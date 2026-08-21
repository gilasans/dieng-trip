<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dieng Trip Manager - Kelola perjalanan liburan keluarga ke Dieng">
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Dieng Trip Manager')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Global helpers: defined as regular script (not module) so inline onclick handlers can always call them --}}
    <script>
        // Modal System
        window.openModal = function(modalId) {
            var modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };
        window.closeModal = function(modalId) {
            var modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        };

        // Image Preview
        window.previewImage = function(input, previewId) {
            var preview = document.getElementById(previewId);
            if (input.files && input.files[0] && preview) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        // Toast
        window.showToast = function(message, type, duration) {
            type = type || 'success';
            duration = duration || 3000;
            var icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
            var container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container';
                document.body.appendChild(container);
            }
            var toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.innerHTML = '<div class="flex items-center gap-2"><span>' + (icons[type] || '✅') + '</span><span>' + message + '</span></div>';
            container.appendChild(toast);
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(function() { toast.remove(); }, 300);
            }, duration);
        };

        // AJAX Helper
        window.ajax = async function(url, options) {
            options = options || {};
            var headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
            };
            if (!(options.body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
                if (options.body && typeof options.body === 'object') {
                    options.body = JSON.stringify(options.body);
                }
            }
            var config = Object.assign({}, options, { headers: Object.assign({}, headers, options.headers || {}) });
            if (options.body instanceof FormData) delete config.headers['Content-Type'];
            try {
                var response = await fetch(url, config);
                var data = await response.json();
                if (!response.ok) throw { status: response.status, data: data };
                return data;
            } catch (error) {
                if (error && error.data) throw error;
                throw { status: 500, data: { message: 'Koneksi gagal. Coba lagi.' } };
            }
        };

        // Custom Confirm
        window.customConfirm = function(message, title) {
            title = title || 'Konfirmasi';
            return new Promise(function(resolve) {
                document.getElementById('confirm-title').innerText = title;
                document.getElementById('confirm-message').innerText = message;
                window.openModal('custom-confirm-modal');
                document.getElementById('confirm-ok-btn').onclick = function() {
                    window.closeModal('custom-confirm-modal');
                    resolve(true);
                };
                document.getElementById('confirm-cancel-btn').onclick = function() {
                    window.closeModal('custom-confirm-modal');
                    resolve(false);
                };
            });
        };

        // Lightbox
        window.openLightbox = function(src) {
            var lightbox = document.getElementById('lightbox');
            if (!lightbox) {
                lightbox = document.createElement('div');
                lightbox.id = 'lightbox';
                lightbox.className = 'lightbox';
                lightbox.onclick = function() {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                };
                document.body.appendChild(lightbox);
            }
            lightbox.innerHTML = '<img src="' + src + '" alt="Preview" class="animate-scale-in">';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 25%, #f0f9ff 50%, #f8fafc 100%);
            min-height: 100vh;
            padding-bottom: 5.5rem;
        }
    </style>
</head>
<body class="antialiased">

    {{-- Page Header --}}
    <header class="sticky top-0 z-40 glass px-4 py-3">
        <div class="max-w-lg mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-900">@yield('header', '🏔️ Dieng Trip')</h1>
                @hasSection('subheader')
                    <p class="text-xs text-gray-500">@yield('subheader')</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @yield('header-actions')
                <a href="{{ route('members.index') }}" class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center hover:bg-emerald-200 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-lg mx-auto px-4 py-4">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        <div class="max-w-lg mx-auto flex">
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-[22px] h-[22px] mb-0.5" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('dashboard') ? '0' : '1.8' }}">
                    @if(request()->routeIs('dashboard'))
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    @endif
                </svg>
                <span>Home</span>
            </a>
            <a href="{{ route('expenses.index') }}" class="bottom-nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <svg class="w-[22px] h-[22px] mb-0.5" fill="{{ request()->routeIs('expenses.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('expenses.*') ? '0' : '1.8' }}">
                    @if(request()->routeIs('expenses.*'))
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    @endif
                </svg>
                <span>Expense</span>
            </a>
            <a href="{{ route('itinerary.index') }}" class="bottom-nav-item {{ request()->routeIs('itinerary.*') ? 'active' : '' }}">
                <svg class="w-[22px] h-[22px] mb-0.5" fill="{{ request()->routeIs('itinerary.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('itinerary.*') ? '0' : '1.8' }}">
                    @if(request()->routeIs('itinerary.*'))
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    @endif
                </svg>
                <span>Itinerary</span>
            </a>
            <a href="{{ route('destinations.index') }}" class="bottom-nav-item {{ request()->routeIs('destinations.*') ? 'active' : '' }}">
                <svg class="w-[22px] h-[22px] mb-0.5" fill="{{ request()->routeIs('destinations.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('destinations.*') ? '0' : '1.8' }}">
                    @if(request()->routeIs('destinations.*'))
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    @endif
                </svg>
                <span>Destinasi</span>
            </a>
            <a href="{{ route('gallery.index') }}" class="bottom-nav-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                <svg class="w-[22px] h-[22px] mb-0.5" fill="{{ request()->routeIs('gallery.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('gallery.*') ? '0' : '1.8' }}">
                    @if(request()->routeIs('gallery.*'))
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    @endif
                </svg>
                <span>Gallery</span>
            </a>
        </div>
    </nav>

    {{-- Toast Container --}}
    <div id="toast-container" class="toast-container"></div>

    {{-- Custom Confirm Modal --}}
    <div id="custom-confirm-modal" class="modal-overlay !items-center" style="z-index: 1000;">
        <div class="modal-content !translate-y-0 !rounded-3xl" style="max-width: 320px; padding: 1.5rem; text-align: center;">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 id="confirm-title" class="text-lg font-bold text-gray-900 mb-2">Konfirmasi</h3>
            <p id="confirm-message" class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin melanjutkan?</p>
            <div class="flex gap-3">
                <button id="confirm-cancel-btn" class="flex-1 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition-colors">Batal</button>
                <button id="confirm-ok-btn" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white font-semibold text-sm hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
