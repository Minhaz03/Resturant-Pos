<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand RMS — Restaurant Management & Cloud POS</title>
    <link rel="icon" type="image/png" href="{{ asset('favPos.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            red: '#8B0000',
                            redDark: '#6B0000',
                            navy: '#0A2647',
                            gold: '#D4AF37',
                            bg: '#F8FAFC',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#F8FAFC] text-slate-800 font-sans antialiased overflow-x-hidden">

    <!-- Navigation Header -->
    <header
        class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-brand-gold to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/20 group-hover:rotate-6 transition-transform duration-300">
                        <i class="bi bi-cup-hot-fill text-white text-lg"></i>
                    </div>
                    <span class="font-outfit font-bold text-xl text-brand-navy tracking-tight">Grand<span
                            class="text-brand-red">RMS</span></span>
                </a>

                <!-- Nav Menu (Desktop) -->
                <nav class="hidden md:flex items-center gap-8 font-medium text-slate-600 text-sm">
                    <a href="{{ url('/#features') }}" class="hover:text-brand-red transition-colors">Features</a>
                    <a href="{{ url('/#demo') }}" class="hover:text-brand-red transition-colors">Dashboard Preview</a>
                    <a href="{{ url('/#pricing') }}" class="hover:text-brand-red transition-colors">Pricing</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}"
                        class="text-slate-600 hover:text-brand-red font-medium text-sm transition-colors">
                        Sign In
                    </a>
                    <a href="{{ url('/#register') }}"
                        class="bg-brand-red hover:bg-brand-redDark text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-red-900/10 hover:shadow-red-900/20 transition-all duration-200 hover:-translate-y-0.5">
                        Register Restaurant
                    </a>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-brand-gold rounded-lg flex items-center justify-center text-white">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <span class="font-outfit font-bold text-white">Grand RMS</span>
                </div>
                <div class="flex gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] uppercase tracking-widest text-brand-gold font-bold">Built by</span>
                        <img class="h-6 w-40 object-contain brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300"
                            src="{{ asset('solutionclime.webp') }}" alt="Solution Clime">
                    </div>
                </div>
            </div>
            <div class="text-sm text-center text-slate-500 mt-2 pt-2 border-t border-slate-800/40">
                &copy; 2026 Grand RMS. All rights reserved. Built with passion for dining services.
            </div>
        </div>
    </footer>

</body>

</html>
