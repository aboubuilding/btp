{{-- resources/views/layouts/partials/_search.blade.php --}}

<style>
    /* ============================================
       SEARCH MODAL — BTP Manager
    ============================================ */
    :root {
        --btps-primary: #0a1628;
        --btps-primary-dark: #060e1a;
        --btps-accent: #d4a745;
        --btps-accent-light: #f0d48a;
        --btps-accent-dark: #b8922e;
        --btps-muted: #6b7a8f;
        --btps-bg: #ffffff;
        --btps-border: #e2e8f0;
        --btps-radius: 16px;
        --btps-radius-sm: 10px;
        --btps-ff: 'Kumbh Sans', sans-serif;
    }

    .search-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        background: rgba(10, 22, 40, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding-top: 10vh;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .search-modal.open {
        opacity: 1;
        visibility: visible;
    }

    .search-modal-content {
        background: var(--btps-bg);
        border-radius: var(--btps-radius);
        max-width: 720px;
        width: 92%;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transform: translateY(-30px) scale(0.96);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s ease;
        opacity: 0;
        border: 1px solid rgba(212, 167, 69, 0.1);
    }

    .search-modal.open .search-modal-content {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    /* ============================================
       HEADER
    ============================================ */
    .search-modal-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--btps-primary-dark), var(--btps-primary));
        border-bottom: 2px solid var(--btps-accent);
    }

    .search-modal-header .search-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(212, 167, 69, 0.15);
        border-radius: var(--btps-radius-sm);
        color: var(--btps-accent);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .search-modal-header .search-icon-wrapper i {
        transition: transform 0.3s ease;
    }

    .search-modal.open .search-icon-wrapper i {
        animation: searchPulse 0.6s ease;
    }

    @keyframes searchPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }

    .search-modal-input {
        flex: 1;
        border: none;
        background: rgba(255, 255, 255, 0.08);
        border-radius: var(--btps-radius-sm);
        font-size: 1.05rem;
        font-weight: 400;
        color: #fff;
        font-family: var(--btps-ff);
        outline: none;
        padding: 12px 18px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .search-modal-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
        font-weight: 300;
        letter-spacing: 0.3px;
    }

    .search-modal-input:focus {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(212, 167, 69, 0.3);
        box-shadow: 0 0 0 4px rgba(212, 167, 69, 0.08);
    }

    .search-modal-shortcut {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 6px;
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.4);
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        flex-shrink: 0;
    }

    .search-modal-shortcut kbd {
        background: rgba(255, 255, 255, 0.12);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.6rem;
        color: rgba(255, 255, 255, 0.6);
        font-family: var(--btps-ff);
    }

    .search-modal-close {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: var(--btps-radius-sm);
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .search-modal-close:hover {
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
        transform: rotate(90deg);
    }

    /* ============================================
       BODY — RÉSULTATS
    ============================================ */
    .search-modal-body {
        padding: 8px 0;
        max-height: 55vh;
        overflow-y: auto;
        background: var(--btps-bg);
    }

    /* Catégories de résultats */
    .search-result-section {
        padding: 8px 20px 4px;
        font-size: 0.65rem;
        text-transform: uppercase;
        color: var(--btps-muted);
        font-weight: 700;
        letter-spacing: 0.8px;
        border-bottom: 1px solid var(--btps-border);
        padding-bottom: 8px;
        margin-bottom: 4px;
    }

    .search-result-section i {
        margin-right: 6px;
        color: var(--btps-accent);
        font-size: 0.6rem;
    }

    /* Élément de résultat */
    .search-result-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f2f5;
        font-family: var(--btps-ff);
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:hover {
        background: #f8f6f0;
        padding-left: 26px;
    }

    .search-result-item:hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--btps-accent);
        border-radius: 0 2px 2px 0;
    }

    .search-result-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--btps-primary-dark), var(--btps-primary));
        border-radius: var(--btps-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--btps-accent);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .search-result-item:hover .search-result-icon {
        transform: scale(1.05);
    }

    .search-result-info {
        flex: 1;
        min-width: 0;
    }

    .search-result-title {
        font-weight: 600;
        font-size: 0.92rem;
        color: var(--btps-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .search-result-title .badge-category {
        background: var(--btps-accent);
        color: #fff;
        font-size: 0.55rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .search-result-title .badge-category.orange {
        background: var(--btps-accent);
    }

    .search-result-title .badge-category.blue {
        background: #2b6cb0;
    }

    .search-result-title .badge-category.green {
        background: #2d8f5e;
    }

    .search-result-title .badge-category.red {
        background: #c0392b;
    }

    .search-result-title .badge-category.purple {
        background: #6b46c1;
    }

    .search-result-title .badge-category.pink {
        background: #d53f8c;
    }

    .search-result-title .badge-category.teal {
        background: #2c7a7b;
    }

    .search-result-sub {
        font-size: 0.78rem;
        color: var(--btps-muted);
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .search-result-sub i {
        color: var(--btps-accent);
        margin-right: 4px;
        font-size: 0.6rem;
    }

    /* ============================================
       ÉTATS VIDE ET CHARGEMENT
    ============================================ */
    .search-empty {
        padding: 48px 20px 40px;
        text-align: center;
        color: var(--btps-muted);
        font-family: var(--btps-ff);
    }

    .search-empty .empty-icon {
        font-size: 3.5rem;
        color: #dce4ea;
        margin-bottom: 16px;
        display: block;
    }

    .search-empty .empty-icon i {
        display: block;
    }

    .search-empty .empty-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--btps-primary);
        margin-bottom: 4px;
    }

    .search-empty .empty-sub {
        font-size: 0.85rem;
        color: var(--btps-muted);
    }

    .search-empty .empty-hint {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        padding: 4px 14px;
        background: #f0f2f5;
        border-radius: 20px;
        font-size: 0.7rem;
        color: var(--btps-muted);
    }

    .search-empty .empty-hint kbd {
        background: var(--btps-primary);
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.6rem;
        font-family: var(--btps-ff);
    }

    /* ============================================
       SCROLLBAR
    ============================================ */
    .search-modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .search-modal-body::-webkit-scrollbar-track {
        background: #f0f2f5;
    }

    .search-modal-body::-webkit-scrollbar-thumb {
        background: var(--btps-accent);
        border-radius: 4px;
    }

    .search-modal-body::-webkit-scrollbar-thumb:hover {
        background: var(--btps-accent-dark);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        .search-modal { padding-top: 6vh; }
        .search-modal-content { width: 96%; border-radius: 14px; }
        .search-modal-header { padding: 14px 16px; gap: 10px; }
        .search-modal-input { font-size: 0.95rem; padding: 10px 14px; }
        .search-modal-shortcut { display: none; }
        .search-modal-close { width: 36px; height: 36px; font-size: 1rem; }
        .search-modal-header .search-icon-wrapper { width: 34px; height: 34px; font-size: 0.9rem; }
        .search-result-item { padding: 10px 14px; gap: 12px; }
        .search-result-icon { width: 36px; height: 36px; font-size: 0.85rem; }
        .search-result-title { font-size: 0.85rem; }
        .search-result-sub { font-size: 0.72rem; }
        .search-result-title .badge-category { font-size: 0.5rem; padding: 1px 8px; }
        .search-empty { padding: 32px 16px; }
        .search-empty .empty-icon { font-size: 2.5rem; }
        .search-empty .empty-title { font-size: 0.95rem; }
    }

    @media (max-width: 480px) {
        .search-modal { padding-top: 4vh; }
        .search-modal-header { padding: 12px 12px; gap: 8px; }
        .search-modal-input { font-size: 0.85rem; padding: 8px 12px; }
        .search-modal-header .search-icon-wrapper { width: 30px; height: 30px; font-size: 0.8rem; }
        .search-modal-close { width: 32px; height: 32px; font-size: 0.85rem; }
        .search-result-item { padding: 8px 12px; gap: 10px; }
        .search-result-icon { width: 30px; height: 30px; font-size: 0.75rem; }
        .search-result-title { font-size: 0.78rem; }
        .search-result-sub { font-size: 0.65rem; }
        .search-result-section { font-size: 0.55rem; padding: 6px 12px 4px; }
        .search-empty .empty-icon { font-size: 2rem; }
        .search-empty .empty-title { font-size: 0.85rem; }
        .search-empty .empty-sub { font-size: 0.75rem; }
    }

    /* ============================================
       DARK THEME SUPPORT
    ============================================ */
    .dark-theme .search-modal {
        background: rgba(0, 0, 0, 0.85);
    }

    .dark-theme .search-modal-content {
        background: #161b22;
        border-color: rgba(212, 167, 69, 0.08);
    }

    .dark-theme .search-modal-body {
        background: #161b22;
    }

    .dark-theme .search-modal-header {
        background: linear-gradient(135deg, #0d1117, #06080a);
    }

    .dark-theme .search-result-item {
        border-bottom-color: #1c2333;
    }

    .dark-theme .search-result-item:hover {
        background: #1c2333;
    }

    .dark-theme .search-result-title {
        color: #e6edf3;
    }

    .dark-theme .search-result-sub {
        color: #8b949e;
    }

    .dark-theme .search-result-icon {
        background: linear-gradient(135deg, #1c2333, #0d1117);
    }

    .dark-theme .search-empty .empty-icon {
        color: #30363d;
    }

    .dark-theme .search-empty .empty-title {
        color: #e6edf3;
    }

    .dark-theme .search-empty .empty-sub {
        color: #8b949e;
    }

    .dark-theme .search-empty .empty-hint {
        background: #1c2333;
        color: #8b949e;
    }

    .dark-theme .search-empty .empty-hint kbd {
        background: #30363d;
        color: #e6edf3;
    }

    .dark-theme .search-result-section {
        color: #8b949e;
        border-bottom-color: #30363d;
    }

    .dark-theme .search-modal-body::-webkit-scrollbar-track {
        background: #0d1117;
    }

    .dark-theme .search-modal-body::-webkit-scrollbar-thumb {
        background: var(--btps-accent-dark);
    }
</style>

<div class="search-modal" id="searchModal" role="dialog" aria-modal="true" aria-label="Recherche">
    <div class="search-modal-content">
        <!-- Header -->
        <div class="search-modal-header">
            <div class="search-icon-wrapper">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" class="search-modal-input" id="searchInput"
                   placeholder="Rechercher un projet, un engin, un employé, une facture..."
                   aria-label="Champ de recherche"
                   autocomplete="off">
            <span class="search-modal-shortcut">
                <kbd>⌘</kbd> <kbd>K</kbd>
            </span>
            <button class="search-modal-close" id="searchModalClose" aria-label="Fermer la recherche">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Résultats -->
        <div class="search-modal-body" id="searchResults">
            <!-- Résultats dynamiques via JavaScript -->
            <div class="search-empty">
                <span class="empty-icon">
                    <i class="fas fa-search-plus"></i>
                </span>
                <div class="empty-title">Recherche rapide</div>
                <div class="empty-sub">Commencez à taper pour trouver ce que vous cherchez</div>
                <div class="empty-hint">
                    <i class="fas fa-arrow-right"></i>
                    <kbd>Ctrl</kbd> + <kbd>K</kbd> pour ouvrir
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        'use strict';

        const modal = document.getElementById('searchModal');
        const closeBtn = document.getElementById('searchModalClose');
        const input = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('searchResults');

        // ============================================
        // DONNÉES DE RECHERCHE
        // ============================================
        const searchData = [
            {
                id: 1,
                title: 'CH-2026-014 — Résidence Les Palmiers',
                subtitle: 'Client : Sotoco Immo · Conducteur : K. Amenyo · En cours (62%)',
                icon: 'fa-diagram-project',
                category: 'Projet',
                categoryColor: 'orange',
                url: '#',
                type: 'projet'
            },
            {
                id: 2,
                title: 'Chargeuse CAT 924K',
                subtitle: 'Code EN-014 · Statut : en service · Chantier : CH-2026-014',
                icon: 'fa-truck',
                category: 'Engin',
                categoryColor: 'blue',
                url: '#',
                type: 'engin'
            },
            {
                id: 3,
                title: 'AMENYO Komi',
                subtitle: 'Matricule EMP-0032 · Conducteur de travaux · Actif',
                icon: 'fa-id-badge',
                category: 'Employé',
                categoryColor: 'green',
                url: '#',
                type: 'employe'
            },
            {
                id: 4,
                title: 'Dépôt central Lomé',
                subtitle: 'Responsable : D. Sossou · 148 articles en stock',
                icon: 'fa-warehouse',
                category: 'Dépôt',
                categoryColor: 'teal',
                url: '#',
                type: 'depot'
            },
            {
                id: 5,
                title: 'Facture n° FAC-2026-041',
                subtitle: 'Client : Sotoco Immo · Montant : 12 500 000 FCFA · Statut : en attente',
                icon: 'fa-file-invoice-dollar',
                category: 'Facture',
                categoryColor: 'red',
                url: '#',
                type: 'facture'
            },
            {
                id: 6,
                title: 'Bon de commande BC-2026-018',
                subtitle: 'Fournisseur : CIMTOGO · Montant : 3 200 000 FCFA · Statut : confirmée',
                icon: 'fa-file-invoice',
                category: 'Achat',
                categoryColor: 'purple',
                url: '#',
                type: 'achat'
            },
            {
                id: 7,
                title: 'Sous-traitant : Elec Pro Togo',
                subtitle: 'Spécialité : Électricité · Note : 4.5/5 · Statut : actif',
                icon: 'fa-handshake',
                category: 'Sous-traitant',
                categoryColor: 'pink',
                url: '#',
                type: 'sous-traitant'
            },
            {
                id: 8,
                title: 'Ciment CEM II 42.5 — sac 50kg',
                subtitle: 'Catégorie : Ciment & liants · Stock : 320 sacs · Seuil alerte : 100',
                icon: 'fa-cubes',
                category: 'Matériau',
                categoryColor: 'orange',
                url: '#',
                type: 'materiau'
            },
            {
                id: 9,
                title: 'Période de paie — Mars 2026',
                subtitle: 'Statut : Ouvert · 45 employés · Total : 18 750 000 FCFA',
                icon: 'fa-calendar-week',
                category: 'Paie',
                categoryColor: 'green',
                url: '#',
                type: 'paie'
            },
            {
                id: 10,
                title: 'Client : BTP Moderne SARL',
                subtitle: '5 projets en cours · Total facturé : 85 000 000 FCFA',
                icon: 'fa-address-card',
                category: 'Client',
                categoryColor: 'blue',
                url: '#',
                type: 'client'
            }
        ];

        // ============================================
        // FONCTIONS DE RECHERCHE
        // ============================================

        function search(query) {
            if (!query || query.trim() === '') {
                return [];
            }

            const q = query.toLowerCase().trim();
            return searchData.filter(item => {
                return item.title.toLowerCase().includes(q) ||
                    item.subtitle.toLowerCase().includes(q) ||
                    item.category.toLowerCase().includes(q);
            });
        }

        function renderResults(results) {
            if (results.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="search-empty">
                        <span class="empty-icon">
                            <i class="fas fa-search-minus"></i>
                        </span>
                        <div class="empty-title">Aucun résultat</div>
                        <div class="empty-sub">Aucun élément ne correspond à votre recherche</div>
                        <div class="empty-hint">
                            <i class="fas fa-lightbulb"></i>
                            Essayez avec d'autres mots-clés
                        </div>
                    </div>
                `;
                return;
            }

            // Grouper par catégorie
            const grouped = {};
            results.forEach(item => {
                if (!grouped[item.category]) {
                    grouped[item.category] = [];
                }
                grouped[item.category].push(item);
            });

            let html = '';
            Object.keys(grouped).forEach(category => {
                html += `
                    <div class="search-result-section">
                        <i class="fas fa-folder"></i> ${category}
                    </div>
                `;
                grouped[category].forEach(item => {
                    html += `
                        <a href="${item.url}" class="search-result-item" tabindex="0">
                            <div class="search-result-icon"><i class="fas ${item.icon}"></i></div>
                            <div class="search-result-info">
                                <div class="search-result-title">
                                    ${item.title}
                                    <span class="badge-category ${item.categoryColor || 'orange'}">${item.category}</span>
                                </div>
                                <div class="search-result-sub">
                                    <i class="fas fa-circle"></i> ${item.subtitle}
                                </div>
                            </div>
                        </a>
                    `;
                });
            });

            resultsContainer.innerHTML = html;
        }

        // ============================================
        // GESTION DES ÉVÉNEMENTS
        // ============================================

        // Recherche en temps réel
        let searchTimeout;
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const results = search(this.value);
                renderResults(results);
            }, 250);
        });

        // Raccourcis clavier
        document.addEventListener('keydown', function(e) {
            // Ctrl+K ou Cmd+K pour ouvrir
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (modal.classList.contains('open')) {
                    closeModal();
                } else {
                    openModal();
                }
            }
            // Escape pour fermer
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });

        // Fermer avec le bouton
        closeBtn.addEventListener('click', closeModal);

        // Cliquer à l'extérieur pour fermer
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Navigation clavier dans les résultats
        document.addEventListener('keydown', function(e) {
            if (!modal.classList.contains('open')) return;

            const items = document.querySelectorAll('.search-result-item');
            if (items.length === 0) return;

            let currentIndex = -1;
            items.forEach((item, index) => {
                if (item === document.activeElement) {
                    currentIndex = index;
                }
            });

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = Math.min(currentIndex + 1, items.length - 1);
                if (nextIndex >= 0) {
                    items[nextIndex].focus();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = Math.max(currentIndex - 1, 0);
                if (prevIndex >= 0) {
                    items[prevIndex].focus();
                }
            } else if (e.key === 'Enter' && currentIndex >= 0) {
                e.preventDefault();
                items[currentIndex].click();
            }
        });

        // ============================================
        // FONCTIONS D'OUVERTURE/FERMETURE
        // ============================================

        function openModal() {
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                input.focus();
                input.select();
            }, 150);

            // Réinitialiser les résultats
            resultsContainer.innerHTML = `
                <div class="search-empty">
                    <span class="empty-icon">
                        <i class="fas fa-search-plus"></i>
                    </span>
                    <div class="empty-title">Recherche rapide</div>
                    <div class="empty-sub">Commencez à taper pour trouver ce que vous cherchez</div>
                    <div class="empty-hint">
                        <i class="fas fa-arrow-right"></i>
                        <kbd>Ctrl</kbd> + <kbd>K</kbd> pour ouvrir
                    </div>
                </div>
            `;
            input.value = '';
        }

        function closeModal() {
            modal.classList.remove('open');
            document.body.style.overflow = '';
            input.blur();
        }

        // ============================================
        // EXPOSITION DES FONCTIONS GLOBALES
        // ============================================

        window.openSearchModal = openModal;
        window.closeSearchModal = closeModal;

        // ============================================
        // LIEN AVEC LE BOUTON DE RECHERCHE DU HEADER
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.querySelector('[data-search-toggle]');
            if (searchBtn) {
                searchBtn.addEventListener('click', openModal);
            }
        });

        console.log('🔍 Recherche BTP Manager prête');

    })();
</script>
