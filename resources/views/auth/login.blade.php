<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion · BTP Manager</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app/assets/img/favicon.png') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap.min.css') }}" />

    <!-- Fontawesome & Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/tabler-icons/tabler-icons.min.css') }}" />

    <!-- SweetAlert2 & Toastr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <style>
        :root {
            /* Palette alignée sur le layout BTP Manager (graphite + orange sécurité) */
            --btp-primary: #1c2530;
            --btp-primary-dark: #121924;
            --btp-secondary: #2a3644;
            --btp-accent: #f0900c;
            --btp-accent-light: #ffb648;
            --btp-accent-dark: #c96f00;
            --btp-success: #2d8f5e;
            --btp-danger: #e63946;
            --btp-warning: #b7950b;
            --btp-ink: #1a202c;
            --btp-muted: #6b7a8f;
            --btp-white: #ffffff;
            --btp-bg: #eef0f3;
            --btp-card-bg: #ffffff;
            --btp-shadow: 0 25px 60px rgba(10, 15, 22, 0.16);
            --btp-shadow-hover: 0 30px 70px rgba(10, 15, 22, 0.22);
            --btp-border-radius: 20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body.account-page {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            height: 100vh;
            background: var(--btp-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Fond avec motif BTP */
        body.account-page::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(240, 144, 12, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(28, 37, 48, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        #global-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .btp-loader-ring {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 4px solid #eef0f3;
            border-top-color: var(--btp-accent);
            animation: btpSpin 0.8s linear infinite;
        }
        @keyframes btpSpin { to { transform: rotate(360deg); } }

        .btp-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1150px;
            height: 88vh;
            max-height: 780px;
            padding: 0;
            display: flex;
            align-items: stretch;
            gap: 0;
            overflow: hidden;
            background: var(--btp-card-bg);
            border-radius: var(--btp-border-radius);
            box-shadow: var(--btp-shadow);
            transition: box-shadow 0.3s ease;
        }
        .btp-wrapper:hover {
            box-shadow: var(--btp-shadow-hover);
        }

        /* Liseré "signalétique chantier" en haut de la carte */
        .btp-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            z-index: 3;
            background: repeating-linear-gradient(
                135deg,
                var(--btp-accent) 0 14px,
                var(--btp-primary) 14px 28px
            );
        }

        /* ============== PANEL GAUCHE ============== */

        .btp-brand-panel {
            flex: 0 0 44%;
            height: 100%;
            background: linear-gradient(160deg, var(--btp-primary-dark) 0%, var(--btp-primary) 40%, var(--btp-secondary) 100%);
            background-size: 180% 180%;
            animation: btpDrift 18s ease-in-out infinite alternate;
            padding: 48px 44px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-radius: var(--btp-border-radius) 0 0 var(--btp-border-radius);
        }
        @keyframes btpDrift {
            0%   { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }
        @media (prefers-reduced-motion: reduce) {
            .btp-brand-panel { animation: none; }
        }

        .btp-brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 30% 80%, rgba(240, 144, 12, 0.10) 0%, transparent 60%),
                radial-gradient(circle at 70% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        .btp-brand-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(transparent, transparent 35px, rgba(255,255,255,0.025) 35px, rgba(255,255,255,0.025) 36px),
                repeating-linear-gradient(90deg, transparent, transparent 35px, rgba(255,255,255,0.025) 35px, rgba(255,255,255,0.025) 36px);
            pointer-events: none;
            z-index: 1;
        }

        .btp-brand-content {
            position: relative;
            z-index: 2;
            max-width: 420px;
        }

        .btp-brand-logo {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 36px;
        }
        .btp-brand-logo .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(240, 144, 12, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            color: var(--btp-accent);
            border: 1px solid rgba(240, 144, 12, 0.28);
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .btp-brand-logo:hover .logo-icon {
            transform: scale(1.05) rotate(-3deg);
        }
        .btp-brand-logo-text {
            font-weight: 800;
            font-size: 1.5rem;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .btp-brand-logo-text small {
            display: block;
            font-weight: 500;
            font-size: 0.6rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
        }

        .btp-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: var(--btp-accent-light);
            margin-bottom: 14px;
        }
        .btp-brand-badge::before {
            content: '';
            width: 28px;
            height: 2px;
            background: var(--btp-accent);
        }

        .btp-brand-title {
            font-weight: 800;
            font-size: clamp(1.8rem, 3.2vw, 2.6rem);
            line-height: 1.15;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }
        .btp-brand-title span {
            color: var(--btp-accent);
            position: relative;
        }
        .btp-brand-title span::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--btp-accent);
            border-radius: 2px;
            opacity: 0.4;
        }
        .btp-brand-desc {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .btp-features {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .btp-features li {
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 500;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.85);
            transition: color 0.2s;
            opacity: 0;
            transform: translateX(-8px);
            animation: btpFeatureIn 0.5s ease-out forwards;
        }
        .btp-features li:nth-child(1) { animation-delay: 0.15s; }
        .btp-features li:nth-child(2) { animation-delay: 0.25s; }
        .btp-features li:nth-child(3) { animation-delay: 0.35s; }
        .btp-features li:nth-child(4) { animation-delay: 0.45s; }
        @keyframes btpFeatureIn {
            to { opacity: 1; transform: translateX(0); }
        }
        .btp-features li:hover {
            color: #ffffff;
        }
        .btp-features li i {
            width: 34px;
            height: 34px;
            background: rgba(240, 144, 12, 0.12);
            border: 1px solid rgba(240, 144, 12, 0.22);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: var(--btp-accent);
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        .btp-features li:hover i {
            background: rgba(240, 144, 12, 0.22);
            border-color: var(--btp-accent);
            transform: scale(1.05);
        }

        .btp-brand-footer {
            margin-top: auto;
            padding-top: 22px;
            font-size: 0.68rem;
            color: rgba(255,255,255,0.32);
            letter-spacing: 0.3px;
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btp-brand-footer i { color: rgba(255,255,255,0.32); font-size: 0.75rem; }

        /* ============== PANEL DROIT ============== */

        .btp-form-panel {
            flex: 1;
            height: 100%;
            background: var(--btp-card-bg);
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 var(--btp-border-radius) var(--btp-border-radius) 0;
            overflow-y: auto;
        }
        .btp-form-inner {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            opacity: 0;
            transform: translateY(10px);
            animation: btpFormIn 0.5s ease-out 0.2s forwards;
        }
        @keyframes btpFormIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .btp-mobile-logo {
            display: none;
            text-align: center;
            margin-bottom: 24px;
        }
        .btp-mobile-logo .logo-icon {
            width: 48px;
            height: 48px;
            background: var(--btp-primary);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--btp-accent);
            margin-bottom: 8px;
        }
        .btp-mobile-logo h5 {
            font-weight: 700;
            color: var(--btp-ink);
            margin: 0;
            font-size: 1rem;
        }

        .btp-card-head {
            margin-bottom: 28px;
        }
        .btp-card-head .btp-icon-badge {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--btp-primary), var(--btp-secondary));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--btp-accent-light);
            box-shadow: 0 10px 24px rgba(10, 15, 22, 0.22);
            margin-bottom: 14px;
            transition: transform 0.3s ease;
        }
        .btp-card-head .btp-icon-badge:hover {
            transform: scale(1.05);
        }
        .btp-card-head h3 {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--btp-ink);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }
        .btp-card-head p {
            color: var(--btp-muted);
            font-size: 0.88rem;
            margin: 0;
        }

        /* ---- Bandeau d'alerte ---- */
        .btp-alert-banner {
            display: none;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            line-height: 1.5;
            border: 1px solid transparent;
            animation: alertIn 0.25s ease-out both;
        }
        .btp-alert-banner.show { display: flex; }
        .btp-alert-banner i {
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .btp-alert-banner .alert-title {
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .btp-alert-banner.type-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--btp-danger);
        }
        .btp-alert-banner.type-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: var(--btp-warning);
        }
        .btp-alert-banner.type-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: var(--btp-success);
        }
        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btp-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--btp-ink);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .btp-label i { color: var(--btp-primary); }

        .btp-input-group {
            display: flex;
            align-items: stretch;
            background: #f8f9fc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.25s ease;
            overflow: hidden;
        }
        .btp-input-group:focus-within {
            border-color: var(--btp-primary);
            box-shadow: 0 0 0 4px rgba(28, 37, 48, 0.08);
            background: #ffffff;
        }
        .btp-input-group:focus-within .input-group-text:first-child {
            color: var(--btp-accent);
        }
        .btp-input-group.is-invalid {
            border-color: var(--btp-danger);
            background: #fef2f2;
        }
        .btp-input-group.is-invalid:focus-within {
            box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.08);
        }
        .btp-input-group .form-control {
            border: none;
            background: transparent;
            padding: 13px 16px;
            font-size: 0.9rem;
            box-shadow: none !important;
            color: var(--btp-ink);
            font-weight: 500;
        }
        .btp-input-group .form-control:focus { outline: none; box-shadow: none; }
        .btp-input-group .form-control::placeholder {
            color: #a0aec0;
            font-weight: 400;
        }
        .btp-input-group .input-group-text {
            border: none;
            background: transparent;
            color: #a0aec0;
            padding: 0 14px;
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btp-input-group .toggle-password:hover {
            color: var(--btp-primary);
        }

        .invalid-feedback.d-block {
            display: flex !important;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            margin-top: 6px;
            color: var(--btp-danger);
            font-weight: 500;
        }

        .btp-row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 24px;
            font-size: 0.85rem;
        }

        /* Interrupteur "Se souvenir de moi" */
        .btp-toggle {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
        }
        .btp-toggle input { display: none; }
        .btp-toggle .switch {
            width: 36px;
            height: 20px;
            border-radius: 20px;
            background: #dce1e8;
            position: relative;
            transition: background 0.2s ease;
            flex-shrink: 0;
        }
        .btp-toggle .switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
            transition: transform 0.2s ease;
        }
        .btp-toggle input:checked + .switch {
            background: var(--btp-accent);
        }
        .btp-toggle input:checked + .switch::after {
            transform: translateX(16px);
        }
        .btp-toggle span.label-text {
            color: #4a5568;
            font-weight: 500;
        }

        .btp-row-between a {
            color: var(--btp-primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
            font-size: 0.85rem;
        }
        .btp-row-between a:hover {
            color: var(--btp-accent-dark);
            text-decoration: underline;
        }

        .btp-btn-submit {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            background: linear-gradient(120deg, var(--btp-primary), var(--btp-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(28, 37, 48, 0.25);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .btp-btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, var(--btp-accent), var(--btp-accent-dark));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .btp-btn-submit:hover:not(:disabled)::before {
            opacity: 1;
        }
        .btp-btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(28, 37, 48, 0.35);
        }
        .btp-btn-submit:active:not(:disabled) {
            transform: scale(0.97);
        }
        .btp-btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .btp-btn-submit span {
            position: relative;
            z-index: 1;
        }

        .btp-secure-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 0.72rem;
            color: #a0aec0;
            font-weight: 500;
        }
        .btp-secure-note i { color: #b7c0cc; font-size: 0.7rem; }

        .btp-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 22px 0 18px;
            color: #a0aec0;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .btp-divider span {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .btp-register {
            text-align: center;
            font-size: 0.88rem;
            color: #4a5568;
        }
        .btp-register a {
            color: var(--btp-primary);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .btp-register a:hover {
            color: var(--btp-accent-dark);
            text-decoration: underline;
        }

        .btp-footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.7rem;
            color: #a0aec0;
        }

        /* Toastr personnalisé */
        #toast-container > .toast-success { background-color: var(--btp-success) !important; }
        #toast-container > .toast-error { background-color: var(--btp-danger) !important; }
        #toast-container > .toast-warning { background-color: var(--btp-warning) !important; }
        #toast-container > .toast-info { background-color: var(--btp-secondary) !important; }

        /* ============== RESPONSIVE ============== */

        @media (max-width: 991.98px) {
            body.account-page {
                height: auto;
                overflow-y: auto;
                padding: 20px;
            }
            .btp-wrapper {
                flex-direction: column;
                height: auto;
                max-height: none;
                border-radius: 16px;
                max-width: 500px;
                margin: 0 auto;
            }
            .btp-brand-panel {
                flex: none;
                height: auto;
                min-height: 260px;
                padding: 28px 24px;
                border-radius: 16px 16px 0 0;
            }
            .btp-brand-panel::before { display: none; }
            .btp-brand-content {
                max-width: 100%;
                text-align: center;
            }
            .btp-features { display: none; }
            .btp-brand-logo { justify-content: center; }
            .btp-mobile-logo { display: block; }
            .btp-form-panel {
                flex: none;
                height: auto;
                padding: 28px 20px;
                border-radius: 0 0 16px 16px;
            }
            .btp-form-inner {
                max-width: 100%;
            }
            .btp-brand-title { font-size: 1.6rem; }
        }

        @media (max-width: 480px) {
            body.account-page { padding: 12px; }
            .btp-brand-panel { padding: 20px 16px; min-height: 200px; }
            .btp-brand-panel .btp-brand-title { font-size: 1.3rem; }
            .btp-brand-panel .btp-brand-desc { font-size: 0.8rem; }
            .btp-form-panel { padding: 20px 16px; }
            .btp-card-head h3 { font-size: 1.2rem; }
            .btp-brand-logo .logo-icon { width: 44px; height: 44px; font-size: 1.4rem; }
            .btp-brand-logo-text { font-size: 1.1rem; }
        }

        /* Animations */
        .btp-wrapper { animation: fadeInUp 0.8s ease-out both; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-6px); }
            20%, 40%, 60%, 80% { transform: translateX(6px); }
        }
        .shake { animation: shake 0.4s ease; }

        @media (prefers-reduced-motion: reduce) {
            .btp-wrapper, .shake, .btp-alert-banner, .btp-form-inner, .btp-features li { animation: none !important; opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body class="account-page">

<!-- LOADER -->
<div id="global-loader">
    <div class="btp-loader-ring" role="status" aria-label="Chargement"></div>
</div>

<!-- WRAPPER -->
<div class="btp-wrapper">

    <!-- PANEL GAUCHE -->
    <aside class="btp-brand-panel">
        <div class="btp-brand-content">
            <div class="btp-brand-logo">
                <div class="logo-icon"><i class="fas fa-helmet-safety"></i></div>
                <div class="btp-brand-logo-text">
                    BTP Manager
                    <small>Gestion intégrée des chantiers</small>
                </div>
            </div>

            <span class="btp-brand-badge">Plateforme professionnelle</span>
            <h1 class="btp-brand-title">Gérez vos chantiers en <span>toute efficacité</span></h1>
            <p class="btp-brand-desc">
                Solution complète de gestion pour le secteur du BTP :
                projets, équipes, engins, stocks, finances et reporting, du bureau au chantier.
            </p>

            <ul class="btp-features">
                <li><i class="ti ti-building"></i> Pilotage des projets et chantiers</li>
                <li><i class="ti ti-users"></i> Gestion des équipes et sous-traitants</li>
                <li><i class="ti ti-package"></i> Suivi des stocks et approvisionnements</li>
                <li><i class="ti ti-report-money"></i> Budgets, factures et trésorerie</li>
            </ul>

            <div class="btp-brand-footer">
                <i class="fas fa-shield-halved"></i> &copy; {{ date('Y') }} BTP Manager — Tous droits réservés.
            </div>
        </div>
    </aside>

    <!-- PANEL DROIT -->
    <div class="btp-form-panel">
        <div class="btp-form-inner">

            <!-- Logo mobile -->
            <div class="btp-mobile-logo">
                <div class="logo-icon"><i class="fas fa-helmet-safety"></i></div>
                <h5>BTP Manager</h5>
            </div>

            <!-- En-tête -->
            <div class="btp-card-head">
                <div class="btp-icon-badge"><i class="ti ti-login-2"></i></div>
                <h3>Connexion</h3>
                <p>Accédez à votre espace de travail</p>
            </div>

            <!-- Bandeau d'alerte -->
            <div id="alert-banner" class="btp-alert-banner" role="alert" aria-live="assertive">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <span class="alert-title" id="alert-banner-title"></span>
                    <span id="alert-banner-text"></span>
                </div>
            </div>

            <!-- Formulaire -->
            <form id="form-login" autocomplete="off" novalidate>
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="btp-label" for="email"><i class="ti ti-mail"></i> Adresse email <span class="text-danger">*</span></label>
                    <div class="btp-input-group" id="group-email">
                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="exemple@domaine.com" autocomplete="email" required autofocus
                               aria-describedby="error-email">
                    </div>
                    <div class="invalid-feedback d-block" id="error-email" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> <span>Email obligatoire</span>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="mb-2">
                    <label class="btp-label" for="mot_de_passe"><i class="ti ti-lock"></i> Mot de passe <span class="text-danger">*</span></label>
                    <div class="btp-input-group" id="group-password">
                        <span class="input-group-text"><i class="ti ti-key"></i></span>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control"
                               placeholder="••••••••" autocomplete="current-password" required
                               aria-describedby="error-motdepasse">
                        <span class="input-group-text toggle-password" id="togglePassword" role="button" tabindex="0" aria-label="Afficher le mot de passe">
                            <i class="ti ti-eye-off" id="eye-icon"></i>
                        </span>
                    </div>
                    <div class="invalid-feedback d-block" id="error-motdepasse" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> <span>Le mot de passe est obligatoire</span>
                    </div>
                </div>

                <!-- Options -->
                <div class="btp-row-between">
                    <label class="btp-toggle" for="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="switch"></span>
                        <span class="label-text">Se souvenir de moi</span>
                    </label>
                    <a href="#" id="forgotLink">Mot de passe oublié ?</a>
                </div>

                <!-- Bouton -->
                <button type="submit" class="btp-btn-submit" id="btn-login">
                    <span class="btn-spinner" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                    <span class="btn-text"><i class="ti ti-login"></i> Se connecter</span>
                </button>

                <p class="btp-secure-note"><i class="ti ti-shield-lock"></i> Connexion chiffrée</p>

                <!-- Divider -->
                <div class="btp-divider">
                    <span></span> ou <span></span>
                </div>

                <!-- Lien inscription -->
                <p class="btp-register mb-0">
                    Nouveau sur la plateforme ?
                    <a href="#"><i class="ti ti-user-plus"></i> Créer un compte</a>
                </p>

            </form>

            <!-- Footer -->
            <div class="btp-footer-note">
                &copy; {{ date('Y') }} <strong>BTP Manager</strong> — Tous droits réservés.
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="{{ asset('app/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // ============================================
    // CONFIGURATION
    // ============================================

    var LOGIN_ROUTE = "{{ route('login.post') }}";
    var DASHBOARD_ROUTE = "{{ route('dashboard') }}";

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "extendedTimeOut": "2000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    var ERROR_MAP = {
        USER_NOT_FOUND: {
            field: 'email',
            banner: 'danger',
            title: 'Email introuvable',
            text: "Aucun compte ne correspond à cet email. Vérifiez votre saisie."
        },
        INVALID_PASSWORD: {
            field: 'mot_de_passe',
            banner: 'danger',
            title: 'Mot de passe incorrect',
            text: "Le mot de passe saisi est incorrect. Vérifiez votre saisie."
        },
        ACCOUNT_INACTIVE: {
            sweetalert: {
                icon: 'warning',
                title: 'Compte désactivé',
                text: "Votre compte a été désactivé. Contactez l'administrateur."
            }
        },
        ERROR: {
            banner: 'danger',
            title: 'Erreur technique',
            text: "Une erreur technique est survenue. Veuillez réessayer."
        }
    };

    // ============================================
    // INITIALISATION
    // ============================================

    $(document).ready(function() {
        setTimeout(function() {
            $('#global-loader').addClass('hidden');
        }, 400);

        clearData();
        bindEvents();
        checkSession();
    });

    // ============================================
    // GESTION DES ÉVÉNEMENTS
    // ============================================

    function bindEvents() {
        $('#form-login').on('submit', function(e) {
            e.preventDefault();
            handleLogin();
        });

        $('#togglePassword').on('click keydown', function(e) {
            if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            const input = $('#mot_de_passe');
            const icon = $('#eye-icon');
            const isHidden = input.attr('type') === 'password';
            input.attr('type', isHidden ? 'text' : 'password');
            icon.toggleClass('ti-eye-off', !isHidden).toggleClass('ti-eye', isHidden);
            $(this).attr('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        });

        $('#email, #mot_de_passe').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleLogin();
            }
        });

        $('#email').on('blur', function() { validateField('email'); });
        $('#mot_de_passe').on('blur', function() { validateField('mot_de_passe'); });

        $('#email, #mot_de_passe').on('input', function() {
            const field = $(this).attr('id');
            const errorId = field === 'email' ? 'error-email' : 'error-motdepasse';
            const groupId = field === 'email' ? 'group-email' : 'group-password';
            clearFieldError(errorId, groupId);
            hideBanner();
        });

        $('#forgotLink').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Mot de passe oublié',
                text: "Veuillez contacter l'administrateur pour réinitialiser votre mot de passe.",
                confirmButtonColor: '#1c2530',
                confirmButtonText: 'Compris'
            });
        });
    }

    // ============================================
    // VALIDATION
    // ============================================

    function validateField(field) {
        const value = $('#' + field).val().trim();
        const errorId = field === 'email' ? 'error-email' : 'error-motdepasse';
        const groupId = field === 'email' ? 'group-email' : 'group-password';

        if (value === '') {
            showFieldError(errorId, groupId, field === 'email'
                ? 'L\'email est obligatoire'
                : 'Le mot de passe est obligatoire');
            return false;
        }

        if (field === 'email' && !isValidEmail(value)) {
            showFieldError(errorId, groupId, 'Veuillez saisir un email valide');
            return false;
        }

        clearFieldError(errorId, groupId);
        return true;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function showFieldError(errorId, groupId, message) {
        $('#' + errorId + ' span').text(message);
        $('#' + errorId).show();
        $('#' + groupId).addClass('is-invalid');
    }

    function clearFieldError(errorId, groupId) {
        $('#' + errorId).hide();
        $('#' + groupId).removeClass('is-invalid');
    }

    function clearErrors() {
        clearFieldError('error-email', 'group-email');
        clearFieldError('error-motdepasse', 'group-password');
        hideBanner();
    }

    function clearData() {
        $('#email').val('');
        $('#mot_de_passe').val('');
        clearErrors();
    }

    // ============================================
    // BANDEAU D'ALERTE
    // ============================================

    function showBanner(type, title, text) {
        const banner = $('#alert-banner');
        banner.removeClass('type-danger type-warning type-success').addClass('type-' + type);
        banner.find('i').attr('class', type === 'warning' ? 'fas fa-triangle-exclamation' :
            type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle');
        $('#alert-banner-title').text(title);
        $('#alert-banner-text').text(text);
        banner.addClass('show');
    }

    function hideBanner() {
        $('#alert-banner').removeClass('show type-danger type-warning type-success');
    }

    // ============================================
    // AUTHENTIFICATION
    // ============================================

    function handleLogin() {
        const email = $('#email').val().trim();
        const password = $('#mot_de_passe').val();

        clearErrors();

        const emailValid = validateField('email');
        const passwordValid = validateField('mot_de_passe');

        if (!emailValid || !passwordValid) {
            toastr.warning('Veuillez corriger les champs en surbrillance.');
            return;
        }

        if (password.length < 6) {
            showFieldError('error-motdepasse', 'group-password', 'Le mot de passe doit contenir au moins 6 caractères.');
            toastr.warning('Le mot de passe doit contenir au moins 6 caractères.');
            return;
        }

        authentifier(email, password);
    }

    function authentifier(email, password) {
        setLoading(true);

        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: LOGIN_ROUTE,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            data: {
                email: email,
                mot_de_passe: password
            },
            timeout: 10000,

            success: function(data) {
                if (data.success) {
                    handleLoginSuccess(data);
                } else {
                    setLoading(false);
                    handleBusinessError(data.code, data.message);
                }
            },

            error: function(xhr) {
                setLoading(false);
                handleAjaxError(xhr);
            }
        });
    }

    function handleLoginSuccess(data) {
        toastr.success(data.message || 'Connexion réussie !', 'Connexion réussie', {
            timeOut: 3000,
            extendedTimeOut: 1000
        });

        setRedirecting();

        setTimeout(function() {
            window.location.href = data.redirect || DASHBOARD_ROUTE;
        }, 1200);
    }

    function showError(type, title, text) {
        showBanner(type, title, text);

        if (type === 'warning') {
            toastr.warning(text, title);
        } else if (type === 'success') {
            toastr.success(text, title);
        } else {
            toastr.error(text, title);
        }
    }

    function handleBusinessError(code, fallbackMessage) {
        const mapping = ERROR_MAP[code];

        if (mapping && mapping.sweetalert) {
            const text = mapping.sweetalert.text || fallbackMessage;
            toastr.error(text, mapping.sweetalert.title);
            Swal.fire({
                icon: mapping.sweetalert.icon,
                title: mapping.sweetalert.title,
                text: text,
                confirmButtonColor: '#1c2530',
                confirmButtonText: "J'ai compris"
            });
            return;
        }

        if (mapping && mapping.field) {
            const specificMessage = mapping.text;
            const errorId = mapping.field === 'email' ? 'error-email' : 'error-motdepasse';
            const groupId = mapping.field === 'email' ? 'group-email' : 'group-password';

            showFieldError(errorId, groupId, specificMessage);
            $(mapping.field === 'email' ? '#email' : '#mot_de_passe').trigger('focus');
            showError(mapping.banner, mapping.title, specificMessage);
            shakeForm();
            return;
        }

        showError(
            (mapping && mapping.banner) || 'danger',
            (mapping && mapping.title) || 'Connexion impossible',
            (mapping && mapping.text) || fallbackMessage || 'Email ou mot de passe incorrect.'
        );

        shakeForm();
    }

    // ============================================
    // GESTION DES ERREURS HTTP
    // ============================================

    function handleAjaxError(xhr) {
        if (xhr.status === 401) {
            try {
                const response = JSON.parse(xhr.responseText);
                handleBusinessError(response.code, response.message);
            } catch (e) {
                handleBusinessError('ERROR', 'Email ou mot de passe incorrect.');
            }
            return;
        }

        if (xhr.status === 422) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    const errors = Object.values(response.errors).flat();
                    showError('danger', 'Formulaire incomplet', errors.join(' '));
                } else {
                    showError('danger', 'Données invalides', response.message || 'Veuillez vérifier vos informations.');
                }
            } catch (e) {
                showError('danger', 'Données invalides', 'Veuillez vérifier vos informations.');
            }
            shakeForm();
            return;
        }

        switch (xhr.status) {
            case 0:
                showError('danger', 'Connexion impossible', 'Impossible de joindre le serveur. Vérifiez votre connexion internet.');
                break;
            case 419:
                toastr.warning('Votre session a expiré.', 'Session expirée');
                Swal.fire({
                    icon: 'warning',
                    title: 'Session expirée',
                    text: 'Votre session a expiré. La page va être actualisée.',
                    confirmButtonColor: '#1c2530',
                    confirmButtonText: 'Actualiser'
                }).then(function() { window.location.reload(); });
                break;
            case 429:
                showError('warning', 'Trop de tentatives', 'Trop de tentatives de connexion. Veuillez patienter.');
                break;
            case 500:
                showError('danger', 'Erreur serveur', "Une erreur interne est survenue. Merci de contacter l'administrateur.");
                break;
            default:
                showError('danger', 'Erreur ' + xhr.status, 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    // ============================================
    // ÉTAT DE CHARGEMENT
    // ============================================

    function setLoading(state) {
        const btn = $('#btn-login');
        const text = $('.btn-text');
        const spinner = $('.btn-spinner');

        if (state) {
            btn.prop('disabled', true);
            text.html('Connexion en cours...');
            spinner.show();
        } else {
            btn.prop('disabled', false);
            text.html('<i class="ti ti-login"></i> Se connecter');
            spinner.hide();
        }
    }

    function setRedirecting() {
        const btn = $('#btn-login');
        const text = $('.btn-text');
        const spinner = $('.btn-spinner');

        btn.prop('disabled', true);
        text.html('<i class="ti ti-check"></i> Redirection...');
        spinner.show();
    }

    function shakeForm() {
        $('.btp-form-panel').addClass('shake');
        setTimeout(function() { $('.btp-form-panel').removeClass('shake'); }, 400);
    }

    // ============================================
    // VÉRIFICATION DE SESSION
    // ============================================

    function checkSession() {
        $.ajax({
            url: '{{ route("check.session") }}',
            type: 'GET',
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                if (data.authenticated) {
                    window.location.href = DASHBOARD_ROUTE;
                }
            },
            error: function() {
                // Pas de session active
            }
        });
    }
</script>

</body>
</html>
