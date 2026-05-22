<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOIL Body Wash — El gel que limpia profundo y huele a perfume todo el día</title>
    <meta name="description" content="NOIL Starry Say: ácido salicílico suave, minerales marinos y fragancia encapsulada. Sin sulfatos agresivos. Limpieza profunda para piel masculina. Entrega a todo Colombia.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="NOIL Body Wash — El gel que limpia profundo y huele a perfume todo el día">
    <meta property="og:description" content="NOIL Starry Say: ácido salicílico suave, minerales marinos y fragancia encapsulada. Sin sulfatos agresivos. Limpieza profunda para piel masculina. Entrega a todo Colombia.">
    <meta property="og:image" content="{{ asset('images_landings/noil/noilbodywash-solution.webp') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="NOIL Body Wash — El gel que limpia profundo y huele a perfume todo el día">
    <meta property="twitter:description" content="NOIL Starry Say: ácido salicílico suave, minerales marinos y fragancia encapsulada. Sin sulfatos agresivos. Limpieza profunda para piel masculina. Entrega a todo Colombia.">
    <meta property="twitter:image" content="{{ asset('images_landings/noil/noilbodywash-solution.webp') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
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
                <img src="{{ asset('images_landings/noil/noilbodywash-pain.webp') }}" alt="Piel con granitos e irritación — problema que NOIL resuelve" width="420" height="747" loading="lazy" class="w-full h-full object-cover block">
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
                <img src="{{ asset('images_landings/noil/noilbodywash-solution.webp') }}" alt="Piel limpia y sin granitos con NOIL Body Wash" width="420" height="747" loading="lazy" class="w-full h-full object-cover block">
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

    {{-- DIFERENCIAS / FEATURES --}}
    <section class="bg-noil relative py-24 px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="eyebrow reveal"><span class="dot"></span>¿Por qué NOIL y no otro?</span>
                <h2 class="reveal display mt-6 text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Fórmula premium para <span class="text-gradient">resultados reales</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="feature-card reveal">
                    <div class="feature-icon"><i class="fa-solid fa-droplet"></i></div>
                    <h3 class="text-lg font-bold text-white mb-2">Ácido Salicílico Suave</h3>
                    <p class="text-sm text-white/60 leading-relaxed">Disuelve el sebo atrapado en los poros de la espalda y pecho sin resecar ni dañar la barrera cutánea.</p>
                </div>
                <div class="feature-card reveal" style="transition-delay: 100ms;">
                    <div class="feature-icon"><i class="fa-solid fa-water"></i></div>
                    <h3 class="text-lg font-bold text-white mb-2">Minerales Marinos</h3>
                    <p class="text-sm text-white/60 leading-relaxed">Equilibran el pH de la piel y reducen la inflamación activa. Ideales para la piel masculina más gruesa.</p>
                </div>
                <div class="feature-card reveal" style="transition-delay: 200ms;">
                    <div class="feature-icon"><i class="fa-solid fa-shield-virus"></i></div>
                    <h3 class="text-lg font-bold text-white mb-2">Sin Sulfatos Agresivos</h3>
                    <p class="text-sm text-white/60 leading-relaxed">No usamos detergentes fuertes que resecan. Limpiamos con tensioactivos suaves de alta calidad.</p>
                </div>
                <div class="feature-card reveal" style="transition-delay: 300ms;">
                    <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
                    <h3 class="text-lg font-bold text-white mb-2">Fragancia 8H</h3>
                    <p class="text-sm text-white/60 leading-relaxed">Tecnología de fragancia encapsulada. El olor masculino, fresco y elegante se libera lentamente todo el día.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIOS --}}
    <section class="relative py-24 px-6 overflow-hidden" style="background:rgba(56,189,248,0.03)">
        <div class="absolute inset-0 grid-lab opacity-20 pointer-events-none"></div>
        <div class="relative max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="eyebrow reveal"><span class="dot"></span>Prueba Social</span>
                <h2 class="reveal display mt-6 text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Ellos ya cambiaron su <span class="text-gradient">rutina</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass p-6 sm:p-8 rounded-3xl reveal">
                    <div class="flex text-sky-400 mb-4 text-xs gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 leading-relaxed text-sm mb-6">
                        "Siempre sufrí de granos en la espalda por hacer ejercicio. Probé jabones de farmacia carísimos y nada. NOIL no solo me los quitó en 2 semanas, sino que huelo cabrón todo el día. Recomendado al 100%."
                    </p>
                    <p class="text-sm font-bold text-white">— Mateo V. <span class="text-white/40 font-normal ml-1">· Medellín</span></p>
                </div>
                <div class="glass p-6 sm:p-8 rounded-3xl reveal" style="transition-delay: 100ms;">
                    <div class="flex text-sky-400 mb-4 text-xs gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 leading-relaxed text-sm mb-6">
                        "Mi novia fue la que me notó el cambio de olor. Pensó que había comprado un perfume nuevo. El efecto en la piel se siente desde la primera ducha, ya no me pica la espalda."
                    </p>
                    <p class="text-sm font-bold text-white">— Alejandro R. <span class="text-white/40 font-normal ml-1">· Bogotá</span></p>
                </div>
                <div class="glass p-6 sm:p-8 rounded-3xl reveal" style="transition-delay: 200ms;">
                    <div class="flex text-sky-400 mb-4 text-xs gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 leading-relaxed text-sm mb-6">
                        "Es otro nivel. El empaque se ve brutal en mi baño, la textura es increíble y de verdad rinde mucho. Solo necesitas un poco para que haga bastante espuma. Ya pedí 2 más."
                    </p>
                    <p class="text-sm font-bold text-white">— Carlos M. <span class="text-white/40 font-normal ml-1">· Cali</span></p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="bg-noil relative py-24 px-6">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-14">
                <span class="eyebrow reveal"><span class="dot"></span>Preguntas Frecuentes</span>
                <h2 class="reveal display mt-6 text-3xl font-extrabold tracking-tight">
                    ¿Tienes dudas? <span class="text-gradient">Aquí te las resolvemos</span>
                </h2>
            </div>
            <div class="space-y-4">
                <details class="faq-item glass rounded-2xl reveal">
                    <summary class="flex items-center justify-between p-6 select-none">
                        <h3 class="font-bold text-white pr-4">¿Sirve para todo tipo de piel?</h3>
                        <div class="faq-icon text-sky-400 w-6 h-6 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-sm text-white/60 leading-relaxed">
                        Sí, está formulado especialmente para piel masculina, controlando el exceso de sebo, pero sus agentes limpiadores suaves y sin sulfatos agresivos lo hacen apto incluso para pieles sensibles.
                    </div>
                </details>
                <details class="faq-item glass rounded-2xl reveal">
                    <summary class="flex items-center justify-between p-6 select-none">
                        <h3 class="font-bold text-white pr-4">¿En cuánto tiempo veo resultados en los granitos?</h3>
                        <div class="faq-icon text-sky-400 w-6 h-6 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-sm text-white/60 leading-relaxed">
                        Con el uso diario constante, la mayoría de nuestros clientes notan una reducción significativa en la inflamación y aparición de granitos nuevos entre la primera y segunda semana.
                    </div>
                </details>
                <details class="faq-item glass rounded-2xl reveal">
                    <summary class="flex items-center justify-between p-6 select-none">
                        <h3 class="font-bold text-white pr-4">¿A qué huele exactamente?</h3>
                        <div class="faq-icon text-sky-400 w-6 h-6 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-sm text-white/60 leading-relaxed">
                        Tiene un perfil aromático fresco, maderoso y elegante. Combina notas marinas limpias con un fondo ligeramente intenso. Huele a perfume masculino premium recién aplicado, y la fragancia se encapsula para durar horas.
                    </div>
                </details>
                <details class="faq-item glass rounded-2xl reveal">
                    <summary class="flex items-center justify-between p-6 select-none">
                        <h3 class="font-bold text-white pr-4">¿Cómo funciona el pago contra entrega?</h3>
                        <div class="faq-icon text-sky-400 w-6 h-6 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-sm text-white/60 leading-relaxed">
                        Es muy fácil y seguro: llenas el formulario aquí en la página, nosotros te contactamos por WhatsApp para confirmar tus datos, enviamos el paquete, y tú le pagas en efectivo o transferencia al mensajero cuando te entregue el producto en las manos.
                    </div>
                </details>
            </div>
        </div>
    </section>

    {{-- CTA URGENCIA --}}
    <section class="relative py-16 px-6" style="background:rgba(2,132,199,0.1)">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-10">
            <div class="w-full md:w-1/2 reveal-scale">
                <div class="vertical-card" style="aspect-ratio:4/5;max-width:320px;">
                    <img src="{{ asset('images_landings/noil/noilbodywash-urgency.webp') }}" alt="NOIL Body Wash Urgencia" width="320" height="400" loading="lazy" class="w-full h-full object-cover block">
                </div>
            </div>
            <div class="w-full md:w-1/2 text-center md:text-left reveal">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-4 border border-rose-500/30">
                    <i class="fa-solid fa-fire animate-pulse"></i> Alta demanda
                </span>
                <h2 class="display text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">
                    Últimas unidades del <span class="text-gradient">primer lote</span>
                </h2>
                <p class="text-white/60 leading-relaxed mb-8">
                    NOIL se está agotando más rápido de lo esperado. Asegura el tuyo antes de que entremos en lista de espera y transforma tu rutina de ducha hoy mismo.
                </p>
                <a href="#comprar" class="btn-noil w-full sm:w-auto">
                    Asegurar mi unidad ahora <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- FORMULARIO --}}
    <section id="comprar" class="bg-noil relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-25 pointer-events-none"></div>

        <div class="relative max-w-xl mx-auto reveal">
            <div class="glass-strong rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-400/15 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-sky-300/10 blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <div class="text-center mb-2">
                        <span class="eyebrow"><span class="dot"></span> Empieza hoy</span>
                    </div>
                    <h2 class="display text-center text-3xl sm:text-4xl font-extrabold tracking-tight mt-4">
                        Tu pedido en <span class="text-gradient">2 minutos</span>
                    </h2>
                    <p class="mt-2 text-center text-white/50 text-sm">
                        Pago contra entrega. Te llamamos para confirmar antes de despachar.
                    </p>

                    @if(session('success'))
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 text-sm">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-300 text-sm">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                            <div class="space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('landing.order', ['slug' => $landing->slug ?? 'noil']) }}" method="POST" class="mt-8 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nombre completo</label>
                                <input name="full_name" type="text" required class="form-input"
                                       value="{{ old('full_name') }}" placeholder="Carlos Rodríguez">
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
                                      placeholder="Dejar en portería, rejas blancas…">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Resumen de precio --}}
                        <div class="mt-4 rounded-2xl border border-sky-400/20 bg-sky-900/10 p-5 backdrop-blur-md">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-white/60">Precio por unidad</span>
                                <span class="font-semibold text-white">
                                    $<span data-price-unit-display>{{ isset($product) ? number_format((float) $product->price, 0, ',', '.') : '0' }}</span>
                                    <span class="text-[11px] text-white/40 ml-0.5">COP</span>
                                </span>
                            </div>
                            <div class="mt-1.5 flex items-center justify-between text-sm">
                                <span class="text-white/60">Cantidad</span>
                                <span class="font-semibold text-white" data-qty-display>{{ (int) old('quantity', 1) }}</span>
                            </div>

                            <div class="my-3 h-px bg-gradient-to-r from-transparent via-sky-400/30 to-transparent"></div>

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/50">Total a pagar</span>
                                <span class="text-2xl font-black text-sky-400 transition-all" data-total-wrapper>
                                    $<span data-total-display>{{ isset($product) ? number_format((float) $product->price * (int) old('quantity', 1), 0, ',', '.') : '0' }}</span>
                                    <span class="text-xs text-sky-500/70 font-semibold ml-0.5">COP</span>
                                </span>
                            </div>
                            <p class="mt-2 text-[10px] text-white/40 text-center">
                                <i class="fa-solid fa-truck-fast text-sky-400/70 mr-1"></i>
                                Envío incluido · Pagas al recibir
                            </p>
                        </div>

                        <button type="submit" class="btn-noil w-full mt-6">
                            Confirmar mi pedido
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <p class="text-center text-[11px] text-white/30 mt-4">
                            <i class="fa-solid fa-lock text-sky-400/50 mr-1"></i>
                            Pago contra entrega · Tus datos están encriptados
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-black py-10 px-6 text-center text-[11px] text-white/30 border-t border-sky-400/10">
        <p class="text-sky-400 text-sm font-bold mb-2">NOIL <span class="text-white/50">Body Wash</span></p>
        <p>© {{ date('Y') }} GlowYa · Todos los derechos reservados.</p>
        <p class="mt-1 text-white/20">Desarrollado para la piel del hombre.</p>
    </footer>

    {{-- FLOATING CTA --}}
    <a href="#comprar" class="floating-cta btn-noil" id="floatingCta" aria-label="Pedir NOIL Body Wash">
        <i class="fa-solid fa-droplet"></i>
        <span>Pedir ahora</span>
    </a>

    <script>
    window.NOIL_PRICE_PER_UNIT = {{ isset($product) ? (float) $product->price : 0 }};
    </script>
    <script>
    (() => {
        document.addEventListener('DOMContentLoaded', () => {
            const PRICE = Number(window.NOIL_PRICE_PER_UNIT) || 0;
            const formatCOP = (n) => Math.round(n).toLocaleString('es-CO', { maximumFractionDigits: 0 });

            const qtyInput   = document.querySelector('input[name="quantity"]');
            const qtyDisplay = document.querySelector('[data-qty-display]');
            const totalEl    = document.querySelector('[data-total-display]');
            const totalWrap  = document.querySelector('[data-total-wrapper]');

            const updateTotal = () => {
                const raw = parseInt(qtyInput?.value, 10);
                const qty = Math.min(5, Math.max(1, Number.isFinite(raw) ? raw : 1));
                if (qtyDisplay) qtyDisplay.textContent = String(qty);
                if (totalEl)    totalEl.textContent    = formatCOP(qty * PRICE);
                if (totalWrap) {
                    totalWrap.classList.remove('scale-105');
                    void totalWrap.offsetWidth;
                    totalWrap.classList.add('scale-105');
                    setTimeout(() => totalWrap.classList.remove('scale-105'), 180);
                }
            };

            if (qtyInput) {
                qtyInput.addEventListener('input', updateTotal);
                qtyInput.addEventListener('change', updateTotal);
                updateTotal();
            }

            // Reveal on scroll
            const reveals = document.querySelectorAll('.reveal, .reveal-scale');
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
            reveals.forEach(r => io.observe(r));

            // Counters
            const counterIo = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (!e.isIntersecting) return;
                    const el = e.target;
                    const target = parseInt(el.dataset.target, 10);
                    if (!Number.isFinite(target)) return;
                    counterIo.unobserve(el);
                    const duration = 1400;
                    const start = performance.now();
                    const tick = (now) => {
                        const p = Math.min(1, (now - start) / duration);
                        const eased = 1 - Math.pow(1 - p, 3);
                        el.textContent = Math.round(target * eased).toLocaleString('es-CO');
                        if (p < 1) requestAnimationFrame(tick);
                    };
                    requestAnimationFrame(tick);
                });
            }, { threshold: 0.4 });
            document.querySelectorAll('[data-counter]').forEach(c => counterIo.observe(c));

            // Floating CTA
            const floatingCta = document.getElementById('floatingCta');
            const heroSection = document.querySelector('.bg-noil');
            const formSection = document.getElementById('comprar');
            if (floatingCta && heroSection) {
                let overForm = false;
                
                const onScroll = () => {
                    const scrolled = window.scrollY > heroSection.offsetHeight * 0.6;
                    if (scrolled && !overForm) floatingCta.classList.add('visible');
                    else floatingCta.classList.remove('visible');
                };

                if (formSection) {
                    const formObserver = new IntersectionObserver((entries) => {
                        overForm = entries[0].isIntersecting;
                        onScroll();
                    }, { threshold: 0 });
                    formObserver.observe(formSection);
                }

                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll();
            }
            
            // Smooth scroll
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
