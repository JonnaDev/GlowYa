<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOIL Body Wash — El gel que limpia profundo y huele a perfume todo el día</title>
    <meta name="description" content="NOIL Starry Say: ácido salicílico suave, minerales marinos y fragancia encapsulada. Sin sulfatos agresivos. Limpieza profunda para piel masculina. Entrega a todo Colombia.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --noil-blue: #38bdf8;
            --noil-blue-dark: #0284c7;
            --noil-black: #050a12;
            --noil-surface: rgba(255,255,255,0.04);
            --noil-border: rgba(56,189,248,0.18);
        }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--noil-black); color: #fff; overflow-x: hidden; }
        h1,h2,h3,.display { font-family: 'Space Grotesk', system-ui, sans-serif; }

        .bg-noil {
            background:
                radial-gradient(ellipse 80% 60% at 50% -5%, rgba(56,189,248,0.15), transparent 65%),
                radial-gradient(ellipse 50% 50% at 95% 50%, rgba(14,165,233,0.08), transparent 60%),
                radial-gradient(ellipse 50% 60% at 5% 90%, rgba(56,189,248,0.07), transparent 60%),
                var(--noil-black);
        }
        .grid-lab {
            background-image: linear-gradient(rgba(56,189,248,0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(56,189,248,0.05) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            border: 1px solid var(--noil-border);
        }
        .glass-strong {
            background: linear-gradient(135deg, rgba(56,189,248,0.07), rgba(2,132,199,0.04));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(56,189,248,0.25);
            box-shadow: 0 30px 80px -20px rgba(2,132,199,0.3), inset 0 1px 0 rgba(255,255,255,0.06);
        }
        .btn-noil {
            position: relative; display: inline-flex; align-items: center; justify-content: center;
            gap: .65rem; padding: 1rem 2.2rem; border-radius: 9999px; font-weight: 800;
            font-size: .95rem; letter-spacing: .01em;
            background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 50%, #0284c7 100%);
            color: #050a12;
            box-shadow: 0 14px 40px -10px rgba(56,189,248,0.55), inset 0 1px 0 rgba(255,255,255,0.3);
            transition: transform .25s ease, box-shadow .25s ease; overflow: hidden;
        }
        .btn-noil:hover { transform: translateY(-3px); box-shadow: 0 22px 55px -12px rgba(56,189,248,0.75); }
        .btn-noil:active { transform: translateY(-1px); }

        .eyebrow {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .4rem .9rem; border-radius: 999px;
            background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.25);
            font-size: 10px; font-weight: 800; letter-spacing: 0.18em;
            text-transform: uppercase; color: #7dd3fc;
        }
        .eyebrow .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #38bdf8; box-shadow: 0 0 10px #38bdf8;
            animation: pulse-dot 1.6s ease-in-out infinite;
        }
        @keyframes pulse-dot { 0%,100%{opacity:.6} 50%{opacity:1} }

        .text-gradient { background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 50%, #06b6d4 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }

        .vertical-card {
            position: relative; width: 100%; max-width: 420px; margin: 0 auto;
            aspect-ratio: 9/16; border-radius: 28px; overflow: hidden;
            box-shadow: 0 40px 100px -30px rgba(56,189,248,0.35), 0 0 0 1px rgba(56,189,248,0.18);
        }
        .vertical-card img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .vc-tag {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .3rem .75rem; border-radius: 999px;
            font-size: 10px; font-weight: 800; letter-spacing: 0.18em;
            text-transform: uppercase; margin-bottom: .65rem;
        }
        .vc-tag-pain { background: rgba(244,63,94,0.2); color: #fb7185; border: 1px solid rgba(244,63,94,0.3); }
        .vc-tag-solution { background: rgba(56,189,248,0.15); color: #38bdf8; border: 1px solid rgba(56,189,248,0.35); }

        .feature-card {
            padding: 1.75rem 1.5rem; border-radius: 20px;
            background: rgba(255,255,255,0.03); border: 1px solid rgba(56,189,248,0.12);
            transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(56,189,248,0.35); box-shadow: 0 30px 70px -20px rgba(2,132,199,0.3); }
        .feature-icon {
            display: inline-flex; width: 52px; height: 52px; border-radius: 16px;
            background: linear-gradient(135deg, rgba(56,189,248,0.15), rgba(2,132,199,0.2));
            border: 1px solid rgba(56,189,248,0.2); color: #38bdf8;
            align-items: center; justify-content: center; font-size: 20px; margin-bottom: 1rem;
        }

        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
        .marquee { display: flex; gap: 3rem; width: max-content; animation: marquee 28s linear infinite; }
        .marquee-item { display: inline-flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #7dd3fc; opacity: .7; }

        .reveal { opacity: 0; transform: translateY(36px); transition: opacity .9s cubic-bezier(.2,.8,.2,1), transform .9s cubic-bezier(.2,.8,.2,1); }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .reveal-scale { opacity: 0; transform: scale(.93); transition: opacity .9s cubic-bezier(.2,.8,.2,1), transform .9s cubic-bezier(.2,.8,.2,1); }
        .reveal-scale.in { opacity: 1; transform: scale(1); }

        .stat-num { font-family: 'Space Grotesk',sans-serif; font-size: clamp(2.2rem,5vw,3.5rem); font-weight: 800; line-height: 1; background: linear-gradient(135deg, #7dd3fc, #38bdf8); -webkit-background-clip: text; background-clip: text; color: transparent; }

        .floating-cta { position: fixed; right: 1.25rem; bottom: 1.25rem; z-index: 60; opacity: 0; transform: translateY(20px) scale(.9); transition: opacity .4s ease, transform .4s cubic-bezier(.2,.8,.2,1); pointer-events: none; }
        .floating-cta.visible { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
        @keyframes pulse-ring { 0%{box-shadow:0 0 0 0 rgba(56,189,248,.55),0 18px 40px -10px rgba(2,132,199,.55)} 70%{box-shadow:0 0 0 16px rgba(56,189,248,0),0 22px 55px -12px rgba(2,132,199,.65)} 100%{box-shadow:0 0 0 0 rgba(56,189,248,0),0 18px 40px -10px rgba(2,132,199,.55)} }
        .floating-cta.visible { animation: pulse-ring 2.4s ease-out infinite; }

        .faq-item summary { cursor: pointer; list-style: none; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item[open] .faq-icon { transform: rotate(45deg); }
        .faq-icon { transition: transform .3s ease; }

        .form-input {
            width: 100%; padding: .9rem 1rem; border-radius: .85rem;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(56,189,248,0.18);
            color: #fff; font-size: .95rem; transition: all .2s;
        }
        .form-input:focus { outline: none; border-color: #38bdf8; background: rgba(56,189,248,0.06); box-shadow: 0 0 0 4px rgba(56,189,248,0.12); }
        .form-input::placeholder { color: rgba(255,255,255,0.28); }
        .form-label { font-size: .65rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: rgba(56,189,248,0.8); margin-bottom: .5rem; display: block; }
        select.form-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .9rem center; background-size: 1.1rem; appearance: none; padding-right: 2.5rem; }
        select.form-input option { background: #0a131e; color: #fff; }
    </style>
</head>
<body class="antialiased">

    {{-- NAV --}}
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-black/50 border-b border-sky-400/10">
        <div class="max-w-6xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2 text-base font-black tracking-tight">
                <span class="inline-flex w-7 h-7 rounded-lg items-center justify-center bg-gradient-to-br from-sky-400 to-sky-700 text-black text-xs"><i class="fa-solid fa-droplet"></i></span>
                <span class="text-white">NOIL</span><span class="text-sky-400">.</span>
                <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-[0.18em] text-sky-400 ml-1">Body Wash</span>
            </a>
            <a href="#comprar" class="px-5 py-2 rounded-full text-xs font-bold bg-sky-500/10 border border-sky-400/25 text-sky-300 hover:bg-sky-500/20 transition">
                Pedir ahora
            </a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="bg-noil relative pt-32 pb-20 px-6 min-h-screen flex flex-col items-center justify-center text-center overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-40 pointer-events-none"></div>
        <div id="hero-particles" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

        <div class="relative max-w-3xl">
            <span class="eyebrow"><span class="dot"></span>Fórmula masculina · Uso diario seguro</span>

            <h1 class="display mt-7 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-[1.05] tracking-tight">
                Tu piel limpia y sin granitos.<br>
                <span class="text-gradient">El olor que dura todo el día.</span>
            </h1>

            <p class="mt-7 text-base md:text-lg text-white/60 font-light max-w-2xl mx-auto leading-relaxed">
                <strong class="font-semibold text-white">NOIL Body Wash</strong> combina ácido salicílico suave, minerales marinos y fragancia encapsulada de liberación lenta para una limpieza profunda que no irrita ni reseca.
                <strong class="font-semibold text-sky-300">Diferencia visible en la primera semana.</strong>
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs font-semibold text-white/50">
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-flask text-sky-400"></i> Ácido Salicílico Suave</span>
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-sky-400"></i> Sin Sulfatos Agresivos</span>
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-leaf text-sky-400"></i> Fragancia 8h</span>
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-microscope text-sky-400"></i> Minerales Marinos</span>
            </div>

            <div class="mt-10 flex flex-col items-center gap-3">
                <a href="#comprar" class="btn-noil">
                    Quiero mi NOIL Body Wash
                    <i class="fa-solid fa-arrow-down text-xs"></i>
                </a>
                <p class="text-[11px] text-white/40 font-medium">
                    <i class="fa-solid fa-truck-fast text-sky-400/70 mr-1"></i>Pago contra entrega ·
                    <i class="fa-solid fa-shield-halved text-sky-400/70 ml-1 mr-1"></i>Envío 24-72 hrs Colombia
                </p>
            </div>

            <div class="mt-14 grid grid-cols-3 gap-4 max-w-2xl mx-auto">
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="380">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-white/40">reseñas<br>verificadas</p>
                </div>
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="8">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-white/40">horas de<br>fragancia</p>
                </div>
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="4">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-white/40">activos<br>clave</p>
                </div>
            </div>
        </div>

        <a href="#dolor" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-sky-400 hover:text-sky-300 transition" aria-label="Bajar">
            <i class="fa-solid fa-chevron-down text-2xl animate-bounce"></i>
        </a>
    </section>

    {{-- MARQUEE --}}
    <section class="relative py-8 border-y border-sky-400/15 overflow-hidden" style="background:rgba(56,189,248,0.04)">
        <div class="marquee">
            @php $items = ['Ácido Salicílico Suave','Sin Sulfatos Agresivos','Minerales Marinos','pH Balanceado','Sin Parabenos','Fragancia 8h','Piel Masculina','Cruelty Free','Uso Diario Seguro','Test Dermatológico','Ácido Salicílico Suave','Sin Sulfatos Agresivos','Minerales Marinos','pH Balanceado','Sin Parabenos','Fragancia 8h','Piel Masculina','Cruelty Free','Uso Diario Seguro','Test Dermatológico']; @endphp
            @foreach($items as $item)
                <span class="marquee-item">
                    <i class="fa-solid fa-circle text-[5px] text-sky-400"></i>
                    {{ $item }}
                </span>
            @endforeach
        </div>
    </section>

    {{-- DOLOR --}}
    <section id="dolor" class="bg-noil relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-25 pointer-events-none"></div>
        <div class="relative max-w-4xl mx-auto text-center mb-14">
            <span class="eyebrow reveal" style="background:rgba(244,63,94,0.12);border-color:rgba(244,63,94,0.25);color:#fb7185;">
                <span class="dot" style="background:#f43f5e;box-shadow:0 0 10px #fb7185;"></span>
                El problema real
            </span>
            <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                La piel que no huele bien<br><span class="text-gradient">es la que nadie te dice que notan</span>
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg text-white/55 max-w-2xl mx-auto leading-relaxed">
                Granitos en espalda, olor que se va en 2 horas y la sensación de que el gel que usas no hace nada real. El problema no eres tú — es el producto.
            </p>
        </div>

        <div class="reveal-scale max-w-lg mx-auto">
            <div class="vertical-card">
                <img src="{{ asset('images_landings/noil/noilbodywash-pain.png') }}" alt="Piel con granitos e irritación — problema que NOIL resuelve">
            </div>
            <ul class="mt-10 space-y-3 max-w-md mx-auto text-white/80">
                <span class="vc-tag vc-tag-pain"><i class="fa-solid fa-heart-crack text-[9px]"></i> El dolor invisible</span>
                <p class="text-base sm:text-lg font-semibold leading-snug text-white">
                    "Llevo años con granitos en la espalda y ningún gel del mercado los quita."
                </p>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-500/20 text-rose-400 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Geles con sulfatos que irritan, resecan y empeoran la situación.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-500/20 text-rose-400 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Fragancias que se evaporan antes de salir del baño.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-500/20 text-rose-400 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Poros tapados, espalda llena de granitos, piel sin vida.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-500/20 text-rose-400 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Pagar más de $40.000 por algo que no hace diferencia real.</p>
                </li>
            </ul>
        </div>
    </section>

    {{-- SOLUCIÓN --}}
    <section class="relative py-24 px-6 overflow-hidden" style="background:linear-gradient(180deg,rgba(56,189,248,0.06) 0%,var(--noil-black) 100%)">
        <div class="absolute inset-0 grid-lab opacity-20 pointer-events-none"></div>
        <div class="relative max-w-4xl mx-auto text-center mb-14">
            <span class="eyebrow reveal"><span class="dot"></span>La solución clínica</span>
            <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                Un gel que <span class="text-gradient">trabaja de verdad</span><br>desde la primera semana
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg text-white/55 max-w-2xl mx-auto leading-relaxed">
                NOIL combina <strong class="text-white">ácido salicílico suave + minerales marinos + fragancia encapsulada</strong> — los activos que disuelven el sebo, equilibran el pH y dejan un olor que dura hasta 8 horas sin irritar.
            </p>
        </div>

        <div class="reveal-scale max-w-lg mx-auto">
            <div class="vertical-card">
                <img src="{{ asset('images_landings/noil/noilbodywash-solution.png') }}" alt="Piel limpia y sin granitos con NOIL Body Wash">
            </div>
            <ul class="mt-10 space-y-3 max-w-md mx-auto text-white/80">
                <span class="vc-tag vc-tag-solution"><i class="fa-solid fa-sparkles text-[9px]"></i> El cuerpo que proyectas</span>
                <p class="text-base sm:text-lg font-semibold leading-snug text-white">
                    "Por primera vez siento que mi piel está limpia de verdad y el olor aguanta el día."
                </p>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Ácido salicílico disuelve el sebo sin agredir la barrera cutánea.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Minerales marinos equilibran el pH y reducen la inflamación activa.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Sin sulfatos — limpieza profunda sin resecar ni irritar.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Fragancia encapsulada de liberación lenta — dura hasta 8 horas.</p>
                </li>
            </ul>
        </div>
        <div class="reveal mt-14 text-center">
            <a href="#comprar" class="btn-noil">Quiero probar NOIL <i class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
    </section>
</body>
</html>
