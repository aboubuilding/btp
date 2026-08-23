{{-- resources/views/layouts/partials/_footer.blade.php --}}

<style>
    /* ============================================
       FOOTER BTP MANAGER
    ============================================ */
    :root {
        --btpf-primary: #0a1628;
        --btpf-primary-dark: #060e1a;
        --btpf-secondary: #1a3a5c;
        --btpf-accent: #d4a745;
        --btpf-accent-light: #f0d48a;
        --btpf-accent-dark: #b8922e;
        --btpf-muted: #6b7a8f;
        --btpf-border: rgba(255, 255, 255, 0.06);
    }

    .btp-footer {
        font-family: 'Kumbh Sans', sans-serif;
        background: linear-gradient(135deg, var(--btpf-primary-dark), var(--btpf-primary));
        color: rgba(255, 255, 255, 0.8);
        border-top: 2px solid var(--btpf-accent);
        padding: 24px 32px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        font-size: 0.85rem;
        margin-top: auto;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    /* Ligne décorative en haut */
    .btp-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg,
        transparent 0%,
        var(--btpf-accent) 20%,
        var(--btpf-accent-light) 50%,
        var(--btpf-accent) 80%,
        transparent 100%
        );
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    /* Motif de fond subtil */
    .btp-footer::after {
        content: '🏗️';
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 120px;
        opacity: 0.04;
        transform: rotate(-8deg);
        pointer-events: none;
    }

    .btp-footer .footer-left {
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 1;
        position: relative;
    }

    .btp-footer .footer-left .brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btp-footer .footer-left .brand-icon {
        width: 32px;
        height: 32px;
        background: rgba(212, 167, 69, 0.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: var(--btpf-accent);
        border: 1px solid rgba(212, 167, 69, 0.2);
    }

    .btp-footer .footer-left strong {
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.3px;
    }

    .btp-footer .footer-left strong span {
        color: var(--btpf-accent);
    }

    .btp-footer .footer-left .subtitle {
        font-size: 0.7rem;
        opacity: 0.5;
        font-weight: 300;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btp-footer .footer-left .copyright {
        font-size: 0.75rem;
        opacity: 0.6;
        font-weight: 400;
    }

    .btp-footer .footer-left .copyright i {
        color: var(--btpf-accent);
        margin-right: 4px;
    }

    .btp-footer .footer-right {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        z-index: 1;
        position: relative;
    }

    .btp-footer .footer-right .footer-link {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 6px;
        position: relative;
        padding: 4px 0;
    }

    .btp-footer .footer-right .footer-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--btpf-accent);
        transition: width 0.3s ease;
    }

    .btp-footer .footer-right .footer-link:hover {
        color: var(--btpf-accent-light);
        transform: translateY(-2px);
    }

    .btp-footer .footer-right .footer-link:hover::after {
        width: 100%;
    }

    .btp-footer .footer-right .footer-link i {
        font-size: 13px;
        transition: transform 0.3s ease;
    }

    .btp-footer .footer-right .footer-link:hover i {
        transform: scale(1.15);
    }

    .btp-footer .footer-right .separator {
        color: rgba(255, 255, 255, 0.1);
        font-size: 0.6rem;
        user-select: none;
    }

    .btp-footer .footer-right .version-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(212, 167, 69, 0.12);
        color: var(--btpf-accent-light);
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(212, 167, 69, 0.15);
        letter-spacing: 0.5px;
    }

    .btp-footer .footer-right .version-badge i {
        font-size: 11px;
    }

    .btp-footer .footer-right .social-links {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 4px;
    }

    .btp-footer .footer-right .social-links a {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 13px;
    }

    .btp-footer .footer-right .social-links a:hover {
        background: rgba(212, 167, 69, 0.15);
        border-color: var(--btpf-accent);
        color: var(--btpf-accent-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 167, 69, 0.15);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        .btp-footer {
            flex-direction: column;
            text-align: center;
            padding: 20px 24px;
        }

        .btp-footer .footer-left {
            align-items: center;
        }

        .btp-footer .footer-left .brand {
            justify-content: center;
        }

        .btp-footer .footer-right {
            justify-content: center;
            gap: 12px;
        }

        .btp-footer .footer-right .separator {
            display: none;
        }

        .btp-footer .footer-right .social-links {
            margin-left: 0;
        }

        .btp-footer::after {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .btp-footer {
            padding: 16px 16px;
        }

        .btp-footer .footer-right {
            flex-direction: column;
            gap: 8px;
        }

        .btp-footer .footer-right .footer-link {
            font-size: 0.78rem;
        }

        .btp-footer .footer-left .brand strong {
            font-size: 0.9rem;
        }

        .btp-footer .footer-left .brand-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .btp-footer .footer-right .version-badge {
            font-size: 0.65rem;
            padding: 3px 12px;
        }
    }

    /* ============================================
       ANIMATION D'APPARITION
    ============================================ */
    .btp-footer {
        animation: footerSlideUp 0.6s ease-out;
    }

    @keyframes footerSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================
       DARK THEME SUPPORT
    ============================================ */
    .dark-theme .btp-footer {
        background: linear-gradient(135deg, #0d1117, #06080a);
        border-top-color: var(--btpf-accent-dark);
    }

    .dark-theme .btp-footer::before {
        background: linear-gradient(90deg,
        transparent 0%,
        var(--btpf-accent-dark) 20%,
        var(--btpf-accent) 50%,
        var(--btpf-accent-dark) 80%,
        transparent 100%
        );
    }

    .dark-theme .btp-footer .footer-right .footer-link {
        color: rgba(255, 255, 255, 0.5);
    }

    .dark-theme .btp-footer .footer-right .footer-link:hover {
        color: var(--btpf-accent-light);
    }

    .dark-theme .btp-footer .footer-right .version-badge {
        background: rgba(212, 167, 69, 0.08);
        border-color: rgba(212, 167, 69, 0.1);
    }

    .dark-theme .btp-footer .footer-right .social-links a {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.04);
    }

    .dark-theme .btp-footer .footer-right .social-links a:hover {
        background: rgba(212, 167, 69, 0.12);
        border-color: var(--btpf-accent-dark);
        color: var(--btpf-accent);
    }
</style>

<footer class="btp-footer" id="app-footer">
    <!-- Partie gauche -->
    <div class="footer-left">
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-helmet-safety"></i>
            </div>
            <strong>BTP <span>Manager</span></strong>
        </div>
        <div class="subtitle">Plateforme de gestion de chantiers et de ressources BTP</div>
        <div class="copyright">
            <i class="far fa-copyright"></i> {{ date('Y') }} BTP Manager — Tous droits réservés
        </div>
    </div>

    <!-- Partie droite -->
    <div class="footer-right">
        <a href="#" class="footer-link" title="Accueil">
            <i class="fas fa-home"></i> Accueil
        </a>

        <span class="separator">|</span>

        <a href="#" class="footer-link" title="Documentation">
            <i class="fas fa-book"></i> Documentation
        </a>

        <span class="separator">|</span>

        <a href="#" class="footer-link" title="Support">
            <i class="fas fa-headset"></i> Support
        </a>

        <span class="separator">|</span>

        <a href="#" class="footer-link" title="Mentions légales">
            <i class="fas fa-gavel"></i> Mentions
        </a>

        <span class="separator">|</span>

        <div class="version-badge">
            <i class="fas fa-code-branch"></i> v1.0.0
        </div>

        <span class="separator">|</span>

        <!-- Réseaux sociaux -->
        <div class="social-links">
            <a href="#" title="GitHub" aria-label="GitHub">
                <i class="fab fa-github"></i>
            </a>
            <a href="#" title="LinkedIn" aria-label="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="#" title="Twitter" aria-label="Twitter">
                <i class="fab fa-x-twitter"></i>
            </a>
            <a href="#" title="YouTube" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </div>
</footer>

{{-- Scripts pour le footer --}}
@push('js')
    <script>
        (function() {
            'use strict';

            // Animation d'entrée
            const footer = document.getElementById('app-footer');
            if (footer) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            footer.style.animation = 'footerSlideUp 0.6s ease-out';
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(footer);

                // Gestion du clic sur les liens
                footer.querySelectorAll('.footer-link, .social-links a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.getAttribute('href') === '#') {
                            e.preventDefault();
                            // Animation de feedback
                            this.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 200);
                        }
                    });
                });
            }

            // Mise à jour automatique de l'année
            const copyright = document.querySelector('.copyright');
            if (copyright) {
                const year = new Date().getFullYear();
                copyright.innerHTML = `<i class="far fa-copyright"></i> ${year} BTP Manager — Tous droits réservés`;
            }

            console.log('✅ Footer BTP Manager chargé');

        })();
    </script>
@endpush
