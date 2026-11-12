{{--
    Protecciones del formulario de pedido. Incluir dentro del <form>, justo
    después de @csrf, en todas las landings.

    1. Honeypot anti-bots (`extra_reference`): invisible para personas.
    2. Clave de idempotencia (`idempotency_key`): si el mismo formulario llega
       dos veces (doble clic, reintento por mala señal), el servidor devuelve
       el pedido ya creado en vez de crear otro.
    3. Script que bloquea el botón mientras se envía.

    Si se renombra algún campo, actualizar StorePublicOrderRequest y
    PublicOrderController.
--}}
<div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label>No llenar este campo
        <input type="text" name="extra_reference" value="" tabindex="-1" autocomplete="off">
    </label>
</div>

<input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', (string) \Illuminate\Support\Str::uuid()) }}">

<script>
(() => {
    const form = document.currentScript.closest('form');
    if (!form) return;

    const keyInput = form.querySelector('input[name="idempotency_key"]');
    let sending = false;
    let button = null;
    let originalLabel = '';

    form.addEventListener('submit', (event) => {
        if (sending) {
            event.preventDefault();
            return;
        }

        sending = true;
        button = form.querySelector('button[type="submit"]');

        if (button) {
            originalLabel = button.innerHTML;
            button.disabled = true;
            button.textContent = 'Enviando pedido…';
        }
    });

    // Si la persona vuelve atrás y el navegador restaura la página desde su
    // caché, es un intento nuevo (p. ej. corrigió la dirección): se desbloquea
    // el botón y se genera otra clave para que no se descarte como duplicado.
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;

        sending = false;

        if (button) {
            button.disabled = false;
            button.innerHTML = originalLabel;
        }

        if (keyInput && window.crypto && typeof crypto.randomUUID === 'function') {
            keyInput.value = crypto.randomUUID();
        }
    });
})();
</script>
