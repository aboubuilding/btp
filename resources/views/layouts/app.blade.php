{{-- resources/views/layouts/app.blade.php --}}
    <!DOCTYPE html>
<html lang="fr" data-theme="light" data-layout="horizontal">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="BTP Manager - Plateforme de gestion de chantiers, engins, stock, RH et comptabilité pour entreprise de BTP">

    <title>@yield('title', 'Tableau de bord') — BTP Manager</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('app/assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('app/assets/img/apple-touch-icon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/css/mystyle.css') }}">

    <!-- CSS personnalisé BTP Manager -->
    <style>
        :root {
            --btp-primary: #0a1628;
            --btp-primary-dark: #060e1a;
            --btp-secondary: #1a3a5c;
            --btp-accent: #d4a745;
            --btp-accent-light: #f0d48a;
            --btp-accent-dark: #b8922e;
            --btp-success: #2d8f5e;
            --btp-danger: #c0392b;
            --btp-warning: #b7950b;
            --btp-info: #2b6cb0;
            --btp-muted: #6b7a8f;
            --btp-bg: #f0f2f5;
            --btp-card-bg: #ffffff;
            --btp-border: #e2e8f0;
            --btp-shadow: 0 2px 12px rgba(10, 22, 40, 0.08);
            --btp-shadow-hover: 0 8px 30px rgba(10, 22, 40, 0.12);
            --btp-radius: 12px;
            --btp-radius-lg: 16px;
            --btp-radius-xl: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--btp-bg);
            color: #1a202c;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ============================================
           PAGE WRAPPER
        ============================================ */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 70px; /* Hauteur du header */
        }

        /* ============================================
           PAGE HEADER BAR
        ============================================ */
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 30px;
            background: var(--btp-card-bg);
            border-bottom: 1px solid var(--btp-border);
            margin-bottom: 24px;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-title-main {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Kumbh Sans', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--btp-primary);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(212, 167, 69, 0.12);
            border-radius: 10px;
            color: var(--btp-accent);
            font-size: 1.1rem;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
            font-family: 'Kumbh Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--btp-muted);
            flex-wrap: wrap;
        }

        .breadcrumb-custom li {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb-custom li:not(:last-child)::after {
            content: '/';
            color: var(--btp-muted);
            opacity: 0.5;
        }

        .breadcrumb-custom a {
            color: var(--btp-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-custom a:hover {
            color: var(--btp-accent);
        }

        .breadcrumb-custom .active {
            color: var(--btp-primary);
            font-weight: 600;
        }

        .page-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ============================================
           CONTENT AREA
        ============================================ */
        .content-area {
            flex: 1;
            padding: 0 30px 30px;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 991.98px) {
            .page-header-bar {
                padding: 16px 20px;
                flex-direction: column;
                align-items: stretch;
            }

            .page-header-left {
                flex-direction: column;
                align-items: stretch;
            }

            .page-title-main {
                font-size: 1.1rem;
            }

            .content-area {
                padding: 0 16px 16px;
            }
        }

        @media (max-width: 576px) {
            .page-header-bar {
                padding: 12px 16px;
            }

            .page-title-main {
                font-size: 1rem;
            }

            .title-icon {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }

            .breadcrumb-custom {
                font-size: 0.75rem;
            }
        }

        /* ============================================
           TOAST CONTAINER
        ============================================ */
        #toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 380px;
            width: 100%;
        }

        /* ============================================
           SCROLLBAR STYLING
        ============================================ */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--btp-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--btp-muted);
            border-radius: 8px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--btp-secondary);
        }

        /* ============================================
           SELECTION
        ============================================ */
        ::selection {
            background: var(--btp-accent);
            color: #fff;
        }

        /* ============================================
           UTILITIES
        ============================================ */
        .text-btp-primary { color: var(--btp-primary); }
        .text-btp-accent { color: var(--btp-accent); }
        .text-btp-success { color: var(--btp-success); }
        .text-btp-danger { color: var(--btp-danger); }
        .text-btp-warning { color: var(--btp-warning); }
        .text-btp-info { color: var(--btp-info); }
        .text-btp-muted { color: var(--btp-muted); }

        .bg-btp-primary { background: var(--btp-primary); }
        .bg-btp-accent { background: var(--btp-accent); }
        .bg-btp-success { background: var(--btp-success); }
        .bg-btp-danger { background: var(--btp-danger); }
        .bg-btp-warning { background: var(--btp-warning); }
        .bg-btp-info { background: var(--btp-info); }
        .bg-btp-light { background: var(--btp-bg); }

        .fw-300 { font-weight: 300; }
        .fw-400 { font-weight: 400; }
        .fw-500 { font-weight: 500; }
        .fw-600 { font-weight: 600; }
        .fw-700 { font-weight: 700; }
        .fw-800 { font-weight: 800; }
        .fw-900 { font-weight: 900; }

        .rounded-btp { border-radius: var(--btp-radius); }
        .rounded-btp-lg { border-radius: var(--btp-radius-lg); }
        .rounded-btp-xl { border-radius: var(--btp-radius-xl); }

        .shadow-btp { box-shadow: var(--btp-shadow); }
        .shadow-btp-hover:hover { box-shadow: var(--btp-shadow-hover); }

        .transition-btp { transition: all 0.25s ease; }

        .card-btp {
            background: var(--btp-card-bg);
            border: none;
            border-radius: var(--btp-radius-lg);
            box-shadow: var(--btp-shadow);
            transition: all 0.25s ease;
        }

        .card-btp:hover {
            box-shadow: var(--btp-shadow-hover);
        }

        .card-btp .card-header {
            background: transparent;
            border-bottom: 1px solid var(--btp-border);
            padding: 18px 24px;
            font-weight: 600;
            font-family: 'Kumbh Sans', sans-serif;
        }

        .card-btp .card-body {
            padding: 24px;
        }

        .card-btp .card-footer {
            background: transparent;
            border-top: 1px solid var(--btp-border);
            padding: 16px 24px;
        }
    </style>

    {{-- CSS supplémentaire --}}
    @stack('css')
    @yield('css')
</head>

<body class="menu-horizontal">

{{-- Toast notifications --}}
<div id="toast-container" aria-live="polite" aria-atomic="true"></div>

{{-- Modale de recherche --}}
@include('layouts.partials._search')

{{-- En-tête BTP Manager --}}
@include('layouts.partials._header')

{{-- Wrapper principal --}}
<div class="page-wrapper">

    {{-- Barre de contexte --}}
    @hasSection('page_title')
        <div class="page-header-bar">
            <div class="page-header-left">
                <h1 class="page-title-main">
                    <span class="title-icon">
                        <i class="fas @yield('page_icon', 'fa-helmet-safety')"></i>
                    </span>
                    @yield('page_title')
                </h1>
                @hasSection('breadcrumb')
                    <nav aria-label="Fil d'Ariane">
                        <ul class="breadcrumb-custom">
                            @yield('breadcrumb')
                        </ul>
                    </nav>
                @endif
            </div>
            <div class="page-header-right">
                @yield('page_actions')
            </div>
        </div>
    @endif

    {{-- Contenu principal --}}
    <main class="content-area" role="main">
        @yield('contenu')
    </main>

    {{-- Pied de page --}}
    @include('layouts.partials._footer')

</div>

{{-- Scripts requis --}}
<script src="{{ asset('app/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="{{ asset('app/assets/js/feather.min.js') }}"></script>
<script src="{{ asset('app/assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('app/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('app/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('app/assets/plugins/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('app/assets/plugins/chartjs/chart-data.js') }}"></script>
<script src="{{ asset('app/assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('app/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('app/assets/plugins/apexchart/chart-data.js') }}"></script>
<script src="{{ asset('app/assets/plugins/@simonwep/pickr/pickr.es5.min.js') }}"></script>
<script src="{{ asset('app/assets/js/theme-colorpicker.js') }}"></script>
<script src="{{ asset('app/assets/js/script.js') }}"></script>

{{-- Toastr pour les notifications --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
    // Configuration de Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "extendedTimeOut": "2000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "preventDuplicates": true,
        "newestOnTop": true
    };

    // Configuration de Select2 en français
    $(document).ready(function() {
        if ($.fn.select2) {
            $.fn.select2.defaults.set('language', {
                inputTooShort: function(args) {
                    return 'Veuillez saisir ' + (args.min - args.input.length) + ' caractère(s) supplémentaire(s)';
                },
                noResults: function() {
                    return 'Aucun résultat trouvé';
                },
                searching: function() {
                    return 'Recherche en cours...';
                }
            });
        }
    });

    // Initialisation de Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Gestion des messages flash
    @if(session('success'))
    toastr.success('{{ session('success') }}', '✅ Succès');
    @endif

    @if(session('error'))
    toastr.error('{{ session('error') }}', '❌ Erreur');
    @endif

    @if(session('warning'))
    toastr.warning('{{ session('warning') }}', '⚠️ Attention');
    @endif

    @if(session('info'))
    toastr.info('{{ session('info') }}', 'ℹ️ Information');
    @endif
</script>

{{-- JS supplémentaire --}}
@stack('js')
@yield('js')

</body>
</html>
