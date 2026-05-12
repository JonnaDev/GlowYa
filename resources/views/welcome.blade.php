<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowYa | K-Beauty, Bienestar y Accesorios</title>
    <meta name="description" content="Descubre la mejor selección de K-Beauty, bienestar general y accesorios de gimnasio. Calidad original con pago contra entrega.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lab-blue-700: #0369a1;
        }
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #000;
            color: #fff;
        }
        
        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(0, 0, 0, 0.7);
            border-bottom: 1px solid rgba(56, 189, 248, 0.1);
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
            color: #38bdf8;
            opacity: .9;
        }

        /* ----- HERO WARP EFFECT ----- */
        .hero-warp-container {
            position: relative;
            height: 80vh;
            min-height: 600px;
            overflow: hidden;
            background-color: #000;
            perspective: 1000px;
        }
        
        .warp-grid {
            position: absolute;
            inset: -50%;
            width: 200%;
            height: 200%;
            background-image: 
                linear-gradient(rgba(56, 189, 248, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            transform: rotateX(var(--rotate-x, 0deg)) rotateY(var(--rotate-y, 0deg));
            transition: transform 0.1s ease-out;
            pointer-events: none;
            z-index: 1;
        }

        .warp-particle {
            position: absolute;
            color: #fff;
            pointer-events: none;
            z-index: 5;
            animation: particle-fade 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
        }

        @keyframes particle-fade {
            0% { opacity: 0.8; transform: translate(-50%, -50%) scale(0.5); }
            100% { opacity: 0; transform: translate(calc(-50% + var(--tx)), calc(-50% + var(--ty))) scale(1.5) rotate(var(--rot)); }
        }

        /* Overlay CTA */
        .hero-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            text-align: center;
            padding: 0 1.5rem;
            background: radial-gradient(circle at center, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.8) 100%);
        }

        .text-gradient {
            background: linear-gradient(to right, #e0f2fe, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-glow {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            box-shadow: 0 0 20px rgba(2, 132, 199, 0.4), inset 0 1px 0 rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.6), inset 0 1px 0 rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased">

    {{-- NAVBAR --}}
    <nav class="navbar fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-sparkles text-white text-sm"></i>
                    </div>
                    <span class="font-black text-2xl tracking-tighter text-white">Glow<span class="text-sky-400">Ya</span></span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-white/80 hover:text-white font-medium text-sm tracking-wide transition-colors">Inicio</a>
                    <a href="#productos" class="text-white/80 hover:text-white font-medium text-sm tracking-wide transition-colors">Productos</a>
                    <a href="#nosotros" class="text-white/80 hover:text-white font-medium text-sm tracking-wide transition-colors">Nuestra Visión</a>
                </div>
                <div>
                    <a href="#productos" class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold tracking-wide transition-all">
                        Explorar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- MARQUEE --}}
    <section class="relative pt-20 bg-[#020202] border-b border-sky-400/20 overflow-hidden">
        <div class="py-4 marquee">
            @php 
                $items = ['Contra Entrega', 'K-Beauty', 'Calidad', 'GlowUp', 'Original']; 
                // Duplicar para efecto infinito suave
                $items = array_merge($items, $items, $items, $items, $items, $items);
            @endphp
            @foreach($items as $item)
                <span class="marquee-item">
                    <i class="fa-solid fa-star text-[8px] text-sky-400"></i>
                    {{ $item }}
                </span>
            @endforeach
        </div>
    </section>

    {{-- HERO DINAMICO (WARP SPACE) --}}
    <section class="hero-warp-container">
        <!-- Espacio distorsionado -->
        <div class="warp-grid"></div>
        
        <div class="hero-overlay relative z-10 pointer-events-none">
            <div class="max-w-3xl mx-auto pointer-events-auto">
                <span class="inline-block py-1 px-3 rounded-full bg-sky-500/20 border border-sky-400/30 text-sky-300 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md">
                    Descubre tu mejor versión
                </span>
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight text-white">
                    El secreto de un <br>
                    <span class="text-gradient">GlowUp Definitivo</span>
                </h1>
                <p class="text-lg sm:text-xl text-white/70 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Nuestra misión es potenciar tu belleza y bienestar integral. Seleccionamos lo mejor del K-Beauty, suplementos de salud y accesorios fitness premium para acompañarte en tu transformación.
                </p>
                <a href="#nosotros" class="btn-glow inline-flex items-center gap-2 px-8 py-4 rounded-full text-white font-bold text-lg tracking-wide">
                    Conocer más <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ZIGZAG TIMELINE: ¿POR QUÉ NOSOTROS? --}}
    <section id="nosotros" class="py-24 px-6 bg-[#020202]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4">¿Por qué <span class="text-sky-400">nosotros?</span></h2>
                <div class="w-24 h-1 bg-gradient-to-r from-sky-400 to-blue-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="relative">
                <!-- Timeline line (Center on desktop, left on mobile) -->
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-white/10 md:-translate-x-1/2"></div>
                
                <!-- Fase 1 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between mb-16 md:mb-24">
                    <!-- Timeline Dot -->
                    <div class="absolute left-4 md:left-1/2 w-4 h-4 rounded-full bg-black border-2 border-sky-400 md:-translate-x-1/2 z-10 transform -translate-x-1/2 md:-translate-x-1/2 shadow-[0_0_15px_rgba(56,189,248,0.5)]"></div>
                    
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 md:text-right md:pr-12">
                        <h3 class="text-2xl font-bold text-white mb-3">Fórmulas Clínicas</h3>
                        <p class="text-white/60 leading-relaxed">Seleccionamos cuidadosamente ingredientes activos con respaldo científico, desde laboratorios coreanos de K-Beauty hasta producción nacional premium.</p>
                    </div>
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 mt-6 md:mt-0">
                        <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 aspect-video md:aspect-[4/3] relative group">
                            <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                            <img src="{{ asset('welcome/whyus/whyus-glowya.webp') }}" alt="K-Beauty Formulas" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100%\' height=\'100%\'><rect width=\'100%\' height=\'100%\' fill=\'%23111\'/></svg>'">
                        </div>
                    </div>
                </div>
                
                <!-- Fase 2 (Reversed on Desktop) -->
                <div class="relative flex flex-col md:flex-row-reverse items-center justify-between mb-16 md:mb-24">
                    <div class="absolute left-4 md:left-1/2 w-4 h-4 rounded-full bg-black border-2 border-sky-400 md:-translate-x-1/2 z-10 transform -translate-x-1/2 md:-translate-x-1/2 shadow-[0_0_15px_rgba(56,189,248,0.5)]"></div>
                    
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 md:text-left md:pl-12">
                        <h3 class="text-2xl font-bold text-white mb-3">Bienestar Integral</h3>
                        <p class="text-white/60 leading-relaxed">No solo cuidamos tu piel, también impulsamos tu rendimiento físico con suplementos de salud y accesorios de gimnasio de nivel profesional diseñados para durar.</p>
                    </div>
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 mt-6 md:mt-0">
                        <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 aspect-video md:aspect-[4/3] relative group">
                            <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                            <img src="{{ asset('welcome/whyus/whyus-fitness.webp') }}" alt="GlowYa Fitness" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100%\' height=\'100%\'><rect width=\'100%\' height=\'100%\' fill=\'%231a1a1a\'/></svg>'">
                        </div>
                    </div>
                </div>
                
                <!-- Fase 3 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between mb-8 md:mb-0">
                    <div class="absolute left-4 md:left-1/2 w-4 h-4 rounded-full bg-black border-2 border-sky-400 md:-translate-x-1/2 z-10 transform -translate-x-1/2 md:-translate-x-1/2 shadow-[0_0_15px_rgba(56,189,248,0.5)]"></div>
                    
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 md:text-right md:pr-12">
                        <h3 class="text-2xl font-bold text-white mb-3">Pago Contra Entrega</h3>
                        <p class="text-white/60 leading-relaxed">Tu confianza es nuestra prioridad. Recibes el producto en tus manos, lo revisas y lo pagas directamente al mensajero en cualquier parte del país. Sin riesgo.</p>
                    </div>
                    <div class="w-full md:w-[45%] pl-12 md:pl-0 mt-6 md:mt-0">
                        <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 aspect-video md:aspect-[4/3] relative group">
                            <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                            <img src="{{ asset('welcome/whyus/whyus-contraentrega.webp') }}" alt="Pago Contra Entrega GlowYa" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100%\' height=\'100%\'><rect width=\'100%\' height=\'100%\' fill=\'%230f172a\'/></svg>'">
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    {{-- SECCIÓN DE PRODUCTOS (SOLO PARA DARLE DESTINO AL BOTÓN Y MOSTRAR LOS ACTIVOS) --}}
    <section id="productos" class="py-24 px-6 bg-[#050505]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-white mb-4">Lanzamientos Exclusivos</h2>
                <div class="w-24 h-1 bg-sky-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($activeLandings as $landing)
                    <a href="{{ route('landing.show', $landing->slug) }}" class="group block relative rounded-2xl overflow-hidden border border-white/10 bg-white/5 hover:border-sky-400/50 transition-all duration-300">
                        <div class="aspect-[4/5] bg-black/50 relative">
                            {{-- Placeholder estilizado si no hay imagen específica en BD aún --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-b from-transparent to-[#0a0a0a]">
                                <i class="fa-solid fa-droplet text-6xl text-white/10 group-hover:text-sky-400/40 transition-colors duration-500 mb-4"></i>
                            </div>
                            <div class="absolute bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black via-black/80 to-transparent">
                                <span class="inline-block px-2 py-1 bg-sky-500 text-white text-[10px] font-bold uppercase tracking-wider rounded mb-2">
                                    Disponible
                                </span>
                                <h3 class="text-2xl font-bold text-white group-hover:text-sky-400 transition-colors">{{ $landing->title ?? ucfirst($landing->slug) }}</h3>
                                <p class="text-sm text-sky-300/80 mt-1 flex items-center gap-1 font-medium">
                                    Conocer más <i class="fa-solid fa-arrow-right text-xs"></i>
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 border border-white/10 rounded-2xl border-dashed">
                        <i class="fa-solid fa-sparkles text-3xl text-white/20 mb-4 block"></i>
                        <p class="text-white/50">Próximamente revelaremos nuevos productos.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // HERO WARP EFFECT LOGIC
            const hero = document.querySelector('.hero-warp-container');
            const grid = document.querySelector('.warp-grid');
            if (hero && grid) {
                const icons = [
                    'fa-spa', 'fa-leaf', 'fa-droplet', 'fa-star', 'fa-heart', 
                    'fa-spray-can-sparkles', 'fa-wand-magic-sparkles', 'fa-seedling'
                ];
                let lastSpawnTime = 0;

                hero.addEventListener('mousemove', (e) => {
                    const rect = hero.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    // Tilt grid
                    const rotateX = ((y - centerY) / centerY) * -15; // Max 15deg
                    const rotateY = ((x - centerX) / centerX) * 15;
                    grid.style.setProperty('--rotate-x', `${rotateX}deg`);
                    grid.style.setProperty('--rotate-y', `${rotateY}deg`);

                    // Spawn particles
                    const now = performance.now();
                    if (now - lastSpawnTime > 80) { // Throttle spawn rate
                        spawnParticle(x, y);
                        lastSpawnTime = now;
                    }
                });
                
                hero.addEventListener('mouseleave', () => {
                    grid.style.setProperty('--rotate-x', '0deg');
                    grid.style.setProperty('--rotate-y', '0deg');
                    grid.style.transition = 'transform 0.5s ease-out';
                    setTimeout(() => grid.style.transition = 'transform 0.1s ease-out', 500);
                });

                function spawnParticle(x, y) {
                    const particle = document.createElement('i');
                    const iconClass = icons[Math.floor(Math.random() * icons.length)];
                    particle.className = `fa-solid ${iconClass} warp-particle`;
                    
                    const tx = (Math.random() - 0.5) * 200; // Scatter X
                    const ty = (Math.random() - 0.5) * 200 - 50; // Scatter Y (bias up)
                    const rot = (Math.random() - 0.5) * 180;
                    const size = Math.random() * 12 + 10;
                    
                    particle.style.left = `${x}px`;
                    particle.style.top = `${y}px`;
                    particle.style.fontSize = `${size}px`;
                    particle.style.setProperty('--tx', `${tx}px`);
                    particle.style.setProperty('--ty', `${ty}px`);
                    particle.style.setProperty('--rot', `${rot}deg`);
                    
                    hero.appendChild(particle);
                    
                    setTimeout(() => {
                        if (particle.parentNode) {
                            particle.parentNode.removeChild(particle);
                        }
                    }, 1000); // 1s matches CSS animation duration
                }
            }
        });
    </script>
</html>
