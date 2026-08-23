{{-- resources/views/layouts/partials/_header.blade.php --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap');

    :root {
        --btp-primary: #0a1628;
        --btp-primary-dark: #060e1a;
        --btp-secondary: #1a3a5c;
        --btp-accent: #d4a745;
        --btp-accent-light: #f0d48a;
        --btp-accent-dark: #b8922e;
        --btp-danger: #c0392b;
        --btp-success: #2d8f5e;
        --btp-warning: #b7950b;
        --btp-info: #2b6cb0;
        --btp-muted: #6b7a8f;
        --btp-bg: #f0f2f5;
        --btp-card-bg: #ffffff;
        --btp-border: #e2e8f0;
        --btp-shadow: 0 2px 12px rgba(10, 22, 40, 0.08);
        --btp-shadow-hover: 0 8px 30px rgba(10, 22, 40, 0.12);
        --btp-radius: 10px;
        --btp-radius-lg: 16px;
        --btp-transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hbtp-root {
        font-family: 'Kumbh Sans', sans-serif;
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
        background: var(--btp-primary);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
    }

    /* ============================================
       TOP BAR
    ============================================ */
    .hbtp-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 0 24px;
        height: 60px;
        background: linear-gradient(135deg, var(--btp-primary-dark), var(--btp-primary));
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .hbtp-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        flex-shrink: 0;
        transition: opacity var(--btp-transition);
    }
    .hbtp-brand:hover { opacity: 0.85; }

    .hbtp-brand-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--btp-radius);
        background: linear-gradient(145deg, var(--btp-accent-light), var(--btp-accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--btp-primary);
        box-shadow: 0 4px 14px rgba(212, 167, 69, 0.3);
        transition: transform var(--btp-transition), box-shadow var(--btp-transition);
    }
    .hbtp-brand:hover .hbtp-brand-icon {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(212, 167, 69, 0.4);
    }

    .hbtp-brand-title {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.3px;
        line-height: 1.1;
    }
    .hbtp-brand-sub {
        font-size: 9px;
        color: rgba(255, 255, 255, 0.45);
        letter-spacing: 1.2px;
        text-transform: uppercase;
        font-weight: 500;
        display: block;
        margin-top: 2px;
    }

    .hbtp-top-right {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    /* ============================================
       BOUTONS ICONES
    ============================================ */
    .hbtp-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: var(--btp-radius);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        transition: all var(--btp-transition);
        position: relative;
        text-decoration: none;
    }
    .hbtp-icon-btn:hover {
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
        transform: translateY(-1px);
    }
    .hbtp-icon-btn:active {
        transform: scale(0.95);
    }

    .hbtp-notif-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 8px;
        height: 8px;
        background: var(--btp-danger);
        border-radius: 50%;
        border: 2px solid var(--btp-primary);
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0% { box-shadow: 0 0 0 0 rgba(192, 57, 43, 0.5); }
        70% { box-shadow: 0 0 0 6px rgba(192, 57, 43, 0); }
        100% { box-shadow: 0 0 0 0 rgba(192, 57, 43, 0); }
    }

    .hbtp-sep {
        width: 1px;
        height: 28px;
        background: rgba(255, 255, 255, 0.08);
        margin: 0 4px;
        flex-shrink: 0;
    }

    /* ============================================
       SELECTEUR EXERCICE
    ============================================ */
    .hbtp-year-wrap {
        position: relative;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .hbtp-year-wrap i {
        position: absolute;
        left: 12px;
        font-size: 13px;
        color: var(--btp-accent-light);
        pointer-events: none;
    }
    .hbtp-year-select {
        appearance: none;
        -webkit-appearance: none;
        height: 38px;
        padding: 0 32px 0 36px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--btp-radius);
        color: #fff;
        font-family: 'Kumbh Sans', sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--btp-transition);
        min-width: 150px;
    }
    .hbtp-year-select:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(212, 167, 69, 0.3);
    }
    .hbtp-year-select:focus {
        outline: none;
        border-color: var(--btp-accent);
        box-shadow: 0 0 0 3px rgba(212, 167, 69, 0.15);
    }
    .hbtp-year-select option {
        background: var(--btp-primary);
        color: #fff;
    }
    .hbtp-year-wrap::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        width: 8px;
        height: 8px;
        border-right: 2px solid rgba(255, 255, 255, 0.5);
        border-bottom: 2px solid rgba(255, 255, 255, 0.5);
        transform: translateY(-60%) rotate(45deg);
        pointer-events: none;
    }
    .hbtp-year-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 10px;
        height: 10px;
        background: var(--btp-success);
        border: 2px solid var(--btp-primary);
        border-radius: 50%;
    }

    @media (max-width: 900px) {
        .hbtp-year-select { min-width: 110px; font-size: 11px; padding-left: 30px; }
    }
    @media (max-width: 560px) {
        .hbtp-year-wrap { display: none; }
    }

    /* ============================================
       AVATAR
    ============================================ */
    .hbtp-avatar-wrap {
        position: relative;
    }
    .hbtp-avatar-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 12px 0 6px;
        height: 38px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--btp-radius);
        cursor: pointer;
        transition: all var(--btp-transition);
    }
    .hbtp-avatar-btn:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    .hbtp-avatar-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(145deg, var(--btp-accent-light), var(--btp-accent));
        color: var(--btp-primary);
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    }

    .hbtp-avatar-name {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        line-height: 1;
    }
    .hbtp-avatar-role {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.4);
        line-height: 1;
        margin-top: 2px;
        display: block;
        font-weight: 400;
    }
    .hbtp-avatar-caret {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.4);
        margin-left: 2px;
        transition: transform var(--btp-transition);
    }
    .hbtp-avatar-wrap.open .hbtp-avatar-caret {
        transform: rotate(180deg);
    }

    /* ============================================
       DROPDOWN UTILISATEUR
    ============================================ */
    .hbtp-user-drop {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 250px;
        background: var(--btp-card-bg);
        border-radius: var(--btp-radius-lg);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border: 1px solid var(--btp-border);
        display: none;
        z-index: 9999;
        overflow: hidden;
        animation: dropIn 0.2s ease-out;
    }
    .hbtp-avatar-wrap.open .hbtp-user-drop {
        display: block;
    }

    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hbtp-udrop-header {
        padding: 16px 18px;
        background: linear-gradient(135deg, #f8f6f0, #f0ece4);
        border-bottom: 1px solid var(--btp-border);
    }
    .hbtp-udrop-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--btp-primary);
    }
    .hbtp-udrop-email {
        font-size: 11.5px;
        color: var(--btp-muted);
        margin-top: 2px;
    }
    .hbtp-udrop-role {
        display: inline-block;
        margin-top: 6px;
        background: var(--btp-accent);
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .hbtp-udrop-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 18px;
        font-size: 13px;
        color: var(--btp-primary);
        text-decoration: none;
        transition: all var(--btp-transition);
        cursor: pointer;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        font-family: 'Kumbh Sans', sans-serif;
    }
    .hbtp-udrop-item:hover {
        background: #f8f6f0;
        padding-left: 24px;
    }
    .hbtp-udrop-item i {
        font-size: 15px;
        color: var(--btp-accent-dark);
        width: 18px;
        text-align: center;
    }
    .hbtp-udrop-item.danger {
        color: var(--btp-danger);
    }
    .hbtp-udrop-item.danger i {
        color: var(--btp-danger);
    }
    .hbtp-udrop-item.danger:hover {
        background: #fdf2f2;
    }
    .hbtp-udrop-div {
        height: 1px;
        background: var(--btp-border);
        margin: 4px 0;
    }

    /* ============================================
       NAV BAR
    ============================================ */
    .hbtp-nav {
        display: flex;
        align-items: center;
        padding: 0 24px;
        height: 48px;
        background: rgba(255, 255, 255, 0.03);
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        overflow: visible;
    }

    .hbtp-nav-items {
        display: flex;
        align-items: center;
        gap: 2px;
        flex: 1;
        min-width: 0;
        height: 100%;
    }

    /* ============================================
       NAV ITEMS
    ============================================ */
    .hnav-item {
        position: relative;
        display: flex;
        align-items: center;
        height: 100%;
        flex-shrink: 0;
    }

    .hnav-trigger {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 16px;
        height: 100%;
        color: rgba(255, 255, 255, 0.75);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        background: transparent;
        border: none;
        font-family: 'Kumbh Sans', sans-serif;
        transition: all var(--btp-transition);
        position: relative;
        white-space: nowrap;
    }
    .hnav-trigger i {
        font-size: 14px;
    }
    .hnav-trigger .caret {
        font-size: 10px;
        opacity: 0.6;
        transition: transform var(--btp-transition);
    }
    .hnav-item.open .hnav-trigger .caret {
        transform: rotate(180deg);
    }

    .hnav-trigger::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 60%;
        height: 3px;
        background: var(--btp-accent);
        border-radius: 3px 3px 0 0;
        transition: transform var(--btp-transition);
    }
    .hnav-item:hover .hnav-trigger::after,
    .hnav-item.open .hnav-trigger::after,
    .hnav-item.active .hnav-trigger::after {
        transform: translateX(-50%) scaleX(1);
    }

    .hnav-item:hover .hnav-trigger,
    .hnav-item.open .hnav-trigger {
        color: #fff;
        background: rgba(255, 255, 255, 0.06);
    }
    .hnav-item.active .hnav-trigger {
        color: var(--btp-accent-light);
        background: rgba(212, 167, 69, 0.08);
    }

    .hnav-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 6px;
        border-radius: 20px;
        background: var(--btp-danger);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        line-height: 1;
        margin-left: 2px;
    }

    /* ============================================
       DROPDOWN NAV
    ============================================ */
    .hnav-drop {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 260px;
        background: var(--btp-card-bg);
        border-radius: 0 0 var(--btp-radius-lg) var(--btp-radius-lg);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--btp-border);
        border-top: 3px solid var(--btp-accent);
        z-index: 99999;
        padding: 6px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-4px);
        transition: all var(--btp-transition);
        pointer-events: none;
    }
    .hnav-item.open .hnav-drop {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    .hnav-drop-title {
        padding: 8px 16px 4px;
        font-size: 10px;
        text-transform: uppercase;
        color: var(--btp-muted);
        letter-spacing: 0.8px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .hnav-drop-title i {
        font-size: 11px;
        color: var(--btp-accent);
    }

    .hnav-drop-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 500;
        color: var(--btp-primary);
        text-decoration: none;
        transition: all var(--btp-transition);
        border-left: 3px solid transparent;
        font-family: 'Kumbh Sans', sans-serif;
        cursor: pointer;
        background: transparent;
        border-right: none;
        border-top: none;
        border-bottom: none;
        width: 100%;
        text-align: left;
    }
    .hnav-drop-item:hover {
        background: #f8f6f0;
        padding-left: 22px;
        border-left-color: var(--btp-accent);
    }
    .hnav-drop-item i {
        font-size: 14px;
        color: var(--btp-muted);
        width: 18px;
        text-align: center;
        flex-shrink: 0;
    }
    .hnav-drop-item .hnav-mini-badge {
        margin-left: auto;
        font-size: 10px;
        font-weight: 800;
        color: #fff;
        background: var(--btp-danger);
        border-radius: 20px;
        padding: 1px 8px;
    }
    .hnav-drop-item.urgent i {
        color: var(--btp-danger);
    }
    .hnav-drop-item.urgent:hover {
        border-left-color: var(--btp-danger);
    }

    .hnav-drop-div {
        height: 1px;
        background: var(--btp-border);
        margin: 4px 0;
    }

    /* ============================================
       HAMBURGER
    ============================================ */
    .hbtp-hamburger {
        display: none;
        flex-direction: column;
        gap: 4px;
        justify-content: center;
        width: 38px;
        height: 38px;
        cursor: pointer;
        padding: 8px;
        border-radius: var(--btp-radius);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        flex-shrink: 0;
        transition: all var(--btp-transition);
    }
    .hbtp-hamburger:hover {
        background: rgba(255, 255, 255, 0.12);
    }
    .hbtp-hamburger span {
        display: block;
        width: 100%;
        height: 2px;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 2px;
        transition: all var(--btp-transition);
    }
    .hbtp-hamburger.open span:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
    }
    .hbtp-hamburger.open span:nth-child(2) {
        opacity: 0;
    }
    .hbtp-hamburger.open span:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1100px) {
        .hnav-trigger { font-size: 12px; padding: 0 12px; }
        .hbtp-brand-title { font-size: 17px; }
    }

    @media (max-width: 768px) {
        .hbtp-hamburger { display: flex; }
        .hbtp-brand-sub { display: none; }
        .hbtp-nav {
            height: 0;
            overflow: hidden;
            flex-direction: column;
            padding: 0;
            transition: height 0.3s ease;
            background: var(--btp-primary-dark);
        }
        .hbtp-nav.mobile-open {
            height: auto;
            padding: 4px 0 12px;
            overflow: visible;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .hbtp-nav-items {
            flex-direction: column;
            align-items: stretch;
            gap: 0;
            height: auto;
        }

        .hnav-item {
            flex-direction: column;
            height: auto;
        }
        .hnav-trigger {
            padding: 12px 16px;
            justify-content: space-between;
            height: auto;
            width: 100%;
        }
        .hnav-trigger::after { display: none; }

        .hnav-drop {
            position: static;
            box-shadow: none;
            border: none;
            border-top: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 0;
            background: rgba(0, 0, 0, 0.15);
            transform: none !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: none;
            padding: 4px 0;
        }
        .hnav-item.open .hnav-drop {
            display: block;
        }

        .hnav-drop-item {
            color: rgba(255, 255, 255, 0.9);
            border-left-color: transparent !important;
            padding: 10px 16px 10px 24px;
        }
        .hnav-drop-item:hover {
            background: rgba(0, 0, 0, 0.2);
            padding-left: 30px;
        }
        .hnav-drop-item i {
            color: rgba(255, 255, 255, 0.6);
        }
        .hnav-drop-title {
            color: rgba(255, 255, 255, 0.4);
        }
        .hnav-drop-title i {
            color: var(--btp-accent-light);
        }
        .hnav-drop-div {
            background: rgba(255, 255, 255, 0.06);
        }
        .hnav-item.active .hnav-trigger {
            background: rgba(212, 167, 69, 0.12);
        }

        .hbtp-user-drop {
            right: -10px;
            width: 220px;
        }
        .hbtp-avatar-name { font-size: 12px; }
        .hbtp-avatar-role { font-size: 9px; }
    }

    @media (max-width: 480px) {
        .hbtp-top { padding: 0 12px; gap: 8px; height: 54px; }
        .hbtp-brand-title { font-size: 15px; }
        .hbtp-brand-icon { width: 34px; height: 34px; font-size: 15px; }
        .hbtp-avatar-name, .hbtp-avatar-role { display: none; }
        .hbtp-avatar-btn { padding: 0 6px; }
        .hbtp-icon-btn { width: 34px; height: 34px; font-size: 13px; }
        .hbtp-nav { padding: 0 12px; }
        .hbtp-sep { margin: 0 2px; }
    }

    /* ============================================
       SCROLLBAR
    ============================================ */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--btp-accent); border-radius: 4px; }

    /* ============================================
       DARK THEME SUPPORT
    ============================================ */
    .dark-theme {
        --btp-primary: #0d1117;
        --btp-primary-dark: #06080a;
        --btp-card-bg: #161b22;
        --btp-bg: #0d1117;
        --btp-border: #30363d;
        --btp-muted: #8b949e;
        --btp-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        --btp-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.5);
    }
    .dark-theme .hbtp-udrop-header {
        background: linear-gradient(135deg, #1c2333, #161b22);
    }
    .dark-theme .hbtp-udrop-name { color: #fff; }
    .dark-theme .hbtp-udrop-email { color: #8b949e; }
    .dark-theme .hbtp-udrop-item { color: #e6edf3; }
    .dark-theme .hbtp-udrop-item:hover { background: #1c2333; }
    .dark-theme .hbtp-udrop-item i { color: var(--btp-accent); }
    .dark-theme .hbtp-udrop-div { background: #30363d; }
    .dark-theme .hbtp-udrop-item.danger { color: #f85149; }
    .dark-theme .hbtp-udrop-item.danger i { color: #f85149; }
    .dark-theme .hbtp-udrop-item.danger:hover { background: #2d1b1e; }
    .dark-theme .hnav-drop { background: #161b22; border-color: #30363d; }
    .dark-theme .hnav-drop-item { color: #e6edf3; }
    .dark-theme .hnav-drop-item:hover { background: #1c2333; }
    .dark-theme .hnav-drop-item i { color: #8b949e; }
    .dark-theme .hnav-drop-title { color: #8b949e; }
    .dark-theme .hnav-drop-div { background: #30363d; }
    .dark-theme .hbtp-user-drop { background: #161b22; border-color: #30363d; }
</style>

{{-- ============================================
     HEADER BTP MANAGER
     ============================================ --}}

<div class="hbtp-root" id="header-top" role="banner">
    <!-- TOP BAR -->
    <div class="hbtp-top">
        <a href="#" class="hbtp-brand" title="Accueil BTP Manager">
            <div class="hbtp-brand-icon"><i class="fas fa-helmet-safety"></i></div>
            <div>
                <span class="hbtp-brand-title">BTP Manager</span>
                <span class="hbtp-brand-sub">Gestion de chantiers</span>
            </div>
        </a>

        <div class="hbtp-top-right">
            {{-- Sélecteur d'exercice comptable --}}
            <div class="hbtp-year-wrap" title="Changer d'exercice comptable">
                <i class="fas fa-calendar-alt"></i>
                <select name="exercice_id" id="select-exercice-comptable" class="hbtp-year-select">
                    <option value="1" selected>Exercice 2025</option>
                    <option value="2">Exercice 2024</option>
                    <option value="3">Exercice 2023</option>
                </select>
                <span class="hbtp-year-badge" title="Exercice en cours"></span>
            </div>

            <div class="hbtp-sep"></div>

            {{-- Bouton Hamburger --}}
            <button class="hbtp-hamburger" id="hbtp-hamburger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="hbtp-main-nav">
                <span></span><span></span><span></span>
            </button>

            {{-- Thème --}}
            <button class="hbtp-icon-btn" id="themeToggle" aria-label="Basculer le mode sombre">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>

            {{-- Recherche --}}
            <button class="hbtp-icon-btn" id="btnSearch" title="Recherche (Ctrl+K)" data-search-toggle>
                <i class="fas fa-search"></i>
            </button>

            {{-- Notifications --}}
            <a href="#" class="hbtp-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="hbtp-notif-dot"></span>
            </a>

            <div class="hbtp-sep"></div>

            {{-- Avatar utilisateur --}}
            <div class="hbtp-avatar-wrap" id="hbtp-avatar-wrap">
                <div class="hbtp-avatar-btn" id="hbtp-avatar-btn" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
                    <div class="hbtp-avatar-circle">
                        {{ strtoupper(substr($headerNomComplet ?? 'AD', 0, 2)) }}
                    </div>
                    <div>
                        <span class="hbtp-avatar-name">{{ $headerNomComplet ?? 'Utilisateur' }}</span>
                        <span class="hbtp-avatar-role">{{ $headerRoleLabel ?? 'Rôle' }}</span>
                    </div>
                    <i class="fas fa-chevron-down hbtp-avatar-caret"></i>
                </div>

                {{-- Dropdown utilisateur --}}
                <div class="hbtp-user-drop" id="hbtp-user-drop" role="menu">
                    <div class="hbtp-udrop-header">
                        <div class="hbtp-udrop-name">{{ $headerNomComplet ?? 'Nom Prénom' }}</div>
                        <div class="hbtp-udrop-email">{{ $headerUserEmail ?? 'utilisateur@btp-manager.tg' }}</div>
                        <div class="hbtp-udrop-role">{{ $headerRoleLabel ?? 'Rôle' }}</div>
                    </div>
                    <a href="#" class="hbtp-udrop-item" role="menuitem">
                        <i class="fas fa-user-circle"></i> Mon profil
                    </a>
                    <a href="#" class="hbtp-udrop-item" role="menuitem">
                        <i class="fas fa-key"></i> Changer mot de passe
                    </a>
                    <div class="hbtp-udrop-div"></div>
                    <form action="#" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="hbtp-udrop-item danger" role="menuitem" style="width:100%; border:none; background:transparent; text-align:left; cursor:pointer;">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- NAV BAR -->
    <nav class="hbtp-nav" id="hbtp-main-nav" aria-label="Navigation principale">
        <div class="hbtp-nav-items" id="hbtp-nav-items">

            {{-- 1. Tableau de bord --}}
            <div class="hnav-item active">
                <a href="#" class="hnav-trigger">
                    <i class="fas fa-gauge-high"></i> Tableau de bord
                </a>
            </div>

            {{-- 2. Projets --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-diagram-project"></i> Projets <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <div class="hnav-drop-title"><i class="fas fa-list-check"></i> Chantiers</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-list-ul"></i> Liste des projets</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-calendar-days"></i> Planification</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-flag-checkered"></i> Jalons</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-chart-line"></i> Suivi &amp; pilotage</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-chart-line"></i> Suivi d'avancement</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-user-group"></i> Équipe affectée</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-sack-dollar"></i> Budget du projet</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-folder-open"></i> Documents</a>
                </div>
            </div>

            {{-- 3. Engins --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-truck"></i> Engins <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <a href="#" class="hnav-drop-item"><i class="fas fa-truck"></i> Liste des engins</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-map-location-dot"></i> Affectations</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-screwdriver-wrench"></i> Maintenance</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-triangle-exclamation"></i> Pannes</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-gas-pump"></i> Suivi carburant</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-lines"></i> Documents</a>
                </div>
            </div>

            {{-- 4. Stock & Achats --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-boxes-stacked"></i> Stock &amp; Achats
                    <span class="hnav-badge">{{ $headerAlertesStock ?? 4 }}</span>
                    <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <div class="hnav-drop-title"><i class="fas fa-warehouse"></i> Stock</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-warehouse"></i> Dépôts</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-cubes"></i> Catalogue matériaux</a>
                    <a href="#" class="hnav-drop-item urgent">
                        <i class="fas fa-layer-group"></i> Niveaux de stock
                        <span class="hnav-mini-badge">{{ $headerAlertesStock ?? 4 }}</span>
                    </a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-right-left"></i> Mouvements</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-truck-ramp-box"></i> Transferts</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-clipboard-list"></i> Inventaires</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-cart-shopping"></i> Achats</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-circle-plus"></i> Demandes d'achat</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-invoice"></i> Bons de commande</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-dolly"></i> Livraisons</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-industry"></i> Fournisseurs</a>
                </div>
            </div>

            {{-- 5. RH --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-users"></i> RH <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <div class="hnav-drop-title"><i class="fas fa-id-badge"></i> Personnel</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-id-badge"></i> Employés</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-signature"></i> Contrats</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-sitemap"></i> Départements</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-clock"></i> Suivi</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-umbrella-beach"></i> Congés</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-folder"></i> Documents</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-clock"></i> Pointage</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-money-check-dollar"></i> Paie</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-calendar-week"></i> Périodes</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-invoice-dollar"></i> Bulletins</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-money-check-dollar"></i> Avances</a>
                </div>
            </div>

            {{-- 6. Comptabilité --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-calculator"></i> Comptabilité <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <div class="hnav-drop-title"><i class="fas fa-book"></i> Générale</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-book"></i> Plan comptable</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-pen-to-square"></i> Écritures</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-calendar-alt"></i> Exercices</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-file-invoice-dollar"></i> Facturation &amp; Trésorerie</div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-credit-card"></i> Paiements</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-money-bill-wave"></i> Dépenses</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-building-columns"></i> Comptes bancaires</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-cash-register"></i> Caisses</a>
                </div>
            </div>

            {{-- 7. Sous-traitants --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-handshake"></i> Sous-traitants <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <a href="#" class="hnav-drop-item"><i class="fas fa-address-book"></i> Liste</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-contract"></i> Contrats</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-file-invoice"></i> Factures</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-money-bill-transfer"></i> Paiements</a>
                    <div class="hnav-drop-div"></div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-star-half-stroke"></i> Évaluations</a>
                </div>
            </div>

            {{-- 8. Administration --}}
            <div class="hnav-item">
                <a href="#" class="hnav-trigger" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-gear"></i> Administration <i class="fas fa-chevron-down caret"></i>
                </a>
                <div class="hnav-drop" role="menu">
                    <a href="#" class="hnav-drop-item"><i class="fas fa-users-cog"></i> Utilisateurs</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-user-shield"></i> Rôles &amp; permissions</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-address-card"></i> Clients</a>
                    <div class="hnav-drop-div"></div>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-sliders-h"></i> Paramètres</a>
                    <a href="#" class="hnav-drop-item"><i class="fas fa-history"></i> Journal d'activité</a>
                </div>
            </div>

        </div>
    </nav>
</div>

<script>
    (function () {
        'use strict';

        var navItems = document.getElementById('hbtp-nav-items');
        var isMobile = function () { return window.innerWidth <= 768; };

        function closeAll() {
            document.querySelectorAll('.hnav-item.open').forEach(function (el) {
                el.classList.remove('open');
                var t = el.querySelector(':scope > .hnav-trigger');
                if (t) t.setAttribute('aria-expanded', 'false');
            });
        }

        function openItem(item) {
            item.classList.add('open');
            var t = item.querySelector(':scope > .hnav-trigger');
            if (t) t.setAttribute('aria-expanded', 'true');
        }

        function bindItem(item) {
            if (item._btp) return;
            item._btp = true;
            var trigger = item.querySelector(':scope > .hnav-trigger');
            if (!trigger) return;

            item.addEventListener('mouseenter', function () {
                if (isMobile()) return;
                closeAll();
                openItem(item);
            });
            item.addEventListener('mouseleave', function () {
                if (isMobile()) return;
                item.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            });

            trigger.addEventListener('click', function (e) {
                if (!isMobile()) return;
                e.preventDefault();
                var was = item.classList.contains('open');
                closeAll();
                if (!was) openItem(item);
            });
        }

        function bindAll() {
            document.querySelectorAll('#hbtp-nav-items > .hnav-item').forEach(bindItem);
        }

        /* ===== AVATAR ===== */
        var aWrap = document.getElementById('hbtp-avatar-wrap');
        var aBtn = document.getElementById('hbtp-avatar-btn');
        if (aWrap && aBtn) {
            aWrap.addEventListener('mouseenter', function () {
                aWrap.classList.add('open');
                aBtn.setAttribute('aria-expanded', 'true');
            });
            aWrap.addEventListener('mouseleave', function () {
                aWrap.classList.remove('open');
                aBtn.setAttribute('aria-expanded', 'false');
            });
            aBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var o = aWrap.classList.toggle('open');
                aBtn.setAttribute('aria-expanded', String(o));
            });
            document.addEventListener('click', function (e) {
                if (!aWrap.contains(e.target)) {
                    aWrap.classList.remove('open');
                    aBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        /* ===== HAMBURGER ===== */
        var hbg = document.getElementById('hbtp-hamburger');
        var nav = document.getElementById('hbtp-main-nav');
        if (hbg && nav) {
            hbg.addEventListener('click', function () {
                var o = nav.classList.toggle('mobile-open');
                hbg.classList.toggle('open', o);
                hbg.setAttribute('aria-expanded', String(o));
                if (o) {
                    closeAll();
                }
            });
        }

        /* ===== THÈME ===== */
        var tBtn = document.getElementById('themeToggle');
        var tIcon = document.getElementById('themeIcon');

        function applyTheme(dark) {
            document.documentElement.classList.toggle('dark-theme', dark);
            if (tIcon) tIcon.className = dark ? 'fas fa-sun' : 'fas fa-moon';
            try { localStorage.setItem('btp-theme', dark ? 'dark' : 'light'); } catch (e) {}
        }

        if (tBtn) {
            try {
                var sv = localStorage.getItem('btp-theme');
                if (sv) applyTheme(sv === 'dark');
            } catch (e) {}
            tBtn.addEventListener('click', function () {
                applyTheme(!document.documentElement.classList.contains('dark-theme'));
            });
        }

        /* ===== CTRL+K RECHERCHE ===== */
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                var b = document.getElementById('btnSearch');
                if (b) b.click();
            }
        });

        /* ===== INIT ===== */
        bindAll();

        console.log('✅ Header BTP Manager chargé');
        console.log('👤 Utilisateur:', '{{ $headerNomComplet ?? "Non connecté" }}');

    })();
</script>
