<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr Melaxin TX Cream — Tratamiento despigmentante con respaldo dermatológico</title>
    <meta name="description" content="Aclara manchas oscuras, melasma y paño en piel facial con Dr Melaxin TX. Fórmula con activos clínicamente probados. Resultados visibles desde la 4ª semana.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --lab-blue-50:  #f0f9ff;
            --lab-blue-100: #e0f2fe;
            --lab-blue-200: #bae6fd;
            --lab-blue-300: #7dd3fc;
            --lab-blue-400: #38bdf8;
            --lab-blue-500: #0ea5e9;
            --lab-blue-600: #0284c7;
            --lab-blue-700: #0369a1;
            --lab-blue-900: #0c4a6e;
            --slate-text:   #0f172a;
            --slate-soft:   #475569;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--slate-text);
            background: #ffffff;
            overflow-x: hidden;
        }
        h1, h2, h3, .display { font-family: 'Playfair Display', Georgia, serif; }

        /* ----- Lab background layers ----- */
        .bg-lab {
            background:
                radial-gradient(ellipse 90% 50% at 50% 0%, rgba(14, 165, 233, 0.10), transparent 70%),
                radial-gradient(ellipse 60% 50% at 100% 50%, rgba(2, 132, 199, 0.08), transparent 70%),
                radial-gradient(ellipse 60% 50% at 0% 100%, rgba(125, 211, 252, 0.10), transparent 70%),
                #ffffff;
        }
        .bg-lab-strong {
            background:
                radial-gradient(ellipse 80% 70% at 50% 0%, rgba(56, 189, 248, 0.18), transparent 70%),
                linear-gradient(180deg, var(--lab-blue-50) 0%, #ffffff 60%);
        }
        .grid-lab {
            background-image:
                linear-gradient(rgba(14, 165, 233, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14, 165, 233, 0.06) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* ----- Floating particles ----- */
        @keyframes float-up {
            0%   { transform: translateY(20px); opacity: 0; }
            10%  { opacity: 0.7; }
            90%  { opacity: 0.7; }
            100% { transform: translateY(-100vh); opacity: 0; }
        }
        .lab-particle {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--lab-blue-400);
            border-radius: 50%;
            box-shadow: 0 0 12px 2px rgba(56, 189, 248, 0.55);
            animation: float-up linear infinite;
        }

        /* ----- Reveal on scroll ----- */
        .reveal { opacity: 0; transform: translateY(36px); transition: opacity 1s cubic-bezier(.2,.8,.2,1), transform 1s cubic-bezier(.2,.8,.2,1); }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .reveal-scale { opacity: 0; transform: scale(.92); transition: opacity 1s cubic-bezier(.2,.8,.2,1), transform 1s cubic-bezier(.2,.8,.2,1); }
        .reveal-scale.in { opacity: 1; transform: scale(1); }

        /* ----- Glass cards ----- */
        .glass-lab {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(14, 165, 233, 0.18);
            box-shadow:
                0 20px 60px -20px rgba(2, 132, 199, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        .glass-lab-strong {
            background: linear-gradient(135deg, rgba(240, 249, 255, 0.95), rgba(255, 255, 255, 0.92));
            backdrop-filter: blur(18px);
            border: 1px solid rgba(14, 165, 233, 0.25);
            box-shadow:
                0 30px 80px -25px rgba(2, 132, 199, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        /* ----- Buttons ----- */
        .btn-lab {
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
            background: linear-gradient(135deg, var(--lab-blue-400) 0%, var(--lab-blue-500) 50%, var(--lab-blue-700) 100%);
            color: #ffffff;
            box-shadow:
                0 14px 40px -10px rgba(2, 132, 199, 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            transition: transform .25s ease, box-shadow .25s ease;
            overflow: hidden;
        }
        .btn-lab::before {
            content: '';
            position: absolute;
            top: 0; left: -120%;
            width: 60%; height: 100%;
            background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.45) 50%, transparent 100%);
            transition: left .8s cubic-bezier(.2,.8,.2,1);
        }
        .btn-lab:hover { transform: translateY(-3px); box-shadow: 0 22px 55px -12px rgba(2, 132, 199, 0.75), inset 0 1px 0 rgba(255, 255, 255, 0.5); }
        .btn-lab:hover::before { left: 130%; }
        .btn-lab:active { transform: translateY(-1px); }

        .btn-ghost-lab {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .85rem 1.6rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: .9rem;
            color: var(--lab-blue-700);
            background: rgba(224, 242, 254, 0.8);
            border: 1px solid rgba(14, 165, 233, 0.3);
            transition: all .25s ease;
        }
        .btn-ghost-lab:hover { background: rgba(186, 230, 253, 0.95); transform: translateY(-2px); }

        /* ----- Floating CTA (lives at bottom of viewport) ----- */
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.55), 0 18px 40px -10px rgba(2, 132, 199, 0.55); }
            70%  { box-shadow: 0 0 0 18px rgba(14, 165, 233, 0), 0 22px 55px -12px rgba(2, 132, 199, 0.65); }
            100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0), 0 18px 40px -10px rgba(2, 132, 199, 0.55); }
        }
        @keyframes wiggle-cta {
            0%, 100% { transform: rotate(0deg) translateY(0); }
            25%      { transform: rotate(-2deg) translateY(-2px); }
            75%      { transform: rotate(2deg) translateY(-2px); }
        }
        .floating-cta {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            z-index: 60;
            animation: pulse-ring 2.4s ease-out infinite, wiggle-cta 5s ease-in-out infinite;
            opacity: 0;
            transform: translateY(20px) scale(.9);
            transition: opacity .4s ease, transform .4s cubic-bezier(.2,.8,.2,1);
            pointer-events: none;
        }
        .floating-cta.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .floating-cta:hover { animation-play-state: paused; transform: translateY(-4px) scale(1.04) !important; }

        /* ----- Section labels ----- */
        .lab-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem .9rem;
            border-radius: 999px;
            background: rgba(224, 242, 254, 0.85);
            border: 1px solid rgba(14, 165, 233, 0.3);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--lab-blue-700);
        }
        .lab-eyebrow .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--lab-blue-500);
            box-shadow: 0 0 10px var(--lab-blue-400);
            animation: pulse-dot 1.6s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: .6; }
            50%      { opacity: 1; }
        }

        /* ----- Headline gradient ----- */
        .text-gradient-lab {
            background: linear-gradient(135deg, var(--lab-blue-700) 0%, var(--lab-blue-500) 60%, #06b6d4 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* ----- Pain/Solution image card ----- */
        .vertical-card {
            position: relative;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            aspect-ratio: 9 / 16;
            border-radius: 32px;
            overflow: hidden;
            box-shadow:
                0 40px 100px -30px rgba(2, 132, 199, 0.35),
                0 0 0 1px rgba(14, 165, 233, 0.18);
            background: #fff;
        }
        .vertical-card img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .vc-overlay-bottom {
            position: absolute;
            inset: auto 0 0 0;
            padding: 2rem 1.5rem 1.5rem;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.86), transparent);
            color: #fff;
        }
        .vc-tag {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: .65rem;
        }
        .vc-tag-pain     { background: rgb(243, 125, 125);  color: #ffffff; border: 1px solid rgba(244, 63, 94, 0.35); }
        .vc-tag-solution { background: rgba(14, 165, 233, 0.22); color: #000000; border: 1px solid rgba(14, 165, 233, 0.45); }

        /* ----- Feature cards ----- */
        .feature-card {
            position: relative;
            padding: 2rem 1.6rem;
            border-radius: 24px;
            background: #ffffff;
            border: 1px solid rgba(14, 165, 233, 0.15);
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        }
        .feature-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0), rgba(56, 189, 248, .35), rgba(2, 132, 199, 0));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            opacity: 0;
            transition: opacity .3s ease;
            pointer-events: none;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(14, 165, 233, 0.3);
            box-shadow: 0 30px 70px -20px rgba(2, 132, 199, 0.25);
        }
        .feature-card:hover::after { opacity: 1; }

        .feature-icon {
            display: inline-flex;
            width: 56px; height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--lab-blue-100), var(--lab-blue-200));
            color: var(--lab-blue-700);
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 1rem;
            box-shadow: inset 0 1px 0 #fff;
        }

        /* ----- Stat counter ----- */
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--lab-blue-600), var(--lab-blue-400));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* ----- Form ----- */
        .form-input-lab {
            width: 100%;
            padding: .9rem 1rem;
            border-radius: .85rem;
            background: rgba(240, 249, 255, 0.55);
            border: 1px solid rgba(14, 165, 233, 0.22);
            color: var(--slate-text);
            font-size: .95rem;
            transition: all .2s;
        }
        .form-input-lab:focus {
            outline: none;
            border-color: var(--lab-blue-500);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.14);
        }
        .form-input-lab::placeholder { color: rgba(15, 23, 42, 0.32); }
        .form-label-lab {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--lab-blue-700);
            margin-bottom: .5rem;
            display: block;
        }
        select.form-input-lab {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230284c7'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
            background-size: 1.1rem;
            appearance: none;
            padding-right: 2.5rem;
        }
        select.form-input-lab option { background: #fff; color: var(--slate-text); }

        /* ----- Scroll-driven video section ----- */
        .scroll-video-hero {
            height: 500vh;
            position: relative;
            background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 50%, #ffffff 100%);
        }
        .scroll-video-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .scroll-video-stage {
            position: relative;
            width: clamp(280px, 40vw, 460px);
            aspect-ratio: 9/16;
            border-radius: 36px;
            overflow: hidden;
            box-shadow:
                0 60px 130px -30px rgba(2, 132, 199, 0.5),
                0 0 0 1px rgba(14, 165, 233, 0.25),
                inset 0 1px 0 rgba(255,255,255,.4);
        }
        .scroll-video-stage video {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            background: #0c4a6e;
        }
        .scroll-video-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.4rem 1.4rem 1.6rem;
            color: #fff;
            background:
                linear-gradient(180deg, rgba(15, 23, 42, 0.45) 0%, transparent 30%, transparent 60%, rgba(15, 23, 42, 0.55) 100%);
            pointer-events: none;
        }
        .scroll-video-side {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            max-width: 320px;
            color: var(--slate-text);
        }
        .scroll-video-side.left  { left: 6%; }
        .scroll-video-side.right { right: 6%; text-align: right; }
        @media (max-width: 1024px) {
            .scroll-video-side { display: none; }
        }
        .scroll-progress-bar {
            position: absolute;
            left: 8%; right: 8%;
            bottom: 1rem;
            height: 3px;
            background: rgba(15, 23, 42, 0.12);
            border-radius: 999px;
            overflow: hidden;
            z-index: 3;
        }
        .scroll-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--lab-blue-400), var(--lab-blue-600));
            transition: width 0.05s linear;
        }

        @media (max-width: 768px) {
            .scroll-video-hero { height: 100vh; }
            .scroll-video-sticky { position: relative; }
            .scroll-video-stage { width: 78%; }
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-video-hero { height: 100vh; }
            .scroll-video-sticky { position: relative; }
        }

        /* ----- Marquee de confianza ----- */
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .marquee {
            display: flex;
            gap: 3rem;
            width: max-content;
            animation: marquee 30s linear infinite;
        }
        .marquee-item {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--lab-blue-700);
            opacity: .7;
        }

        /* ----- Star rating ----- */
        .stars { display: inline-flex; gap: 2px; color: #f59e0b; }

        /* ----- Sticky bg layers helper ----- */
        .relative-stage { position: relative; isolation: isolate; }
    </style>
</head>
<body class="bg-white antialiased">

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  NAV FIJA                                            ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-white/75 border-b border-sky-100">
        <div class="max-w-6xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2 text-base font-black tracking-tight">
                <span class="inline-flex w-7 h-7 rounded-lg items-center justify-center bg-gradient-to-br from-sky-400 to-sky-700 text-white text-xs">
                    <i class="fa-solid fa-flask"></i>
                </span>
                <span class="text-slate-900">Dr Melaxin</span><span class="text-sky-600">.</span>
                <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-[0.18em] text-sky-600 ml-1">TX Cream</span>
            </a>
            <a href="#comprar" class="px-5 py-2 rounded-full text-xs font-bold bg-sky-600 text-white hover:bg-sky-700 transition shadow-md shadow-sky-500/30">
                Pedir ahora
            </a>
        </div>
    </nav>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  HERO clínico — vertical, centrado                   ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="bg-lab-strong relative pt-32 pb-20 px-6 min-h-screen flex flex-col items-center justify-center text-center overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-50 pointer-events-none"></div>
        <div id="hero-particles" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

        <div class="relative max-w-3xl">
            {{-- Eyebrow lab badge --}}
            <span class="lab-eyebrow">
                <span class="dot"></span>
                Fórmula respaldada por dermatología
            </span>

            <h1 class="display mt-7 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-[1.05] tracking-tight text-slate-900">
                Borra las manchas que
                <span class="block text-gradient-lab">la luz reveló en tu piel</span>
            </h1>

            <p class="mt-7 text-base md:text-lg text-slate-600 font-light max-w-2xl mx-auto leading-relaxed">
                <strong class="font-semibold text-slate-900">Dr Melaxin TX Cream</strong> aclara melasma, paño y manchas oscuras del rostro con activos clínicamente probados.
                Resultados visibles desde la <strong class="font-semibold text-sky-700">4ª semana</strong> — sin irritación, sin rebote.
            </p>

            {{-- Trust mini-row --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs font-semibold text-slate-500">
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-microscope text-sky-600"></i> Tranexámico + Niacinamida</span>
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-leaf text-sky-600"></i> Sin parabenos</span>
                <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-sky-600"></i> Apto piel sensible</span>
            </div>

            {{-- CTA principal --}}
            <div class="mt-10 flex flex-col items-center gap-3">
                <a href="#comprar" class="btn-lab">
                    Quiero borrar mis manchas
                    <i class="fa-solid fa-arrow-down text-xs"></i>
                </a>
                <p class="text-[11px] text-slate-500 font-medium">
                    <i class="fa-solid fa-truck-fast text-sky-600/80 mr-1"></i> Pago contra entrega ·
                    <i class="fa-solid fa-shield-halved text-sky-600/80 ml-1 mr-1"></i> Envío 24-72 hrs
                </p>
            </div>

            {{-- Stats row --}}
            <div class="mt-14 grid grid-cols-3 gap-4 max-w-2xl mx-auto">
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="92">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">% nota mejoría<br>en 8 semanas</p>
                </div>
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="14">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">activos<br>despigmentantes</p>
                </div>
                <div class="reveal">
                    <div class="stat-num" data-counter data-target="2400">0</div>
                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">pieles tratadas<br>en Colombia</p>
                </div>
            </div>
        </div>

        {{-- Scroll hint --}}
        <a href="#dolor" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-sky-600 hover:text-sky-700 transition" aria-label="Bajar">
            <i class="fa-solid fa-chevron-down text-2xl animate-bounce"></i>
        </a>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  Marquee de confianza                                ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="relative bg-white py-8 border-y border-sky-100 overflow-hidden">
        <div class="marquee">
            @php $items = ['Activos clínicos', 'Sin hidroquinona', 'pH balanceado', 'Test dermatológico', 'Cruelty free', 'Producción Colombia', 'Activos clínicos', 'Sin hidroquinona', 'pH balanceado', 'Test dermatológico', 'Cruelty free', 'Producción Colombia']; @endphp
            @foreach($items as $item)
                <span class="marquee-item">
                    <i class="fa-solid fa-circle text-[6px] text-sky-400"></i>
                    {{ $item }}
                </span>
            @endforeach
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  DOLOR — imagen vertical + copy emocional            ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section id="dolor" class="bg-lab relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-30 pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto text-center mb-14">
            <span class="lab-eyebrow reveal" style="background: rgba(254, 226, 226, 0.7); border-color: rgba(244, 63, 94, .25); color: #be123c;">
                <span class="dot" style="background: #f43f5e; box-shadow: 0 0 10px #fb7185;"></span>
                El problema real
            </span>
            <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Cuando el espejo ya no <br class="hidden sm:block">refleja a la mujer que eres
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Las manchas no llegaron solas — llegaron con un peso emocional. La sensación de ocultarte detrás del maquillaje, evitar fotos sin filtro, escuchar "se te ve cansada" cuando sabes que dormiste bien.
            </p>
        </div>

        <div class="reveal-scale max-w-lg mx-auto">
            <div class="vertical-card">
                <img src="{{ asset('images_landings/dr-melaxin/drmelaxin-pain.png') }}" alt="Mujer mirándose al espejo con manchas en el rostro">
            </div>

            {{-- Pain bullet list --}}
            <ul class="mt-10 space-y-3 max-w-md mx-auto text-slate-700">
                <span class="vc-tag vc-tag-pain">
                    <i class="fa-solid fa-heart-crack text-[9px]"></i> El dolor invisible
                </span>

                <p class="text-base sm:text-lg font-semibold leading-snug">
                    "Llevaba 3 años cubriéndolas con base. Cada mañana era un recordatorio."
                </p>

                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-100 text-rose-600 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Cremas que prometen y solo te resecan o te irritan más.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-100 text-rose-600 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Tratamientos láser de $1.200.000 sin garantía y con efecto rebote.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-100 text-rose-600 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Hidroquinona que aclara hoy y oscurece más mañana (ocronosis).</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-rose-100 text-rose-600 items-center justify-center text-[10px]"><i class="fa-solid fa-xmark"></i></span>
                    <p>Salir sin maquillaje y sentir que todos miran <em>esa zona</em>.</p>
                </li>
            </ul>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  SOLUCIÓN — imagen vertical + copy aspiracional      ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="bg-lab-strong relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-30 pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto text-center mb-14">
            <span class="lab-eyebrow reveal">
                <span class="dot"></span>
                La solución clínica
            </span>
            <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Una fórmula que <span class="text-gradient-lab">trabaja por capas</span>
                <br class="hidden sm:block">mientras tú duermes
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Dr Melaxin TX combina <strong>ácido tranexámico, niacinamida, alpha-arbutin y vitamina C estabilizada</strong> — los 4 activos que la dermatología moderna usa para inhibir la producción de melanina sin agredir la barrera cutánea.
            </p>
        </div>

        <div class="reveal-scale max-w-lg mx-auto">
            <div class="vertical-card">
                <img src="{{ asset('images_landings/dr-melaxin/drmelaxin-solution.jpg') }}" alt="Mujer con piel uniforme tras tratamiento despigmentante">
            </div>

            {{-- Solution bullet list --}}
            <ul class="mt-10 space-y-3 max-w-md mx-auto text-slate-700">
                    <span class="vc-tag vc-tag-solution">
                        <i class="fa-solid fa-sparkles text-[9px]"></i> El reflejo que recuperas
                    </span>
                    <p class="text-base sm:text-lg font-semibold leading-snug">
                        "Por primera vez en años, salgo sin base y mi piel se ve uniforme."
                    </p>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-100 text-sky-700 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Despigmenta en superficie <strong>y</strong> en capas profundas (no solo aclara, corrige).</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-100 text-sky-700 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Refuerza la barrera cutánea — no irrita, no reseca, no descama.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-100 text-sky-700 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Compatible con tratamiento diurno (FPS 50+ recomendado) y nocturno.</p>
                </li>
                <li class="reveal flex gap-3 items-start">
                    <span class="mt-1 inline-flex w-5 h-5 rounded-full bg-sky-100 text-sky-700 items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                    <p>Resultados visibles en 4 semanas, transformación en 8.</p>
                </li>
            </ul>
        </div>

        <div class="reveal mt-14 text-center">
            <a href="#comprar" class="btn-lab">
                Comenzar mi tratamiento
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  FEATURES — lo que otras marcas no tienen            ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="bg-white relative py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <span class="lab-eyebrow reveal">
                    <span class="dot"></span>
                    Por qué Dr Melaxin TX y no otra
                </span>
                <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                    Lo que <span class="text-gradient-lab">otras cremas no te dicen</span>
                </h2>
                <p class="reveal mt-4 text-slate-600 max-w-2xl mx-auto">
                    El 80% de las despigmentantes del mercado usan un solo activo. La pigmentación es multi-capa — necesitas atacarla por todos los frentes.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-flask-vial"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">4 activos sinérgicos</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Tranexámico inhibe, niacinamida transporta, alpha-arbutin neutraliza, vitamina C estabilizada repara. Las marcas comunes solo usan uno.</p>
                </div>
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-shield-virus"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Cero hidroquinona</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Sin el activo prohibido en Europa que produce ocronosis (manchas grises permanentes) tras uso prolongado. Resultado real, sin daño futuro.</p>
                </div>
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-droplet"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Textura aireada</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">No grasa, no pegajosa, no irritante. Penetra en segundos. Puedes maquillarte encima sin que se "rasque" la piel.</p>
                </div>
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-clock"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Acción 24h</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Liberación gradual durante el día. Trabaja mientras maquillas, mientras duermes y mientras te expones al sol.</p>
                </div>
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-microscope"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Test dermatológico</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Probado en pieles tipo III-V (las más propensas a melasma en Latinoamérica). pH equilibrado a 5.5.</p>
                </div>
                <div class="feature-card reveal">
                    <span class="feature-icon"><i class="fa-solid fa-rotate"></i></span>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Sin efecto rebote</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Cuando termines el tratamiento las manchas no regresan más oscuras. La fórmula corrige el origen — no enmascara.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  PROBLEMA EMOCIONAL — texto vertical                 ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="bg-lab relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-20 pointer-events-none"></div>

        <div class="relative max-w-3xl mx-auto text-center">
            <span class="lab-eyebrow reveal">
                <span class="dot"></span>
                No es vanidad — es identidad
            </span>
            <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Las manchas no son <span class="text-gradient-lab">solo piel</span>
            </h2>

            <div class="reveal mt-10 space-y-6 text-base sm:text-lg text-slate-700 leading-relaxed">
                <p>
                    Cuando una mujer mira el espejo y ve las manchas antes que su sonrisa, algo se rompe por dentro. Empiezas a evitar la luz natural en las fotos. Eliges los filtros más agresivos. Te sientas en lados específicos de la mesa para que la sombra "te ayude".
                </p>
                <p>
                    Y la peor parte: <strong class="text-slate-900">empiezas a creer que así es tu cara real ahora</strong>. Que esa mujer del espejo es la nueva. Que tu juventud se quedó en otra década.
                </p>
                <p class="text-sky-700 font-semibold">
                    No tiene que ser así.
                </p>
                <p>
                    Dr Melaxin TX no te promete una piel de 20 años — te devuelve <strong class="text-slate-900">tu piel</strong>. La que tenías antes de que el sol, las hormonas o el estrés hicieran lo suyo. Porque cuando tu reflejo coincide con cómo te sientes por dentro, todo cambia: cómo caminas, cómo te paras frente a alguien, cómo te tomas una foto sin pensar.
                </p>
            </div>

            <div class="reveal mt-12">
                <a href="#comprar" class="btn-lab">
                    Recuperar mi reflejo
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  RESEÑAS                                             ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="bg-white relative py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="lab-eyebrow reveal">
                    <span class="dot"></span>
                    Reseñas verificadas
                </span>
                <h2 class="reveal display mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
                    +2.400 mujeres en Colombia
                    <span class="block text-gradient-lab">ya recuperaron su reflejo</span>
                </h2>
            </div>

            <div class="space-y-8">
                <div class="reveal flex justify-start">
                    <div class="w-full md:w-2/3 glass-lab rounded-3xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -left-3 text-xl text-sky-500 bg-white rounded-full p-2 border border-sky-200 shadow"></i>
                        <div class="stars text-sm mb-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-slate-700 leading-relaxed text-sm sm:text-base">
                            "El melasma del embarazo me destrozó la autoestima. Probé 3 tratamientos diferentes — uno me costó $900.000 y solo me ardía. A la quinta semana con Dr Melaxin la mancha del pómulo izquierdo se aclaró. A las 8 semanas casi no se nota. Por primera vez en 4 años salí sin base."
                        </p>
                        <p class="mt-4 text-sm font-bold text-sky-700">— Catalina V. <span class="text-slate-400 font-normal ml-1">· Bogotá · 34 años</span></p>
                    </div>
                </div>

                <div class="reveal flex justify-end">
                    <div class="w-full md:w-2/3 glass-lab rounded-3xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -right-3 text-xl text-sky-500 bg-white rounded-full p-2 border border-sky-200 shadow"></i>
                        <div class="stars text-sm mb-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-slate-700 leading-relaxed text-sm sm:text-base">
                            "Tengo piel mixta y todo me irrita. La crema se siente liviana, no me brota nada y desde la tercera semana noté que las manchas del bigotillo y la frente empezaron a aclararse parejo. No es magia, es constancia — pero funciona."
                        </p>
                        <p class="mt-4 text-sm font-bold text-sky-700">— Daniela P. <span class="text-slate-400 font-normal ml-1">· Medellín · 29 años</span></p>
                    </div>
                </div>

                <div class="reveal flex justify-start">
                    <div class="w-full md:w-2/3 glass-lab rounded-3xl p-6 md:p-8 relative">
                        <i class="fa-solid fa-quote-left absolute -top-3 -left-3 text-xl text-sky-500 bg-white rounded-full p-2 border border-sky-200 shadow"></i>
                        <div class="stars text-sm mb-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-slate-700 leading-relaxed text-sm sm:text-base">
                            "Soy enfermera, paso turnos largos y la luz del hospital me daña la piel. Tenía manchas oscuras a los lados de la cara que ningún serum me corrigió. Con Dr Melaxin TX en 6 semanas se uniformó el tono. Mis compañeras me preguntan qué me hago. Ya pedí 2 más."
                        </p>
                        <p class="mt-4 text-sm font-bold text-sky-700">— Marcela L. <span class="text-slate-400 font-normal ml-1">· Cali · 41 años</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  SCROLL-DRIVEN VIDEO — sección sticky con copys      ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section class="scroll-video-hero" id="scroll-hero">
        <div class="scroll-video-sticky">
            {{-- Side copy left --}}
            <div class="scroll-video-side left">
                <span class="lab-eyebrow">
                    <span class="dot"></span>
                    El producto en detalle
                </span>
                <h3 class="display mt-4 text-2xl xl:text-3xl font-extrabold leading-tight text-slate-900">
                    Examina el empaque
                    <span class="block text-gradient-lab">a tu ritmo</span>
                </h3>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                    Desliza para revelar cada ángulo. Sube para devolver. Como tener el producto en la mano antes de pedirlo.
                </p>
            </div>

            {{-- Stage --}}
            <div class="scroll-video-stage">
                <video id="heroVideo"
                       src="{{ asset('images_landings/dr-melaxin/drmelaxin-scroll.mp4') }}"
                       muted
                       playsinline
                       preload="auto"
                       disablepictureinpicture></video>

                <div class="scroll-video-overlay">
                    <div>
                        <span class="lab-eyebrow" style="background: rgba(255,255,255,.85);">
                            <span class="dot"></span>
                            Dr Melaxin TX
                        </span>
                    </div>
                    <div class="text-white">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] opacity-80">Desliza ↓ para explorar</p>
                    </div>
                </div>

                <div class="scroll-progress-bar">
                    <div class="scroll-progress-fill" id="progressFill"></div>
                </div>
            </div>

            {{-- Side copy right --}}
            <div class="scroll-video-side right">
                <span class="lab-eyebrow">
                    <span class="dot"></span>
                    Empaque clínico
                </span>
                <h3 class="display mt-4 text-2xl xl:text-3xl font-extrabold leading-tight text-slate-900">
                    Diseñado para
                    <span class="block text-gradient-lab">preservar el activo</span>
                </h3>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                    Tubo airless con dosificador. Cada presión libera la cantidad exacta sin contaminar la fórmula con oxígeno.
                </p>
            </div>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  FORMULARIO de pedido — replica patrón noil          ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <section id="comprar" class="bg-lab-strong relative py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 grid-lab opacity-25 pointer-events-none"></div>

        <div class="relative max-w-xl mx-auto reveal">
            <div class="glass-lab-strong rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-400/15 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-sky-300/20 blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <div class="text-center mb-2">
                        <span class="lab-eyebrow"><span class="dot"></span> Empieza hoy</span>
                    </div>
                    <h2 class="display text-center text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 mt-4">
                        Tu tratamiento en <span class="text-gradient-lab">2 minutos</span>
                    </h2>
                    <p class="mt-2 text-center text-slate-600 text-sm">
                        Pago contra entrega. Te llamamos para confirmar antes de despachar.
                    </p>

                    @if(session('success'))
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 text-sm">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 flex items-start gap-3 p-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 text-sm">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                            <div class="space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('landing.order', ['slug' => $landing->slug]) }}" method="POST" class="mt-8 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-lab">Nombre completo</label>
                                <input name="full_name" type="text" required class="form-input-lab"
                                       value="{{ old('full_name') }}" placeholder="Catalina Rodríguez">
                            </div>
                            <div>
                                <label class="form-label-lab">Cédula</label>
                                <input name="id_number" type="text" required class="form-input-lab"
                                       value="{{ old('id_number') }}" placeholder="1023456789">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-lab">Celular</label>
                                <input name="phone" type="tel" required class="form-input-lab"
                                       value="{{ old('phone') }}" placeholder="3001234567">
                            </div>
                            <div>
                                <label class="form-label-lab">Email</label>
                                <input name="email" type="email" required class="form-input-lab"
                                       value="{{ old('email') }}" placeholder="tu@email.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-lab">Departamento</label>
                                <select name="department" required class="form-input-lab">
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
                                <label class="form-label-lab">Ciudad</label>
                                <input name="city" type="text" required class="form-input-lab"
                                       value="{{ old('city') }}" placeholder="Bogotá">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-lab">Barrio (opcional)</label>
                                <input name="neighborhood" type="text" class="form-input-lab"
                                       value="{{ old('neighborhood') }}" placeholder="Chapinero">
                            </div>
                            <div>
                                <label class="form-label-lab">Cantidad</label>
                                <input name="quantity" type="number" min="1" max="5" required class="form-input-lab"
                                       value="{{ old('quantity', 1) }}">
                            </div>
                        </div>

                        <div>
                            <label class="form-label-lab">Dirección</label>
                            <input name="address" type="text" required class="form-input-lab"
                                   value="{{ old('address') }}" placeholder="Cra 7 # 32-15 apto 502">
                        </div>

                        <div>
                            <label class="form-label-lab">Notas para el courier (opcional)</label>
                            <textarea name="notes" rows="2" class="form-input-lab"
                                      placeholder="Tocar timbre 3 veces, edificio rejas blancas…">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Resumen de precio (live calc según cantidad) --}}
                        <div class="mt-2 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-sky-50/50 p-5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Precio por unidad</span>
                                <span class="font-semibold text-slate-800">
                                    $<span data-price-unit-display>{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                    <span class="text-[11px] text-slate-400 ml-0.5">COP</span>
                                </span>
                            </div>
                            <div class="mt-1.5 flex items-center justify-between text-sm">
                                <span class="text-slate-500">Cantidad</span>
                                <span class="font-semibold text-slate-800" data-qty-display>{{ (int) old('quantity', 1) }}</span>
                            </div>

                            <div class="my-3 h-px bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Total a pagar</span>
                                <span class="text-2xl font-black text-sky-700 transition-all" data-total-wrapper>
                                    $<span data-total-display>{{ number_format((float) $product->price * (int) old('quantity', 1), 0, ',', '.') }}</span>
                                    <span class="text-xs text-sky-500 font-semibold ml-0.5">COP</span>
                                </span>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-500 text-center">
                                <i class="fa-solid fa-truck-fast text-sky-500/70 mr-1"></i>
                                Envío incluido · Pagas al recibir
                            </p>
                        </div>

                        <button type="submit" class="btn-lab w-full mt-6">
                            Confirmar mi pedido
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <p class="text-center text-[11px] text-slate-500 mt-4">
                            <i class="fa-solid fa-lock text-sky-500/70 mr-1"></i>
                            Pago contra entrega · Tus datos están protegidos
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  FOOTER                                              ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <footer class="bg-slate-900 py-10 px-6 text-center text-[11px] text-slate-400">
        <p class="text-sky-400 text-sm font-bold mb-2">Dr Melaxin <span class="text-white/60">TX Cream</span></p>
        <p>© {{ date('Y') }} Glofit · Todos los derechos reservados.</p>
        <p class="mt-1 text-slate-500">El uso prolongado debe ir acompañado de protector solar FPS 50+ diario.</p>
    </footer>

    {{-- ╔══════════════════════════════════════════════════════╗
         ║  FLOATING CTA — fixed bottom-right, vivo             ║
         ╚══════════════════════════════════════════════════════╝ --}}
    <a href="#comprar" class="floating-cta btn-lab" id="floatingCta" aria-label="Pedir Dr Melaxin TX">
        <i class="fa-solid fa-flask"></i>
        <span>Pedir ahora</span>
    </a>

    <script>
    window.DRMELAXIN_PRICE_PER_UNIT = {{ (float) $product->price }};
    </script>
    <script>
    (() => {
        document.addEventListener('DOMContentLoaded', () => {
            // ╔══ Total dinámico ══╗
            const PRICE = Number(window.DRMELAXIN_PRICE_PER_UNIT) || 0;
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

            // ╔══ Reveal on scroll ══╗
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

            // ╔══ Counter (stats) ══╗
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

            // ╔══ Lab particles en hero ══╗
            const particleHost = document.getElementById('hero-particles');
            if (particleHost) {
                for (let i = 0; i < 18; i++) {
                    const p = document.createElement('span');
                    p.className = 'lab-particle';
                    p.style.left = Math.random() * 100 + '%';
                    p.style.bottom = '-20px';
                    p.style.animationDuration = (8 + Math.random() * 8) + 's';
                    p.style.animationDelay = (Math.random() * 8) + 's';
                    p.style.opacity = (0.35 + Math.random() * 0.5).toString();
                    p.style.transform = `scale(${0.6 + Math.random() * 1.4})`;
                    particleHost.appendChild(p);
                }
            }

            // ╔══ Smooth scroll para anchors ══╗
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

            // ╔══ Floating CTA — aparece después del hero ══╗
            const floatingCta = document.getElementById('floatingCta');
            const heroSection = document.querySelector('.bg-lab-strong');
            const formSection = document.getElementById('comprar');
            if (floatingCta && heroSection) {
                const onScroll = () => {
                    const scrolled = window.scrollY > heroSection.offsetHeight * 0.6;
                    const formRect = formSection?.getBoundingClientRect();
                    const overForm = formRect && formRect.top < window.innerHeight && formRect.bottom > 0;
                    if (scrolled && !overForm) floatingCta.classList.add('visible');
                    else floatingCta.classList.remove('visible');
                };
                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll();
            }

            // ╔══ Scroll-driven video (hero variante B) ══╗
            const video = document.getElementById('heroVideo');
            const scrollHero = document.getElementById('scroll-hero');
            const progressFill = document.getElementById('progressFill');
            if (video && scrollHero) {
                const isMobile = window.matchMedia('(max-width: 768px)').matches;
                const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                if (isMobile || prefersReduced) {
                    video.autoplay = true;
                    video.loop = true;
                    video.muted = true;
                    video.play().catch(() => {});
                } else {
                    video.pause();
                    video.currentTime = 0;
                    let isReady = false;
                    video.addEventListener('loadedmetadata', () => { isReady = true; });
                    video.load();

                    const updateVideo = () => {
                        if (!isReady || !video.duration) return;
                        const rect = scrollHero.getBoundingClientRect();
                        const scrolled = -rect.top;
                        const scrollable = scrollHero.offsetHeight - window.innerHeight;
                        if (scrolled <= 0) {
                            video.currentTime = 0;
                            if (progressFill) progressFill.style.width = '0%';
                            return;
                        }
                        if (scrolled >= scrollable) {
                            video.currentTime = video.duration;
                            if (progressFill) progressFill.style.width = '100%';
                            return;
                        }
                        const progress = scrolled / scrollable;
                        video.currentTime = progress * video.duration;
                        if (progressFill) progressFill.style.width = (progress * 100) + '%';
                    };

                    let ticking = false;
                    window.addEventListener('scroll', () => {
                        if (!ticking) {
                            requestAnimationFrame(() => { updateVideo(); ticking = false; });
                            ticking = true;
                        }
                    }, { passive: true });
                    window.addEventListener('resize', updateVideo);
                }
            }
        });
    })();
    </script>
</body>
</html>
