# INSTRUCCIONES E-COMMERCE — Laravel 12 + Inertia + Shopify + Dropi

Documento de referencia del proyecto. Mantener actualizado conforme evolucione la arquitectura.

---

## 1. Resumen del proyecto

E-commerce propio multi-nicho (inicialmente mono-producto por landing) con backend Laravel y front en React vía Inertia. Las órdenes se generan en mi sistema y se envían a Shopify, que a su vez las propaga a Dropi mediante su integración nativa de socios. Objetivos: control total de diseño, SEO y dominio; evitar plantillas lentas de Shopify; landings personalizadas por producto activadas desde panel admin.

**Flujo de orden:** Cliente → Landing (Inertia/React) → Form → Controller Laravel → Shopify Admin API → Dropi (vía integración Shopify).
**Por que este flujo con Shopify:** Debido a prohibiciones del sistema Dropi. Todos sus endpoints no son posibles consumirlos ni interactuar con ellos si no tienes convenio comercial.

---

## 2. Stack tecnológico

- **Backend:** Laravel 12 + PHP 8.2+
- **Frontend:** Inertia.js + React + TypeScript + TailwindCSS
- **DB:** MySQL (nativo Hostinger)
- **Hosting:** Hostinger Premium (shared)
- **CDN/Proxy:** Cloudflare gratuito al frente del dominio (obligatorio desde día 1)
- **Errores:** Sentry (tier gratuito)
- **Versionado/Deploy:** Git + `git pull` desde SSH del hosting
- **Build front:** `npm run build` en local → commit assets compilados → pull en servidor

**Por qué Inertia y no SPA puro:** mejor SEO, sin auth por tokens, mismo deploy que Laravel, build estático compatible con Hostinger (que no permite Node persistente).

---

## 3. Arquitectura: monolito modular

Un solo proyecto Laravel organizado en módulos por dominio (no microservicios). Estructura sugerida:

```
app/
├── Modules/
│   ├── Catalog/        # Productos, categorías, importación Shopify
│   ├── Landing/        # Configuración de landings activas por producto
│   ├── Orders/         # Creación, estado, conciliación
│   ├── Shopify/        # Cliente API, webhooks, mapeo
│   ├── Dropi/          # Solo lectura/conciliación (vía Shopify)
│   ├── Client/         # Reseñas verificadas, magic links, futura cuenta shadow y tracking
│   └── Admin/          # Panel administrativo
```

Cada módulo expone servicios; los controllers HTTP/Inertia los consumen. Nunca llamar APIs externas directamente desde un controller.

---

## 4. Limitaciones reales de Hostinger Premium

| Recurso              | Límite           | Implicación práctica                          |
|----------------------|------------------|------------------------------------------------|
| PHP workers          | 40               | ~40 peticiones HTTP concurrentes uncached     |
| RAM                  | 2 GB             | Cuello de botella en picos                    |
| CPU                  | 1 core           | Mantener uso bajo 80% en horas pico           |
| Conexiones MySQL     | 50 max           | Apretará antes que los PHP workers            |
| Visitas mensuales    | ~25.000          | Techo declarado del plan                      |
| Inodos               | 400.000          | Suficiente; vigilar logs y caché              |
| Cronjobs             | Ilimitados       | Habilita la estrategia de colas               |
| Procesos persistentes| **No permitidos**| No hay queue:work 24/7 ni Reverb              |
| WebSockets entrantes | **No permitidos**| Solo salientes; Reverb queda en opcion.       |

**Cuándo migrar a VPS:** cuando se vean 503/504 sostenidos, CPU >80% recurrente, o se requieran WebSockets entrantes. Hostinger VPS desde ~$6 USD/mes; migración sin cambios de código.

---

## 5. Base de datos (MySQL)

### Reglas obligatorias

- **Pool de conexiones controlado.** Cerrar conexiones rápido. Evitar `persistent => true` en `config/database.php` (consume slots del límite de 50).
- **Idempotencia en órdenes.** Tabla `orders` con columna `idempotency_key UNIQUE` generada antes de llamar a Shopify. Si el cliente reintenta, se devuelve la orden existente.
- **Snapshot local de cada orden.** No depender de Shopify como única fuente de verdad. Guardar `shopify_order_id`, `dropi_order_id`, `status_local`, `status_shopify`, `status_dropi`, `last_synced_at`.
- **Snapshot de productos importados.** Al importar de Shopify guardar `shopify_updated_at` y un `content_hash` para detectar cambios sin pisar customizaciones de landing.
- **Índices.** En `idempotency_key`, `shopify_order_id`, `status_local`, `created_at`. Sin esto, conciliación se vuelve lenta rápido.
- **Migrations versionadas.** Nunca tocar la DB manualmente desde phpMyAdmin en producción.

### Tablas mínimas iniciales

`users`, `products`, `product_variants`, `landings`, `orders`, `order_items`, `reviews`, `review_invitations`, `webhook_events`, `jobs`, `failed_jobs`, `cache`.

> `products` usa **soft-delete obligatorio** (`deleted_at`). Garantiza que `products.id` sea estable de por vida y que `reviews.product_id` no quede huérfano si re-importas un producto desde Shopify.

---

## 6. Front-end (Inertia.js + React)

### Principios

- **Landing pages:** preferir Blade puro + Tailwind para SEO máximo y carga ultrarrápida. Usar React solo en componentes interactivos (formulario de pedido, galería, contador de stock, countdown).
- **Panel admin:** Inertia + React full. Aquí sí justifica SPA.
- **TypeScript estricto.** `strict: true` en `tsconfig.json`. Tipar props de Inertia con interfaces compartidas.
- **Sin localStorage/sessionStorage** para datos sensibles. Sesión vía Laravel.

### Build y deploy del front

1. `npm run build` en local
2. Commit de los assets compilados a la rama `main` (en `public/build/`)
3. SSH al hosting → `git pull origin main`
4. `php artisan migrate --force`
5. `php artisan optimize:clear && php artisan optimize`

### SEO obligatorio

- Meta tags dinámicos por producto (title, description, OG, Twitter Card).
- `sitemap.xml` generado por comando artisan, regenerado en cada importación/activación.
- URLs limpias con slug del producto. **Decidir hoy** si será `dominio.com/producto/{slug}` o subdominio por nicho — cambiarlo después destroza SEO.
- Schema.org `Product` y `Offer` en JSON-LD.

---

## 7. Manejo de colas y jobs (sin queue:work persistente)

### Estrategia: cron + `--stop-when-empty`

Crontab del hosting:

```cron
# Scheduler de Laravel
* * * * * cd /home/USER/htdocs/app && php artisan schedule:run >> /dev/null 2>&1

# Worker de colas (corre cada minuto, sale cuando vacía)
* * * * * cd /home/USER/htdocs/app && php artisan queue:work --stop-when-empty --max-time=55 --tries=3 --backoff=10 >> /dev/null 2>&1
```

### Qué va a colas (asíncrono)

- Envío de orden a Shopify (`SendOrderToShopifyJob`)
- Sincronización de estado de orden desde webhooks
- Importación de productos desde Shopify
- Envío de emails transaccionales
- **Solicitud de reseña post-entrega** (`SendReviewRequestEmailJob`, encolado +N días desde `orders/fulfilled`)
- Generación de reportes
- Reintentos de operaciones fallidas

### Qué va inline (síncrono en request)

- Validación de formulario y creación local de la orden con `idempotency_key`
- Respuesta inmediata al cliente con número de orden local

**Latencia máxima esperada:** ~1 minuto desde orden creada hasta enviada a Shopify. Aceptable para e-commerce dropshipping.

### Driver de cola

`QUEUE_CONNECTION=database` en `.env`. Redis no disponible en shared. Tabla `jobs` con índice en `queue` y `available_at`.

### Reintentos y backoff

Todos los Jobs que llamen a APIs externas implementan `tries = 5`, `backoff = [10, 30, 60, 300, 900]` segundos. Job que falle tras todos los reintentos cae a `failed_jobs` y dispara alerta a Sentry.

---

## 8. Flujo de pedidos: Laravel → Shopify → Dropi

```
┌─────────┐    ┌──────────┐    ┌───────────┐    ┌─────────┐    ┌───────┐
│ Cliente │───>│ Landing  │───>│ Laravel   │───>│ Shopify │───>│ Dropi │
└─────────┘    │ (Inertia)│    │ (orden    │    │ (Admin  │    │       │
               └──────────┘    │  local +  │    │  API)   │    └───────┘
                               │  job)     │    └─────────┘
                               └───────────┘         │
                                     ▲               │ webhook (Reverb se ira implementando despues.)
                                     └───────────────┘
```

### Reglas críticas

- **Laravel NUNCA llama a Dropi directamente.** Dropi solo acepta integraciones autorizadas; Shopify ya es el partner autorizado. Mantener este flujo resuelve el problema de IPs.
- **Shopify rate limits:** REST 40 req/s, GraphQL cost-based. Por eso todo va a colas.
- **Webhooks de Shopify** hacia endpoints `/webhooks/shopify/*` con verificación de HMAC obligatoria. Eventos mínimos: `orders/updated`, `orders/fulfilled`, `orders/cancelled`, `products/update`, `inventory_levels/update`.
- **Endpoint de pago:** PSE/tarjeta débito sin pasarela propia → form se envía al controller, controller crea orden local, encola job de envío a Shopify, redirige a página de confirmación.
- **Conciliación nocturna:** comando artisan `orders:reconcile` que compara estado local vs Shopify y marca inconsistencias para revisión manual.

---

## 9. WebSockets / tiempo real

**No disponible en Hostinger Premium para conexiones entrantes.** Alternativas para el panel admin:

- **MVP:** polling cada 10-15s con `setInterval` en React (suficiente).
- **Crecimiento:** Pusher tier gratuito (100 conexiones concurrentes, 200k mensajes/día).
- **Largo plazo:** VPS + Laravel Reverb cuando el volumen lo justifique.

No invertir tiempo en Reverb hasta migrar a VPS.

---

## 10. Deployment

### Local → GitHub → Hostinger

1. Desarrollo local con `php artisan serve` + `npm run dev`
2. Build front: `npm run build`
3. Commit (incluyendo `public/build/`)
4. Push a `main`
5. SSH al hosting:
   ```bash
   cd ~/htdocs/dominio
   git pull origin main
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

### Variables de entorno

`.env` nunca en git. Configurar manualmente la primera vez en el hosting. Mínimo requerido: `APP_KEY`, `DB_*`, `SHOPIFY_API_KEY`, `SHOPIFY_API_SECRET`, `SHOPIFY_WEBHOOK_SECRET`, `SHOPIFY_STORE`, `SENTRY_LARAVEL_DSN`.

### Caché de Laravel

En producción siempre `config:cache`, `route:cache`, `view:cache`. Limpiar (`optimize:clear`) tras cada deploy y volver a cachear.

---

## 11. Mitigación de riesgos prioritarios

| Riesgo                                | Mitigación                                              |
|---------------------------------------|----------------------------------------------------------|
| Doble click en pagar genera 2 órdenes | `idempotency_key` único antes de llamar Shopify         |
| Shopify caído → orden perdida         | Job con reintentos + tabla `failed_jobs` + alerta       |
| Webhook de Shopify se pierde          | Comando `orders:reconcile` nocturno                      |
| Pico de tráfico tumba PHP workers     | Cloudflare + caché de landings públicas                  |
| MySQL connections agotadas            | Sin `persistent`, queries cortas, índices                |
| Cambio de producto en Shopify pisa landing | `content_hash` + flag manual de "permitir overwrite" |
| Dropi rechaza IP                      | Nunca llamar Dropi directo; siempre vía Shopify          |
| Reseñas falsas / spam de competencia  | Magic link firmado vinculado a `order_item_id` + moderación admin obligatoria (`status = pending` por defecto) |
| Re-importar producto deja reseñas huérfanas | Soft-delete + upsert por `shopify_product_id` mantiene `products.id` estable |

---

## 12. Roadmap de escalabilidad

**Fase 1 — Validación (0-6 meses):** Hostinger Premium, mono-producto, polling para admin, sin Pusher. Foco en SEO y conversión.

**Fase 2 — Crecimiento (6-12 meses):** Multi-producto activo, Pusher para admin si se justifica, primer audit de performance. Posible migración a Hostinger Business si se acerca al techo de visitas.

**Fase 3 — Escala (12+ meses):** Migrar a VPS Hostinger ($6-10/mes) o DigitalOcean. Habilitar Redis, queue workers persistentes con Supervisor, Reverb para tiempo real. Considerar separar admin en subdominio con su propio recurso.

**Reglas para que la migración a VPS no duela:**

- Todo en variables de entorno desde el día 1.
- Nada hardcoded a paths de Hostinger.
- Storage en `storage/app/public` con symlink, nunca en rutas absolutas.
- Logs vía Sentry, no dependiendo del sistema de archivos del shared.
- Código sin asumir extensiones específicas de Hostinger.

---

## 13. Checklist de inicio del proyecto

- [ ] Crear repo Git, estructura modular base
- [ ] Configurar Cloudflare frente al dominio
- [ ] Crear app en Shopify Partners y obtener credenciales
- [ ] Crear cuenta Sentry y configurar `sentry/sentry-laravel`
- [ ] Implementar tabla `orders` con `idempotency_key`
- [ ] Implementar `SendOrderToShopifyJob` con reintentos
- [ ] Configurar crontab con `schedule:run` + `queue:work --stop-when-empty`
- [ ] Implementar verificación HMAC de webhooks Shopify
- [ ] Implementar comando `orders:reconcile`
- [ ] SEO base: sitemap, meta tags dinámicos, JSON-LD
- [ ] Definir estructura de URLs (subdominio vs subcarpeta) — **antes de publicar**
- [ ] Tablas `reviews` y `review_invitations` con FK a `order_items` y `products`
- [ ] `SendReviewRequestEmailJob` con magic link (`URL::temporarySignedRoute`, 30 días)
- [ ] Vista pública de formulario de reseña (sin auth, validada por firma)
- [ ] Cola de moderación de reseñas en panel admin (aprobar / rechazar / spam)
- [ ] Decidir storage de fotos de reseña (Cloudflare R2 / Bunny vs `storage/app/public`) — variable `REVIEWS_DISK`
- [ ] Sección "Confía en nosotros" en home con reseñas globales aprobadas (cacheada)

---

## 14. Módulo Client / Reseñas

Módulo dedicado al ciclo de vida de reseñas verificadas y futuras funcionalidades orientadas al cliente final (cuenta shadow, tracking de pedido en mi dominio, repeat purchase). Foco actual: capturar reseñas reales post-entrega sin obligar al cliente a registrarse.

### Decisiones cerradas

- **Reseña por producto, no por orden.** Si una orden incluye combo (varios productos), el cliente califica cada producto por separado. Esto mantiene el rating de cada producto limpio y permite reusar reseñas pasadas cuando el producto vuelve a importarse en una nueva landing.
- **Sin registro obligatorio.** Compra como invitado. La identidad se valida con un magic link firmado enviado al email del pedido.
- **Moderación obligatoria.** Toda reseña entra como `pending`. Solo se vuelve pública cuando admin la aprueba (`approved`).
- **Almacenamiento en mi sistema, no en Shopify.** Independiente del proveedor; sigue funcionando si en el futuro migro fuera de Shopify.

### Flujo end-to-end

```
1. Cliente compra (form guest) → orden creada con email + idempotency_key
2. Webhook orders/fulfilled de Shopify → snapshot status_shopify = fulfilled
3. Job programado a +N días desde fulfilled → SendReviewRequestEmailJob por cada order_item
4. Email lleva URL firmada (URL::temporarySignedRoute) válida 30 días
5. Cliente abre link → formulario público (rating, comentario, foto opcional)
6. Submit → reviews.status = 'pending', verified_purchase = true
7. Admin revisa en panel → approved | rejected
8. approved → visible en ficha de producto y en sección "Confía en nosotros" del home
```

### Schema de `reviews`

| Columna | Tipo | Notas |
|---|---|---|
| `id` | bigint PK auto | |
| `order_item_id` | bigint FK → `order_items.id` | UNIQUE — una reseña por línea de orden |
| `product_id` | bigint FK → `products.id` | Denormalizado para queries rápidas en landing/home |
| `email_user` | string indexed | Email del comprador |
| `name_user` | string | Nombre mostrado en la reseña |
| `rating` | tinyint 1-5 | Sin esto no hay promedio ni filtro por estrellas |
| `comment` | text | |
| `photo_path` | string nullable | Path/URL al storage definido por `REVIEWS_DISK` |
| `status` | enum('pending','approved','rejected') | Default `pending` |
| `verified_purchase` | bool default true | Garantizado por la firma del magic link |
| `ip_address` | string nullable | Detección de abuso |
| `user_agent` | string nullable | Detección de abuso |
| `created_at` / `updated_at` | timestamps | |

**Índices:** compuesto (`product_id`, `status`) para "reseñas aprobadas por producto", `email_user`, `created_at`.

### Schema de `review_invitations` (medición de funnel)

No guarda el token — la firma es la verdad. Solo registra eventos para medir conversión del email de reseña.

| Columna | Tipo | Notas |
|---|---|---|
| `id` | bigint PK | |
| `order_item_id` | bigint FK UNIQUE | Una invitación por línea |
| `sent_at` | timestamp | |
| `opened_at` | timestamp nullable | |
| `submitted_at` | timestamp nullable | |
| `created_at` / `updated_at` | timestamps | |

### Magic link

- Generado con `URL::temporarySignedRoute('reviews.create', ['order_item' => $id, 'email' => sha1($email)], now()->addDays(30))`.
- Validación automática por Laravel; sin tabla de tokens ni gestión manual.
- Al abrir, validar que **no exista ya** un `review` para ese `order_item_id`. Si existe, mostrar pantalla de "ya calificaste este producto".

### Storage de fotos

- Resize obligatorio en upload: max 1200px lado mayor, peso <500 KB.
- Variable de entorno `REVIEWS_DISK` para no acoplar código a la decisión.
- **Decisión pendiente:** Cloudflare R2 / Bunny CDN (recomendado, encaja con Cloudflare ya en uso) vs `storage/app/public/reviews/{year}/{month}/` (MVP barato, pero come inodos del shared).

### Reusabilidad entre re-importaciones

`products` aplica **soft-delete obligatorio** + upsert por `shopify_product_id` al importar. Esto garantiza que `products.id` sea estable de por vida → `reviews.product_id` nunca queda huérfano. Si re-activo un producto que ya tuvo reseñas, aparecen automáticamente en la nueva landing sin código adicional.

### Sección "Confía en nosotros" del home

Query global con reseñas aprobadas de distintos productos:

```sql
SELECT r.*, p.name, p.slug
FROM reviews r
JOIN products p ON p.id = r.product_id
WHERE r.status = 'approved' AND r.rating >= 4
ORDER BY r.created_at DESC
LIMIT 12
```

Cacheable 10-15 min en driver `cache` (database) para no pegarle al MySQL en cada visita al home.

### Panel admin (cola de moderación)

`Modules/Admin` consume `ReviewService` de `Modules/Client`. Vistas mínimas:

- Lista de pendientes con badge de conteo en sidebar.
- Detalle: rating, comentario, foto, datos del cliente, link a la orden original.
- Acciones: **aprobar** / **rechazar** / **marcar spam**.

### Frontera entre módulos

- `Modules/Client` es **dueño** del modelo `Review`, migrations, jobs, controllers de magic link y formulario público.
- `Modules/Catalog` y `Modules/Landing` consumen vía `ReviewService::getApprovedForProduct($productId)`.
- `Modules/Admin` consume vía `ReviewService::pending()`, `approve($id)`, `reject($id)`.
- **Ningún módulo accede al modelo `Review` directamente** — siempre vía servicio. Esto deja libertad de cambiar el almacenamiento (cache, externo) sin tocar fuera de `Client`.

### Roadmap del módulo (no implementar ahora)

- Cuenta "shadow" auto-creada al comprar, activable por el cliente desde el mismo magic link para ver historial.
- Tracking del pedido en mi dominio (proxy de info de Shopify/Dropi) detrás del mismo magic link → razón fuerte para que abran el email.
- Cupón de descuento en email post-reseña para fomentar repeat purchase.
- Migrar `reviews` a `order_item_id` siempre se mantiene; ya está así desde día 1, así que combos no requieren migración futura.

---

**Última revisión:** mayo 2026  
**Stack confirmado:** Laravel 12 + Inertia.js + React + TypeScript + Tailwind + MySQL + Hostinger Premium
