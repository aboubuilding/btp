<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="BTP Manager — Gestion de chantiers, engins, stock, RH">
    <title>@yield('title', 'Tableau de bord') — BTP Manager</title>

    <link rel="icon" href="{{ asset('app/assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@1.0.3/dist/frappe-gantt.min.css">
    @include('layouts.partials._design-tokens')
    @stack('css')
</head>
<body>
    <div id="toast-container"></div>
    @include('layouts.partials._search')
    @include('layouts.partials._header')

    <div class="page-wrapper">
        @include('layouts.partials._alerts')
        @hasSection('page_title')
            @include('layouts.partials._page-header')
        @endif
        <main class="content-area" role="main">@yield('contenu')</main>
        @include('layouts.partials._footer')
    </div>

    <script src="{{ asset('app/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@1.0.3/dist/frappe-gantt.min.js"></script>
    <script src="{{ asset('app/assets/js/btp-modal.js') }}"></script>
    <script>
        window.BTP = {
            currency: 'FCFA', locale: 'fr',
            user: { id: {{ auth()->id() ?? 'null' }}, nom: @json(auth()->user()->nom ?? null) }
        };
        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:5000 };
        $(function() {
            if ($.fn.select2) $.fn.select2.defaults.set('language', { noResults: () => 'Aucun résultat', searching: () => 'Recherche…' });
            $('.use-select2').select2({ width: '100%' });
        });
    </script>
    @stack('js')
</body>
</html>