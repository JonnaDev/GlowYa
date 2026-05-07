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

### Schema crítico de `orders` (campos extendidos)

La tabla `orders` no es solo un snapshot — es el centro operativo del negocio. Campos obligatorios desde día 1:

**Identidad y trazabilidad**

| Columna | Tipo | Notas |
|---|---|---|
| `id` | bigint PK | |
| `idempotency_key` | string UNIQUE | Generado en cliente o en controller antes de llamar Shopify |
| `shopify_order_id` | string nullable indexed | Llega tras llamada exitosa a Shopify |
| `dropi_order_id` | string nullable indexed | Si se logra extraer del payload |
| `source` | enum (`web_form`, `whatsapp_bot`, `admin_panel`) | KPI de canal de captura — todo canal externo entra siempre por `OrderService` |

**Datos del destinatario (ya no caben en un único `address` libre)**

| Columna | Tipo | Notas |
|---|---|---|
| `recipient_full_name` | string | Aparece en guía del courier |
| `recipient_id_number` | string indexed | **Cédula obligatoria** — anti-fraude COD y exigencia de algunas transportadoras |
| `recipient_phone` | string | Celular para que el courier llame antes de visitar |
| `recipient_email` | string indexed | Magic link de reseña + comprobantes |
| `recipient_department` | string | Departamento (Antioquia, Cundinamarca, Valle, etc.) |
| `recipient_city` | string indexed | Ciudad/municipio — define qué courier puede entregar |
| `recipient_neighborhood` | string nullable | Barrio — algunos couriers lo piden para zonificación |
| `recipient_address_line` | string | Calle, número, complemento (apto, casa, ref) |
| `recipient_notes` | text nullable | "Tocar timbre 3 veces", referencia de fachada |

**Estado y conciliación**

| Columna | Tipo | Notas |
|---|---|---|
| `status_local` | enum | `pending_confirmation`, `confirmed`, `sent_to_shopify`, `fulfilled`, `cancelled` |
| `status_shopify` | string nullable | Espejo del estado real en Shopify (lo actualiza el listener de webhooks) |
| `status_dropi` | string nullable | Estado canónico de Dropi (`ENTREGADO`, `RECHAZADO`, etc.) — fuente para KPI de tasa COD |
| `last_synced_at` | timestamp nullable | Última vez que la conciliación nocturna validó esta orden |
| `cancellation_reason` | enum nullable | `no_coverage`, `no_contact`, `customer_canceled`, `fraud_suspect`, `out_of_stock`, `cod_rejected`, `returned`, `other` — sin esto no hay análisis de pérdidas |

**Pago y costos**

| Columna | Tipo | Notas |
|---|---|---|
| `total_amount` | numeric | Precio que ve el cliente (envío embebido) |
| `shipping_cost_internal` | numeric nullable | **Costo interno de flete estimado** para reporte de margen real. Nunca se muestra al cliente |

> **Nota sobre método de pago:** todo es COD/recaudo en MVP y fase de validación. No se modela `payment_method` como columna porque siempre valdría `cod` (YAGNI). Si en el futuro se agrega transferencia bancaria, PSE u otro método, se agrega la columna con migration cuando llegue ese requerimiento.

**Índices obligatorios:** `idempotency_key`, `shopify_order_id`, `status_local`, `created_at`, `recipient_id_number`, `recipient_city`, `(status_local, created_at)` compuesto para listados del admin.

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
- **Modelo de pago:** 100% COD/recaudo en validación. Sin pasarela online, sin PSE, sin tarjeta. El cliente paga al courier al recibir. El form se envía al controller, controller crea orden local, encola job de envío a Shopify, redirige a página de confirmación.
- **Conciliación nocturna:** comando artisan `orders:reconcile` que compara estado local vs Shopify y marca inconsistencias para revisión manual.

### 8.1 Mapping de estados Dropi → Shopify → local

Dropi expone ~60 estados (incluye duplicados de courier). Solo mapeamos los **canónicos finales** que disparan webhooks útiles a Laravel. Los estados intermedios (`PENDIENTE`, `EN BODEGA`, `EN REPARTO`, etc.) se ignoran a propósito — no aportan info accionable y solo generan ruido de webhooks.

| Estado Dropi | Shopify Status | Por qué |
|---|---|---|
| `ENTREGADO` | `FULFILLED` | 🔴 Disparador del webhook `orders/fulfilled`. Encola `SendReviewRequestEmailJob` con timing correcto (post-entrega real, no post-generación de guía) |
| `CANCELADO` | `CANCELED` | Cancelación pre-envío. Cierre limpio sin pérdida logística. `cancellation_reason = 'customer_canceled'` |
| `RECHAZADO` | `CANCELED` | Cliente rechazó al entregar (pérdida COD). Laravel preserva el matiz en `status_dropi='RECHAZADO'` y `cancellation_reason='cod_rejected'` para KPI |
| `DEVOLUCION` | `CANCELED` | Cliente devolvió producto entregado. `cancellation_reason='returned'` |
| `INDEMNIZADA POR DROPI` | `ARCHIVED` | Caso especial: no se entregó pero Dropi te paga. No es venta ni cancelación pura — archivado. Dispara webhook `orders/closed`, no `orders/cancelled` |

**Estados Dropi explícitamente NO mapeados:** `PENDIENTE_CONFIRMACION`, `PENDIENTE`, `GUIA_GENERADA`, `GUIA_ANULADA`, `PREPARADO PARA TRANSPORTADORA`, `RECOGIDO POR DROPI`, `EN PROCESAMIENTO`, todos los `EN TRANSITO/REPARTO/BODEGA`, `NOVEDAD SOLUCIONADA`, `EN ESPERA DE RX`, `EN CONFIRMACIÓN TELEFÓNICA`, y todos los duplicados de courier (`ENTREGADA`, `ENTREGA EXITOSA`, `REPORTADO ENTREGADO`, `ENTREGA VERIFICADA`, etc. — Dropi normalmente los consolida en `ENTREGADO`).

**Validación empírica obligatoria:** este mapping es teoría hasta que el primer pedido real recorra el flujo completo. En el MVP, loguear **todos los webhooks** de Shopify con payload completo a un archivo durante el ciclo de vida de los primeros 5-10 pedidos. Si un estado importante no dispara webhook, agregar excepción al mapping. Si un webhook se dispara por algo no querido, quitar mapping.

### 8.2 Flujo de confirmación y captura multi-canal

```
┌─────────────┐
│ Form web    │──┐
├─────────────┤  │
│ Bot WhatsApp│──┼──> POST /api/orders ──> OrderService::createFromPayload($payload, $source)
├─────────────┤  │           ↓
│ Admin manual│──┘    Orden local + idempotency_key + status_local='pending_confirmation'
└─────────────┘            ↓
                      SendOrderToShopifyJob (cola, financial_status='paid' incluso COD)
                           ↓
                      Shopify Admin API
                           ↓
                      Dropi (vía app oficial) — orden cae en PENDIENTE_CONFIRMACION
                           ↓
                      Confirmación manual (MVP) o bot WhatsApp (post-MVP)
                           ↓
                      Selección de courier: Dropi auto-filtra los disponibles por ciudad;
                      operador elige entre los habilitados
                           ↓
                      Dropi: GUIA_GENERADA → ... → ENTREGADO
                           ↓
                      Webhook orders/fulfilled → Laravel
                           ↓
                      SendReviewRequestEmailJob encolado +N días
```

**Reglas de captura:**

- **Toda creación de orden pasa por `OrderService::createFromPayload($payload, $source)`.** Único punto de entrada. Form web, bot WhatsApp, admin manual y futuros canales convergen acá.
- **`source` se persiste en `orders.source`** para atribución por canal (KPI de ROAS por origen).
- **No se valida cobertura en el formulario.** El cliente puede pedir desde cualquier ciudad; el operador valida cobertura al confirmar en Dropi. Si la ciudad no tiene courier disponible, se cancela con `cancellation_reason='no_coverage'`. Esta decisión privilegia conversión sobre filtrado preventivo.
- **No hay tabla `coverage_cities`.** Dropi mismo expone los couriers disponibles por ciudad al momento de generar guía — esa es la fuente de verdad, no una réplica nuestra.
- **Confirmación telefónica:**
  - **MVP:** manual desde panel Dropi (operador llama, valida intención y dirección, asigna courier).
  - **Post-MVP:** WhatsApp Business API + agente IA (Lucidbot, Chatea Pro u otro). Decisión de proveedor diferida — ver futura sección dedicada.
- **`financial_status` no se setea desde Laravel** — se deja en el default de Shopify (`pending`), porque refleja la realidad: el cliente todavía no pagó (lo hará al courier).
- **Checkbox "Sincronizar órdenes pagadas automáticamente" en Dropi: DESMARCADO.** Como no habrá nunca un `financial_status='paid'` real (es todo COD), si quedara marcado Dropi no recogería ninguna orden. Desmarcarlo permite que Dropi se traiga todas las órdenes nuevas y caigan en `PENDIENTE_CONFIRMACION` para confirmación manual.

**Regla de adopción de canales externos:** cualquier integración futura de captura (bot WhatsApp, call center, marketplace) **debe poder apuntar a `POST /api/orders` de Glofit**. Si un proveedor solo permite push directo a Dropi y no a webhook propio, se descarta como integración por la pérdida de propiedad de cliente.

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
| Pérdida COD sin métrica (no se distingue rechazo vs no-cobertura vs fraude) | Columna `orders.cancellation_reason` con enum cargado manual o por webhook |
| Lógica de creación duplicada en form web vs bot WhatsApp vs admin | Único punto de entrada `OrderService::createFromPayload($payload, $source)` |
| Estado Dropi no mapeado pasa silencioso (orden colgada) | Mapping mínimo §8.1 + logging empírico de webhooks en primeros 5-10 pedidos reales |
| Bot externo crea órdenes directo en Dropi (regalando cliente al canal) | Regla de adopción §8.2: todo canal externo debe POSTear a `/api/orders`; los que no, se descartan |

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

- [X] Crear repo Git, estructura modular base
- [ ] Configurar Cloudflare frente al dominio
- [X] Crear app en Shopify Partners y obtener credenciales
- [ ] Crear cuenta Sentry y configurar `sentry/sentry-laravel`
- [X] Implementar tabla `orders` con `idempotency_key`
- [X] Implementar `SendOrderToShopifyJob` con reintentos
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
- [X] Tabla `orders` con todos los campos extendidos del §5 (recipient_*, source, cancellation_reason, shipping_cost_internal)
- [X] `OrderService::createFromPayload($payload, $source)` como único punto de creación de órdenes
- [ ] Endpoint `POST /api/orders` autenticado por API key (placeholder para bot WhatsApp post-MVP)
- [ ] Configurar mapping Dropi↔Shopify según §8.1 (5 estados canónicos)
- [ ] **Desmarcar** "Sincronizar órdenes pagadas automáticamente" en panel Dropi (todo es COD)
- [ ] Logger temporal de payloads completos de webhooks Shopify durante primeros 5-10 pedidos reales
- [ ] Política de envío gratis embebido (§16) reflejada en la tabla `products` o configuración de catálogo
- [x] Módulo `Landing` con tabla `landings` (slug, blade_view, product_id, is_active) — §17
- [x] Convención `resources/views/landings/{slug}.blade.php` ↔ `landings.blade_view`
- [x] Catch-all dinámico `/{slug}` al final de `web.php` con regex `[a-z0-9-]+`
- [x] Admin UI Inertia (`/admin/landings`) para toggle activo/inactivo
- [ ] `view()->exists()` defensivo en `PublicLandingController` antes de renderizar
- [ ] Reserved-slugs list validada en `LandingService::create/update` (login, register, admin, dashboard, api, profile, webhooks, storage, up)
- [ ] Comando `php artisan landing:make {slug} --product={id}` cuando se cree la landing #3

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

## 15. Setup de Shopify Partners y conexión inicial

Pendiente de documentar cuando se cierre el setup completo (instalación de app custom, generación de `ACCESS_TOKEN`, configuración de Cloudflare Tunnel para webhooks en dev local, registro de webhooks vía API). Por ahora, decisiones cerradas:

- **Scopes mínimos:** `read_fulfillments,read_inventory,read_orders,write_orders,read_products`. Sin `write_products` (Shopify es source-of-truth del catálogo, Laravel solo lee).
- **API version:** `2026-04`.
- **Flujo de instalación heredado:** desactivado (usar OAuth moderno).
- **URL de callback en dev:** Cloudflare Tunnel apuntando a `php artisan serve`. URL cambia por reinicio del túnel — actualizar en Dev Dashboard cada vez.
- **Dev store:** configurado con país Colombia (no US) para que Dropi funcione correctamente en moneda, dirección y validación de envío.

---

## 16. Política de envío y precios

**Modelo: envío gratis embebido en el precio del producto.**

- El cliente ve un único precio que incluye producto + flete + margen. Sin costo extra al checkout.
- Cálculo manual offline por producto+ciudad (operador estima el flete promedio para las ciudades de mayor venta y carga el precio final en `products.price`).
- **Sin lógica de cálculo de envío en código.** El campo `shipping_cost_internal` en `orders` es solo para reporte interno de margen real, no para mostrar al cliente.

**Por qué este modelo en COD Colombia:**

- Es el estándar del nicho: el cliente no espera ver flete separado, y agregarlo destruye conversión.
- Simplifica el formulario: solo cantidad, no cotizador dinámico.
- Margen sobreestimado cubre el flete promedio; las ciudades caras drenan margen pero el grueso de pedidos compensa.

**Cuándo revisar precios:**

- Reporte mensual de margen real por ciudad (`shipping_cost_internal` vs precio cobrado).
- Si una ciudad sangra margen recurrentemente → marginar esa ciudad como "cobertura limitada" o subir precio del producto en una landing dedicada.
- Si Dropi sube tarifas de courier → recalcular precios de todos los productos activos.

**Lo que NO hacemos (decisiones explícitamente descartadas):**

- ❌ Tarifa plana nacional al checkout — perdemos conversión y honestidad de precio único se rompe.
- ❌ Calculadora dinámica por ciudad/courier — agrega fricción y requiere mantener tabla de tarifas, complejidad innecesaria para el MVP.
- ❌ Tabla `coverage_cities` en DB — Dropi ya filtra couriers por ciudad disponible al confirmar; replicar es deuda de mantenimiento.

---

## 17. Módulo Landing — Convención y registry

Módulo dedicado al ciclo de vida de las landing pages públicas. El objetivo: que activar, desactivar o crear una landing no requiera tocar `web.php` ni redeploy. Toda landing existe como "pieza de marketing" con creatividad propia (HTML/CSS hardcoded en blade) + datos vivos del producto (precio, título, imagen vía DB).

### Decisiones cerradas

- **1 landing : 1 producto** (mono-producto). Combos y variants se modelarán cuando aparezcan, **sin pisar el modelo actual** (los dos casos serán ortogonales — ver Roadmap).
- **Convención de archivos:** cada landing vive en `resources/views/landings/{slug}.blade.php`. La columna `landings.blade_view` guarda la notación Laravel (ej. `landings.noil`). Pueden divergir si admin renombra el slug pero conserva la blade — flexibilidad sin acoplamiento.
- **El slug es la URL pública.** `/{slug}` se resuelve dinámicamente vía DB. La ruta es **catch-all al final de `web.php`** con regex `[a-z0-9-]+` para no pisar rutas estáticas.
- **El admin nunca toca código** para activar/desactivar. Solo toggle desde panel `/admin/landings`.
- **`is_active=false` ⇒ 404 público.** No mostrar versión "pausada" porque indexar zombies daña SEO.
- **El blade no hardcodea precio/título/imagen.** Esos vienen de `$product->*` inyectado por el controller. Solo el creativo (hero, copy del nicho, testimonios, layout) va hardcoded.

### Schema de `landings`

| Columna | Tipo | Notas |
|---|---|---|
| `id` | bigint PK | |
| `slug` | string UNIQUE | URL pública. Validar contra reserved-words list al crear/editar |
| `title` | string | Texto descriptivo para el admin (no se muestra en pública) |
| `blade_view` | string | Notación Laravel (`landings.{slug}`). Puede divergir del slug si admin renombra |
| `product_id` | bigint nullable FK → `products.id` | 1:1 mono-producto. Nullable transitorio MVP — endurecer a NOT NULL cuando admin UI fuerce asignación |
| `is_active` | bool default false | Toggle público. `false` ⇒ 404 |
| `description` | text nullable | Solo admin |
| `timestamps` | | |

**Índices obligatorios:** UNIQUE en `slug`, compuesto en `(is_active, slug)` para queries del controller público.

### Flujo end-to-end

```
1. Importar producto desde Shopify       → products table (vía ProductImporter)
2. Crear blade del landing                → resources/views/landings/{slug}.blade.php
3. Insertar row en landings               → seed/migration o admin UI futuro
                                            (slug, blade_view='landings.{slug}', product_id, is_active=false)
4. Admin activa la landing                → POST /admin/landings/{slug}/toggle → is_active=true
5. Pública sirve /{slug}                  → PublicLandingController resuelve landing + product
                                            → render blade con $landing y $product
6. Form POST /{slug}/order                → PublicOrderController → OrderService::createFromPayload
                                            → SendOrderToShopifyJob (cola) → Shopify → Dropi
```

### Datos disponibles en el blade

El controller inyecta dos variables al render:

- **`$landing`** — modelo `Landing` completo (slug, blade_view, is_active, etc.). Útil para `route('landing.order', ['slug' => $landing->slug])` en el form.
- **`$product`** — el producto asociado vía FK. Si `landing->product_id` es null, fallback a `Product::first()` (transitorio MVP). Si no hay productos en absoluto, 404.

**Regla de oro:**
- Creatividad (HTML, copy, imágenes hero del nicho) ⇒ **hardcoded en blade**.
- Datos volátiles (precio, título, imagen del producto, `shopify_variant_id` para el form) ⇒ **`$product->*`**.

Esto permite cambiar precio en Shopify y que el landing refleje el cambio sin redeploy. Y rediseñar el creativo sin afectar los datos.

### Reglas defensivas

- **`view()->exists($landing->blade_view)` antes de renderizar.** Si la blade fue borrada y la row quedó huérfana, devolver 404 + log a Sentry. **Nunca 500 al usuario público.**
- **Reserved slugs.** Lista negra centralizada en `LandingService` y validada al crear/editar:
  ```
  login, register, dashboard, admin, api, profile, webhooks, storage, up,
  password, email, verify-email, reset-password, forgot-password, logout,
  catalog, orders, products, sales, analytics
  ```
  El catch-all regex `[a-z0-9-]+` ya bloquea uppercase y barras, pero esto previene colisiones accidentales con rutas Laravel.
- **Catch-all al final de `web.php`.** Las rutas dinámicas `/{slug}` y `/{slug}/order` se declaran **después de todas las estáticas y de `auth.php`**. Nunca insertar rutas estáticas debajo del catch-all.
- **CSRF obligatorio en form POST.** El blade usa `@csrf`. El POST `/{slug}/order` está dentro del middleware web por defecto.

### Roadmap evolutivo (no implementar todavía)

**Cuando llegue el primer combo (landing con N productos):**

Agregar tabla pivote `landing_products`:

| Columna | Tipo |
|---|---|
| `landing_id` | FK → `landings` cascadeOnDelete |
| `product_id` | FK → `products` |
| `quantity` | unsigned int default 1 |
| `display_order` | unsigned int |
| `unit_price_override` | decimal(12,2) nullable |

**`landings.product_id` no se deprecia.** Las dos formas conviven y son ortogonales: si el pivote tiene rows ⇒ combo; si solo `product_id` está seteado ⇒ mono-producto. La lógica de resolución vive en `LandingService::resolveProductsFor($landing)`.

**Cuando un producto tenga variants relevantes (jabón con 3 fragancias, talla, color):**

Agregar columna `landings.product_variant_id` nullable FK. Si está seteada, **prevalece sobre `product_id`** al construir el `line_items` para Shopify (Shopify requiere `variant_id`, no `product_id`, en órdenes).

**Cuando crees la landing #3 manualmente y te canse copiar/pegar:**

Comando `php artisan landing:make {slug} --product={id} [--stub=default]`:
- Copia stub blade a `resources/views/landings/{slug}.blade.php`
- Inserta row en `landings` (slug, blade_view, product_id, is_active=false)
- Output: URL preview (`/{slug}`) + URL admin (`/admin/landings`)

**Cuando admin UI lo necesite:**

Endpoints adicionales en `Modules/Landing/Http/Controllers/Admin/LandingController`:
- `update(Landing $landing)` — editar título, descripción, asignar/cambiar `product_id`
- `destroy(Landing $landing)` — soft-delete (no perder URLs por accidente)
- `duplicate(Landing $landing)` — clonar fila + blade con nuevo slug, para A/B testing

### Lo que explícitamente NO modelamos (decisiones descartadas)

- ❌ **Editor visual de landings.** Costo de implementación inflado para 5-10 landings + 1 dev. Si el equipo crece y necesitás eso, migrá a Shogun/PageFly/GemPages — no lo construyas in-house.
- ❌ **CMS dedicado (Strapi, Sanity, Statamic).** Over-engineering para el caso. Blade + tabla `landings` cubren multi-año.
- ❌ **A/B testing automatizado por landing.** Validá manual con dos slugs (`noil` vs `noil-v2`) y comparalo en analytics. Cuando sea repetitivo, automatizá; antes, no.
- ❌ **Editor de copy desde admin (CMS-lite).** Si necesitás cambiar copy seguido, refactorizás el creativo a `$copy->*` desde DB. Pero no antes — reduce libertad creativa por una funcionalidad que probablemente nunca uses.
- ❌ **Subdominio por landing (`noil.glofit.co`).** SEO equivalente a subcarpeta en este volumen, complica DNS y Cloudflare. Mantener `glofit.co/{slug}` plano.

---

**Última revisión:** mayo 2026  
**Stack confirmado:** Laravel 12 + Inertia.js + React + TypeScript + Tailwind + MySQL + Hostinger Premium