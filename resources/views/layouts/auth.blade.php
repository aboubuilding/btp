<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — BTP Manager</title>
    <link rel="icon" href="{{ asset('app/assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @include('layouts.partials._design-tokens')
    @stack('css')
</head>
<body style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--btp-bg);padding:20px;font-family:'Inter',sans-serif;">
    @yield('contenu')
    <script src="{{ asset('app/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>toastr.options = {closeButton:true,progressBar:true,positionClass:'toast-top-right',timeOut:5000};</script>
    @stack('js')
</body>
</html>