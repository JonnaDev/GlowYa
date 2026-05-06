<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOIL — Starry Say Oiled Perfume Body Wash</title>
    <meta name="description" content="El jabón corporal que huele a perfume todo el día. Limpieza profunda con efecto perlado y aroma envolvente de larga duración.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* ----- Aurora background ----- */
        .bg-aurora {
            background:
                radial-gradient(ellipse 70% 60% at 50% -10%, rgba(56, 189, 248, 0.18), transparent 70%),
                radial-gradient(ellipse 50% 50% at 90% 60%, rgba(251, 191, 36, 0.10), transparent 70%),
                radial-gradient(ellipse 60% 60% at 10% 80%, rgba(56, 189, 248, 0.08), transparent 70%),
                #000;
        }

        /* ----- Entry animations ----- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-up { animation: fadeUp .9s cubic-bezier(.2,.8,.2,1) forwards; opacity: 0; }
        .anim-d1 { animation-delay: .15s; }
        .anim-d2 { animation-delay: .35s; }
        .anim-d3 { animation-delay: .55s; }
        .anim-d4 { animation-delay: .80s; }

        @keyframes pulseGold {
            0%, 100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
            50%      { box-shadow: 0 0 0 16px rgba(251, 191, 36, 0); }
        }

        /* ----- Slide VS vertical ----- */
        .vs-wrapper {
            position: relative;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
            aspect-ratio: 9 / 16;
            overflow: hidden;
            border-radius: 28px;
            box-shadow:
                0 25px 80px -20px rgba(56, 189, 248, 0.35),
                0 0 0 1px rgba(251, 191, 36, 0.18) inset;
        }
        .vs-layer {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }
        .vs-before { z-index: 2; clip-path: inset(0 50% 0 0); }
        .vs-before-filter { filter: grayscale(.45) brightness(.85); }
        .vs-after  { z-index: 1; }
        .vs-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: ew-resize;
            z-index: 10;
            -webkit-appearance: none;
            appearance: none;
        }
        .vs-line {
            position: absolute;
            top: 0; bottom: 0;
            left: 50%;
            width: 2px;
            background: linear-gradient(to bottom,
                transparent, #fff 18%, #fff 82%, transparent);
            box-shadow: 0 0 20px rgba(255,255,255,.7);
            z-index: 5;
            pointer-events: none;
            transform: translateX(-50%);
        }
        .vs-handle {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #fde68a 0%, #fbbf24 50%, #d97706 100%);
            color: #0a0a0a;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 13px; letter-spacing: .5px;
            box-shadow: 0 10px 32px rgba(251, 191, 36, 0.55);
            pointer-events: none;
            z-index: 6;
            animation: pulseGold 2.4s infinite;
        }
        .vs-label {
            position: absolute;
            top: 16px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            backdrop-filter: blur(8px);
            z-index: 4;
        }
        .vs-label-before { left: 16px; background: rgba(0,0,0,.5); color: #fca5a5; border: 1px solid rgba(252,165,165,.3); }
        .vs-label-after  { right: 16px; background: rgba(0,0,0,.5); color: #fbbf24; border: 1px solid rgba(251,191,36,.4); }

        /* ----- Sparkles ----- */
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(.4); }
            50%      { opacity: 1; transform: scale(1.2); }
        }
        .sparkle {
            position: absolute;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 10px 2px rgba(255,255,255,.85);
            animation: sparkle 2.4s infinite ease-in-out;
            pointer-events: none;
        }

        /* ----- Glass panels ----- */
        .glass {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-gold {
            background:
                linear-gradient(135deg, rgba(251,191,36,0.06), rgba(56,189,248,0.04)),
                rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(251, 191, 36, 0.25);
        }

        /* ----- Breathing animation ----- */
        @keyframes breathe {
            0%, 100% { transform: scale(1); border-color: rgba(251,191,36,0.25); box-shadow: 0 0 0 0 rgba(251,191,36,0); }
            50%      { transform: scale(1.012); border-color: rgba(251,191,36,0.5); box-shadow: 0 0 40px -10px rgba(251,191,36,0.2); }
        }
        .breathe { animation: breathe 5s ease-in-out infinite; }

        /* ----- Buttons ----- */
        .btn-gold {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
            padding: 1rem 2.2rem;
            border-radius: 9999px;
            font-weight: 800;
            font-size: .95rem;
            letter-spacing: .01em;
            background: linear-gradient(135deg, #fde68a 0%, #fbbf24 50%, #d97706 100%);
            color: #0a0a0a;
            box-shadow: 0 12px 40px -10px rgba(251, 191, 36, 0.55);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 18px 50px -10px rgba(251, 191, 36, 0.75); }
        .btn-gold:active { transform: translateY(0); }

        .btn-sky {
            background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 50%, #0284c7 100%);
            color: #0a0a0a;
            box-shadow: 0 12px 40px -10px rgba(56, 189, 248, 0.6);
        }
        .btn-sky:hover { box-shadow: 0 18px 50px -10px rgba(56, 189, 248, 0.85); }

        /* ----- Reveal on scroll ----- */
        .reveal { opacity: 0; transform: translateY(40px); transition: opacity .9s cubic-bezier(.2,.8,.2,1), transform .9s cubic-bezier(.2,.8,.2,1); }
        .reveal.in { opacity: 1; transform: translateY(0); }

        /* ----- Form ----- */
        .form-input {
            width: 100%;
            padding: .9rem 1rem;
            border-radius: .85rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.10);
            color: #fff;
            font-size: .95rem;
            transition: all .2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #fbbf24;
            background: rgba(255,255,255,0.06);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.12);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.28); }
        .form-label {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
            margin-bottom: .5rem;
            display: block;
        }
        select.form-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23fbbf24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .9rem center; background-size: 1.1rem; appearance: none; padding-right: 2.5rem; }
        select.form-input option { background: #0a0a0a; color: #fff; }

        /* ----- Misc ----- */
        .text-gradient {
            background: linear-gradient(135deg, #fde68a 0%, #fbbf24 35%, #38bdf8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .grain::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 4px 4px;
            mix-blend-mode: overlay;
        }
    </style>
</head>
<body class="bg-black text-white antialiased overflow-x-hidden">

    {{-- Nav fija --}}
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-black/40 border-b border-white/5">
        <div class="max-w-6xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2 text-lg font-black tracking-tight">
                <span class="text-amber-400">NOIL</span><span class="text-sky-400">.</span>
            </a>
            <a href="#comprar" class="px-5 py-2 rounded-full text-xs font-bold bg-white/8 border border-white/10 hover:bg-white/15 transition">
                Pedir ahora
            </a>
        </div>
    </nav>

    {{-- HERO vertical --}}
    <section class="bg-aurora min-h-screen pt-28 pb-16 px-6 flex flex-col items-center justify-center text-center relative">

        <div class="max-w-2xl">
            {{-- Badge --}}
            <div class="anim-fade-up inline-flex items-center gap-2 px-4 py-2 rounded-full glass-gold text-[11px] font-bold uppercase tracking-[0.18em] text-amber-300">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                Lanzamiento exclusivo
            </div>

            {{-- Title --}}
            <h1 class="anim-fade-up anim-d1 mt-7 text-4xl sm:text-5xl md:text-6xl font-black leading-[1.05] tracking-tight">
                <span class="block">El jabón que</span>
                <span class="block text-gradient">huele a perfume</span>
                <span class="block">todo el día</span>
            </h1>

            <p class="anim-fade-up anim-d2 mt-6 text-base md:text-lg text-white/65 font-light max-w-xl mx-auto leading-relaxed">
                NOIL Starry Say. Limpieza profunda con efecto perlado y un aroma envolvente que dura horas en tu piel.
            </p>
        </div>

        {{-- Slide VS --}}
        <div class="anim-fade-up anim-d3 mt-12 w-full">
            <p class="text-[10px] text-white/40 uppercase tracking-[0.22em] mb-4">
                <i class="fa-solid fa-arrows-left-right mr-2 text-amber-400"></i>
                Desliza para ver la diferencia
            </p>

            <div class="vs-wrapper grain" id="vs-wrapper">
                {{-- After (NOIL solution) --}}
                <div class="vs-layer vs-after"
                     data-bg="{{ asset('images_landings/noil-solution.png') }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div id="sparkles" class="absolute inset-0 pointer-events-none"></div>
                </div>

                {{-- Before (pain) --}}
                <div class="vs-layer vs-before vs-before-filter" id="vs-before"
                     data-bg="{{ asset('images_landings/noil-pain.png') }}">
                    <div class="absolute inset-0 bg-black/30"></div>
                </div>

                <span class="vs-label vs-label-before">Antes</span>
                <span class="vs-label vs-label-after">Con NOIL</span>

                <input type="range" min="0" max="100" value="50" class="vs-input" id="vs-input" aria-label="Comparador">
                <div class="vs-line" id="vs-line">
                    <div class="vs-handle">VS</div>
                </div>
            </div>
        </div>

        {{-- CTA hero --}}
        <div class="anim-fade-up anim-d4 mt-12 flex flex-col items-center gap-3">
            <a href="#comprar" class="btn-gold">
                Pedir el mío ahora
                <i class="fa-solid fa-arrow-down text-xs"></i>
            </a>
            <p class="text-[11px] text-white/40">
                <i class="fa-solid fa-truck-fast text-amber-400/70 mr-1"></i> Pago contra entrega ·
                <i class="fa-solid fa-shield-halved text-sky-400/70 ml-1 mr-1"></i> Envío 24-72 hrs
            </p>
        </div>
    </section>

    {{-- FORMULARIO --}}
    <section id="comprar" class="relative bg-aurora py-24 px-6">
        <div class="max-w-xl mx-auto reveal">
            <div class="glass-gold breathe rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-400/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-sky-400/10 blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                        Tu pedido en <span class="text-amber-400">2 minutos</span>
                    </h2>
                    <p class="mt-2 text-white/55 text-sm">
                        Pago contra entrega. Te llamamos para confirmar antes de despachar.
                    </p>

                    @if(session('success'))
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-200 text-sm">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-200 text-sm">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                            <div class="space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('landing.noil.order') }}" method="POST" class="mt-8 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nombre completo</label>
                                <input name="full_name" type="text" required class="form-input"
                                       value="{{ old('full_name') }}" placeholder="Camila Rodríguez">
                            </div>
                            <div>
                                <label class="form-label">Cédula</label>
                                <input name="id_number" type="text" required class="form-input"
                                       value="{{ old('id_number') }}" placeholder="1023456789">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Celular</label>
                                <input name="phone" type="tel" required class="form-input"
                                       value="{{ old('phone') }}" placeholder="3001234567">
                            </div>
                            <div>
                                <label class="form-label">Email</label>
                                <input name="email" type="email" required class="form-input"
                                       value="{{ old('email') }}" placeholder="tu@email.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Departamento</label>
                                <select name="department" required class="form-input">
                                    <option value="">Selecciona…</option>
                                    @foreach([
                                        'Amazonas','Antioquia','Arauca','Atlántico','Bogotá D.C.',
                                        'Bolívar','Boyacá','Caldas','Caquetá','Casanare','Cauca',
                                        'Cesar','Chocó','Córdoba','Cundinamarca','Guainía','Guaviare',
                                        'Huila','La Guajira','Magdalena','Meta','Nariño',
                                        'Norte de Santander','Putumayo','Quindío','Risaralda',
                                        'San Andrés y Providencia','Santander','Sucre','Tolima',
                                        'Valle del Cauca','Vaupés','Vichada'
                                    ] as $dept)
                                        <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Ciudad</label>
                                <input name="city" type="text" required class="form-input"
                                       value="{{ old('city') }}" placeholder="Bogotá">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Barrio (opcional)</label>
                                <input name="neighborhood" type="text" class="form-input"
                                       value="{{ old('neighborhood') }}" placeholder="Chapinero">
                            </div>
                            <div>
                                <label class="form-label">Cantidad</label>
                                <input name="quantity" type="number" min="1" max="5" required class="form-input"
                                       value="{{ old('quantity', 1) }}">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Dirección</label>
                            <input name="address" type="text" required class="form-input"
                                   value="{{ old('address') }}" placeholder="Cra 7 # 32-15 apto 502">
                        </div>

                        <div>
                            <label class="form-label">Notas para el courier (opcional)</label>
                            <textarea name="notes" rows="2" class="form-input"
                                      placeholder="Tocar timbre 3 veces, edificio rejas blancas…">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn-gold w-full mt-6">
                            Confirmar mi pedido
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <p class="text-center text-[11px] text-white/40 mt-4">
                            <i class="fa-solid fa-lock text-amber-400/60 mr-1"></i>
                            Pago contra entrega · Tus datos están protegidos
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIOS zigzag --}}
    <section class="bg-aurora py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="reveal text-3xl sm:text-5xl font-black text-center tracking-tight mb-4">
                Lo que dicen <span class="text-amber-400">quienes ya lo tienen</span>
            </h2>
            <p class="reveal text-center text-white/55 mb-16 max-w-xl mx-auto">
                Más de +5.000 personas en Colombia ya cambiaron su rutina.
            </p>

            <div class="space-y-10">
                {{-- Izquierda --}}
                <div class="reveal flex justify-start">
                    <div class="w-full md:w-2/3 glass-gold breathe rounded-2xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -left-3 text-2xl text-amber-400/60 bg-black rounded-full p-2 border border-amber-400/30"></i>
                        <div class="flex text-amber-400 mb-3 text-sm gap-0.5">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-white/85 leading-relaxed">
                            "Odiaba salir de la ducha y sentir que el olor a limpio se iba en 5 minutos. Con NOIL la textura perlada me encanta y mi piel huele delicioso todo el día."
                        </p>
                        <p class="mt-4 text-sm font-bold text-amber-300">— Camila R. <span class="text-white/40 font-normal ml-1">· Bogotá</span></p>
                    </div>
                </div>

                {{-- Derecha --}}
                <div class="reveal flex justify-end">
                    <div class="w-full md:w-2/3 glass-gold breathe rounded-2xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -right-3 text-2xl text-amber-400/60 bg-black rounded-full p-2 border border-amber-400/30"></i>
                        <div class="flex text-amber-400 mb-3 text-sm gap-0.5">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-white/85 leading-relaxed">
                            "Sufro de piel reseca y los geles normales me irritaban. Desde el Starry Say siento la piel hidratada todo el día. El cambio es real."
                        </p>
                        <p class="mt-4 text-sm font-bold text-amber-300">— Valentina M. <span class="text-white/40 font-normal ml-1">· Medellín</span></p>
                    </div>
                </div>

                {{-- Izquierda --}}
                <div class="reveal flex justify-start">
                    <div class="w-full md:w-2/3 glass-gold breathe rounded-2xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -left-3 text-2xl text-amber-400/60 bg-black rounded-full p-2 border border-amber-400/30"></i>
                        <div class="flex text-amber-400 mb-3 text-sm gap-0.5">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-white/85 leading-relaxed">
                            "Compré uno para probar y ya pedí 3 más. Es literalmente perfume y jabón al mismo tiempo. Súper recomendado para el día a día."
                        </p>
                        <p class="mt-4 text-sm font-bold text-amber-300">— Laura G. <span class="text-white/40 font-normal ml-1">· Cali</span></p>
                    </div>
                </div>
            </div>

            {{-- CTA final --}}
            <div class="reveal mt-20 text-center">
                <h3 class="text-2xl sm:text-4xl font-black tracking-tight mb-3">
                    ¿Listo para sentirlo en tu piel?
                </h3>
                <p class="text-white/55 mb-8">Pago contra entrega. Envío en 24-72 horas.</p>
                <a href="#comprar" class="btn-gold">
                    Comprar ahora
                    <i class="fa-solid fa-arrow-up text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-10 px-6 text-center text-[11px] text-white/30 border-t border-white/5 bg-black">
        <p>© {{ date('Y') }} Glofit · NOIL Starry Say. Todos los derechos reservados.</p>
    </footer>

    <script>
    (() => {
        document.addEventListener('DOMContentLoaded', () => {
            // ---- Aplicar background-image desde data-bg ----
            document.querySelectorAll('[data-bg]').forEach(el => {
                el.style.backgroundImage = `url('${el.dataset.bg}')`;
            });

            // ---- VS slider ----
            const slider = document.getElementById('vs-input');
            const before = document.getElementById('vs-before');
            const line   = document.getElementById('vs-line');

            const update = (v) => {
                const val = Math.max(0, Math.min(100, v));
                before.style.clipPath = `inset(0 ${100 - val}% 0 0)`;
                line.style.left = `${val}%`;
            };
            slider.addEventListener('input', e => update(+e.target.value));

            // ---- Sparkles ----
            const container = document.getElementById('sparkles');
            for (let i = 0; i < 14; i++) {
                const s = document.createElement('div');
                s.className = 'sparkle';
                const size = Math.random() * 4 + 2;
                s.style.width = size + 'px';
                s.style.height = size + 'px';
                s.style.left = (50 + Math.random() * 50) + '%';
                s.style.top = Math.random() * 100 + '%';
                s.style.animationDelay = Math.random() * 2.4 + 's';
                container.appendChild(s);
            }

            // ---- Reveal on scroll ----
            const reveals = document.querySelectorAll('.reveal');
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
            reveals.forEach(r => io.observe(r));

            // ---- Hint animation del slider VS al cargar ----
            setTimeout(() => {
                let v = 50, dir = -1, steps = 0;
                const hint = setInterval(() => {
                    v += dir * 2; steps++;
                    update(v);
                    slider.value = v;
                    if (v <= 32) dir = 1;
                    if (steps > 20) {
                        clearInterval(hint);
                        const ret = setInterval(() => {
                            if (v < 50) { v++; update(v); slider.value = v; }
                            else clearInterval(ret);
                        }, 18);
                    }
                }, 28);
            }, 1700);

            // ---- Smooth scroll ----
            document.querySelectorAll('a[href^="#"]').forEach(a => {
                a.addEventListener('click', e => {
                    const id = a.getAttribute('href');
                    if (id.length > 1) {
                        const target = document.querySelector(id);
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });
        });
    })();
    </script>
</body>
</html>
