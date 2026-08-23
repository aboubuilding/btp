{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('page_title', 'Tableau de bord')
@section('page_icon', 'fa-gauge-high')

@section('breadcrumb')
    <li class="active">Tableau de bord</li>
@endsection

@section('page_actions')
    <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
        <i class="fas fa-sync-alt"></i> Rafraîchir
    </button>
    <button class="btn btn-sm btn-outline-primary" id="btnExport">
        <i class="fas fa-file-export"></i> Exporter
    </button>
@endsection

@section('css')
    <style>
        /* ============================================
           DASHBOARD BTP MANAGER
        ============================================ */
        :root {
            --db-primary: #0a1628;
            --db-primary-dark: #060e1a;
            --db-accent: #d4a745;
            --db-accent-light: #f0d48a;
            --db-accent-dark: #b8922e;
            --db-success: #2d8f5e;
            --db-danger: #c0392b;
            --db-warning: #b7950b;
            --db-info: #2b6cb0;
            --db-muted: #6b7a8f;
            --db-bg: #f0f2f5;
            --db-card-bg: #ffffff;
            --db-border: #e2e8f0;
            --db-radius: 12px;
            --db-radius-lg: 16px;
            --db-shadow: 0 2px 12px rgba(10, 22, 40, 0.06);
            --db-shadow-hover: 0 8px 30px rgba(10, 22, 40, 0.1);
            --db-transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ============================================
           STATS CARDS
        ============================================ */
        .stat-card {
            background: var(--db-card-bg);
            border-radius: var(--db-radius-lg);
            padding: 20px 24px;
            box-shadow: var(--db-shadow);
            transition: all var(--db-transition);
            border: 1px solid var(--db-border);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            box-shadow: var(--db-shadow-hover);
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--db-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-card .stat-icon.primary { background: rgba(10, 22, 40, 0.08); color: var(--db-primary); }
        .stat-card .stat-icon.success { background: rgba(45, 143, 94, 0.1); color: var(--db-success); }
        .stat-card .stat-icon.danger { background: rgba(192, 57, 43, 0.1); color: var(--db-danger); }
        .stat-card .stat-icon.warning { background: rgba(183, 149, 11, 0.1); color: var(--db-warning); }
        .stat-card .stat-icon.info { background: rgba(43, 108, 176, 0.1); color: var(--db-info); }
        .stat-card .stat-icon.accent { background: rgba(212, 167, 69, 0.12); color: var(--db-accent); }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--db-primary);
            line-height: 1.2;
            font-family: 'Kumbh Sans', sans-serif;
        }

        .stat-card .stat-number .currency {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--db-muted);
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--db-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .stat-card .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            margin-top: 6px;
        }

        .stat-card .stat-change.up { background: rgba(45, 143, 94, 0.1); color: var(--db-success); }
        .stat-card .stat-change.down { background: rgba(192, 57, 43, 0.1); color: var(--db-danger); }
        .stat-card .stat-change.neutral { background: rgba(107, 122, 143, 0.1); color: var(--db-muted); }

        .stat-card .stat-decoration {
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 80px;
            opacity: 0.04;
            pointer-events: none;
        }

        /* ============================================
           PROGRESS BAR
        ============================================ */
        .progress-btp {
            height: 6px;
            border-radius: 3px;
            background: var(--db-border);
            overflow: hidden;
        }

        .progress-btp .progress-bar {
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .progress-btp .progress-bar.success { background: var(--db-success); }
        .progress-btp .progress-bar.warning { background: var(--db-warning); }
        .progress-btp .progress-bar.danger { background: var(--db-danger); }
        .progress-btp .progress-bar.info { background: var(--db-info); }
        .progress-btp .progress-bar.accent { background: var(--db-accent); }

        /* ============================================
           SECTION CARDS
        ============================================ */
        .section-card {
            background: var(--db-card-bg);
            border-radius: var(--db-radius-lg);
            box-shadow: var(--db-shadow);
            border: 1px solid var(--db-border);
            overflow: hidden;
            transition: all var(--db-transition);
        }

        .section-card:hover {
            box-shadow: var(--db-shadow-hover);
        }

        .section-card .section-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--db-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-card .section-header .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--db-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .section-card .section-header .section-title i {
            color: var(--db-accent);
            font-size: 1rem;
        }

        .section-card .section-header .section-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-card .section-body {
            padding: 20px 24px;
        }

        /* ============================================
           LIST ITEMS
        ============================================ */
        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--db-border);
            gap: 12px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item .item-info {
            flex: 1;
            min-width: 0;
        }

        .list-item .item-info .item-title {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--db-primary);
        }

        .list-item .item-info .item-sub {
            font-size: 0.78rem;
            color: var(--db-muted);
            margin-top: 1px;
        }

        .list-item .item-badge {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            flex-shrink: 0;
        }

        .list-item .item-badge.success { background: rgba(45, 143, 94, 0.1); color: var(--db-success); }
        .list-item .item-badge.danger { background: rgba(192, 57, 43, 0.1); color: var(--db-danger); }
        .list-item .item-badge.warning { background: rgba(183, 149, 11, 0.1); color: var(--db-warning); }
        .list-item .item-badge.info { background: rgba(43, 108, 176, 0.1); color: var(--db-info); }
        .list-item .item-badge.primary { background: rgba(10, 22, 40, 0.08); color: var(--db-primary); }
        .list-item .item-badge.accent { background: rgba(212, 167, 69, 0.12); color: var(--db-accent-dark); }

        /* ============================================
           ALERTES STOCK
        ============================================ */
        .stock-alert-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--db-radius);
            background: #fef2f2;
            border: 1px solid #fecaca;
            margin-bottom: 8px;
        }

        .stock-alert-item:last-child {
            margin-bottom: 0;
        }

        .stock-alert-item .alert-icon {
            width: 32px;
            height: 32px;
            background: rgba(192, 57, 43, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--db-danger);
            flex-shrink: 0;
        }

        .stock-alert-item .alert-info {
            flex: 1;
        }

        .stock-alert-item .alert-info .alert-title {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--db-primary);
        }

        .stock-alert-item .alert-info .alert-sub {
            font-size: 0.75rem;
            color: var(--db-muted);
        }

        .stock-alert-item .alert-quantity {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--db-danger);
            flex-shrink: 0;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 768px) {
            .stat-card .stat-number {
                font-size: 1.4rem;
            }

            .section-card .section-header {
                padding: 14px 16px;
            }

            .section-card .section-body {
                padding: 14px 16px;
            }

            .stat-card {
                padding: 16px 18px;
            }

            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .stat-card .stat-number {
                font-size: 1.2rem;
            }

            .list-item {
                flex-wrap: wrap;
            }

            .list-item .item-badge {
                font-size: 0.6rem;
                padding: 2px 10px;
            }

            .stock-alert-item {
                flex-wrap: wrap;
            }
        }

        /* ============================================
           DARK THEME
        ============================================ */
        .dark-theme .stat-card,
        .dark-theme .section-card {
            background: #161b22;
            border-color: #30363d;
        }

        .dark-theme .stat-card .stat-number {
            color: #e6edf3;
        }

        .dark-theme .stat-card .stat-label {
            color: #8b949e;
        }

        .dark-theme .stat-card .stat-icon.primary {
            background: rgba(255, 255, 255, 0.05);
            color: #e6edf3;
        }

        .dark-theme .section-card .section-header .section-title {
            color: #e6edf3;
        }

        .dark-theme .section-card .section-header {
            border-color: #30363d;
        }

        .dark-theme .list-item {
            border-color: #30363d;
        }

        .dark-theme .list-item .item-info .item-title {
            color: #e6edf3;
        }

        .dark-theme .list-item .item-info .item-sub {
            color: #8b949e;
        }

        .dark-theme .stock-alert-item {
            background: #2d1b1e;
            border-color: #5c2d30;
        }

        .dark-theme .stock-alert-item .alert-info .alert-title {
            color: #e6edf3;
        }

        .dark-theme .stock-alert-item .alert-info .alert-sub {
            color: #8b949e;
        }

        .dark-theme .stat-card .stat-decoration {
            opacity: 0.02;
        }
    </style>
@endsection

@section('contenu')

    <div class="dashboard-container">

        {{-- ============================================
            1. STATISTIQUES GÉNÉRALES
        ============================================ --}}
        <div class="row g-4 mb-4">

            {{-- Projets --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-number">{{ $projetsStats['actifs'] ?? 0 }}</div>
                            <div class="stat-label">Projets actifs</div>
                            <div class="stat-change {{ ($projetsStats['en_retard'] ?? 0) > 0 ? 'down' : 'neutral' }}">
                                <i class="fas fa-{{ ($projetsStats['en_retard'] ?? 0) > 0 ? 'arrow-down' : 'minus' }}"></i>
                                {{ $projetsStats['en_retard'] ?? 0 }} en retard
                            </div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="fas fa-diagram-project"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Planifiés: {{ $projetsStats['planifies'] ?? 0 }}</span>
                            <span>Avancement: {{ $projetsStats['avancement_moyen'] ?? 0 }}%</span>
                        </div>
                        <div class="progress-btp mt-1">
                            <div class="progress-bar accent" style="width: {{ $projetsStats['avancement_moyen'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <div class="stat-decoration">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>

            {{-- Budget --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-number">
                                {{ number_format(($budgetStats['total'] ?? 0) / 1000000, 1, ',', ' ') }}
                                <span class="currency">M FCFA</span>
                            </div>
                            <div class="stat-label">Budget total</div>
                            <div class="stat-change {{ ($budgetStats['taux_consommation'] ?? 0) > 80 ? 'danger' : 'up' }}">
                                <i class="fas fa-{{ ($budgetStats['taux_consommation'] ?? 0) > 80 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ $budgetStats['taux_consommation'] ?? 0 }}% consommé
                            </div>
                        </div>
                        <div class="stat-icon success">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Engagé: {{ number_format(($budgetStats['engage'] ?? 0) / 1000000, 1, ',', ' ') }}M</span>
                            <span>Disponible: {{ number_format(($budgetStats['disponible'] ?? 0) / 1000000, 1, ',', ' ') }}M</span>
                        </div>
                        <div class="progress-btp mt-1">
                            <div class="progress-bar {{ ($budgetStats['taux_consommation'] ?? 0) > 80 ? 'danger' : 'success' }}"
                                 style="width: {{ $budgetStats['taux_consommation'] ?? 0 }}%;">
                            </div>
                        </div>
                    </div>
                    <div class="stat-decoration">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>

            {{-- Engins --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-number">{{ ($enginsStats['en_service'] ?? 0) + ($enginsStats['disponibles'] ?? 0) }}</div>
                            <div class="stat-label">Engins disponibles</div>
                            <div class="stat-change {{ ($enginsStats['en_panne'] ?? 0) > 0 ? 'danger' : 'success' }}">
                                <i class="fas fa-{{ ($enginsStats['en_panne'] ?? 0) > 0 ? 'exclamation-triangle' : 'check' }}"></i>
                                {{ $enginsStats['en_panne'] ?? 0 }} en panne
                            </div>
                        </div>
                        <div class="stat-icon info">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>En maintenance: {{ $enginsStats['en_maintenance'] ?? 0 }}</span>
                            <span>Dispo: {{ $enginsStats['taux_disponibilite'] ?? 0 }}%</span>
                        </div>
                        <div class="progress-btp mt-1">
                            <div class="progress-bar {{ ($enginsStats['taux_disponibilite'] ?? 0) > 70 ? 'success' : 'warning' }}"
                                 style="width: {{ $enginsStats['taux_disponibilite'] ?? 0 }}%;">
                            </div>
                        </div>
                    </div>
                    <div class="stat-decoration">
                        <i class="fas fa-tractor"></i>
                    </div>
                </div>
            </div>

            {{-- Factures --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-number text-{{ ($facturesRetard['total_retard'] ?? 0) > 0 ? 'danger' : 'success' }}">
                                {{ $facturesRetard['total_retard'] ?? 0 }}
                            </div>
                            <div class="stat-label">Factures en retard</div>
                            <div class="stat-change {{ ($facturesRetard['total_retard'] ?? 0) > 0 ? 'danger' : 'success' }}">
                                <i class="fas fa-{{ ($facturesRetard['total_retard'] ?? 0) > 0 ? 'clock' : 'check-circle' }}"></i>
                                {{ number_format($facturesRetard['montant_total_retard'] ?? 0, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="stat-icon danger">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>{{ $facturesRetard['clients_concernes'] ?? 0 }} clients</span>
                            <span>{{ $facturesRetard['total_proches'] ?? 0 }} échéances proches</span>
                        </div>
                    </div>
                    <div class="stat-decoration">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================
            2. DEUXIÈME LIGNE : STOCK + PAie
        ============================================ --}}
        <div class="row g-4 mb-4">

            {{-- Alertes Stock --}}
            <div class="col-lg-6">
                <div class="section-card h-100">
                    <div class="section-header">
                        <h6 class="section-title">
                            <i class="fas fa-boxes-stacked"></i>
                            Alertes stock
                            @if(($stockAlertes['critique'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $stockAlertes['critique'] }}</span>
                            @endif
                        </h6>
                        <div class="section-actions">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                        </div>
                    </div>
                    <div class="section-body">
                        @if(($stockAlertes['critique'] ?? 0) > 0)
                            {{-- Ruptures --}}
                            @if(!empty($stockAlertes['ruptures']))
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-danger">Rupture</span>
                                        <small class="text-muted">{{ count($stockAlertes['ruptures']) }} matériaux</small>
                                    </div>
                                    @foreach(array_slice($stockAlertes['ruptures'], 0, 3) as $rupture)
                                        <div class="stock-alert-item">
                                            <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
                                            <div class="alert-info">
                                                <div class="alert-title">{{ $rupture->materiau_nom }}</div>
                                                <div class="alert-sub">{{ $rupture->entrepot_nom }}</div>
                                            </div>
                                            <div class="alert-quantity">0 {{ $rupture->unite }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Alertes --}}
                            @if(!empty($stockAlertes['alertes']))
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-warning text-dark">Sous seuil</span>
                                        <small class="text-muted">{{ count($stockAlertes['alertes']) }} matériaux</small>
                                    </div>
                                    @foreach(array_slice($stockAlertes['alertes'], 0, 3) as $alerte)
                                        <div class="list-item">
                                            <div class="item-info">
                                                <div class="item-title">{{ $alerte->materiau_nom }}</div>
                                                <div class="item-sub">{{ $alerte->entrepot_nom }} · Seuil: {{ $alerte->seuil_alerte_stock_min }} {{ $alerte->unite }}</div>
                                            </div>
                                            <span class="item-badge warning">{{ $alerte->quantite }} {{ $alerte->unite }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                                <p class="text-muted mt-2">Aucune alerte stock</p>
                                <small class="text-muted">Tous les stocks sont au-dessus des seuils</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Échéances Paie --}}
            <div class="col-lg-6">
                <div class="section-card h-100">
                    <div class="section-header">
                        <h6 class="section-title">
                            <i class="fas fa-money-check-dollar"></i>
                            Échéances de paie
                        </h6>
                        <div class="section-actions">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="stat-card" style="box-shadow: none; border: 1px solid var(--db-border);">
                                    <div class="stat-number" style="font-size: 1.4rem;">{{ $echeancesPaie['employes_a_payer'] ?? 0 }}</div>
                                    <div class="stat-label">Employés à payer</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card" style="box-shadow: none; border: 1px solid var(--db-border);">
                                    <div class="stat-number" style="font-size: 1.4rem;">
                                        {{ number_format(($echeancesPaie['total_salaires'] ?? 0) / 1000000, 1, ',', ' ') }}
                                        <span class="currency" style="font-size: 0.7rem;">M FCFA</span>
                                    </div>
                                    <div class="stat-label">Total salaires</div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($echeancesPaie['echeances']))
                            @foreach(array_slice($echeancesPaie['echeances'], 0, 3) as $echeance)
                                <div class="list-item">
                                    <div class="item-info">
                                        <div class="item-title">{{ $echeance->nom }}</div>
                                        <div class="item-sub">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($echeance->date_fin)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <span class="item-badge warning">
                                    J-{{ \Carbon\Carbon::parse($echeance->date_fin)->diffInDays(\Carbon\Carbon::now()) }}
                                </span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Aucune échéance imminente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================
            3. PROJETS RÉCENTS
        ============================================ --}}
        <div class="row g-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-header">
                        <h6 class="section-title">
                            <i class="fas fa-clock"></i>
                            Projets récents
                        </h6>
                        <div class="section-actions">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                        </div>
                    </div>
                    <div class="section-body">
                        @if(!empty($recentProjects))
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" style="margin: 0;">
                                    <thead>
                                    <tr>
                                        <th>Projet</th>
                                        <th>Client</th>
                                        <th>Montant</th>
                                        <th>Avancement</th>
                                        <th>Statut</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentProjects as $project)
                                        <tr>
                                            <td>
                                                <strong>{{ $project->nom ?? 'Sans nom' }}</strong>
                                                <div class="small text-muted">{{ $project->code ?? '' }}</div>
                                            </td>
                                            <td>{{ $project->client_nom ?? 'Non défini' }}</td>
                                            <td>{{ number_format($project->montant_contrat ?? 0, 0, ',', ' ') }} FCFA</td>
                                            <td style="min-width: 120px;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress-btp flex-grow-1" style="width: 100px;">
                                                        <div class="progress-bar {{ ($project->pourcentage_avancement ?? 0) >= 80 ? 'success' : (($project->pourcentage_avancement ?? 0) >= 50 ? 'warning' : 'info') }}"
                                                             style="width: {{ $project->pourcentage_avancement ?? 0 }}%;">
                                                        </div>
                                                    </div>
                                                    <span class="small fw-bold">{{ $project->pourcentage_avancement ?? 0 }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="item-badge {{ $project->statut ?? 'primary' }}">
                                                    {{ $project->statut ?? 'Planifié' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">Aucun projet récent</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {

            // ============================================
            // RAFRAÎCHISSEMENT AUTOMATIQUE
            // ============================================
            let refreshInterval = null;

            function startAutoRefresh() {
                if (refreshInterval) clearInterval(refreshInterval);
                refreshInterval = setInterval(function() {
                    $.ajax({
                        url: '{{ route("dashboard.refresh") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Données actualisées', 'Mise à jour');
                                // Mettre à jour les données si nécessaire
                            }
                        },
                        error: function() {
                            // Silencieux pour éviter les erreurs en boucle
                        }
                    });
                }, 300000); // 5 minutes
            }

            startAutoRefresh();

            // ============================================
            // EXPORT
            // ============================================
            $('#btnExport').on('click', function() {
                toastr.info('Export en cours de préparation...', 'Export');
                // Simuler un export
                setTimeout(function() {
                    toastr.success('Export terminé', 'Export');
                }, 1500);
            });

            // ============================================
            // ANIMATION DES CARTES
            // ============================================
            $('.stat-card, .section-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.05) + 's');
            });

            // ============================================
            // TOOLTIP POUR LES STATS
            // ============================================
            $('.stat-card .stat-number').on('mouseenter', function() {
                $(this).closest('.stat-card').css('transform', 'scale(1.01)');
            }).on('mouseleave', function() {
                $(this).closest('.stat-card').css('transform', '');
            });

            console.log('✅ Dashboard BTP Manager chargé');
            console.log('📊 Statistiques chargées:', {
                projets: {{ $projetsStats['actifs'] ?? 0 }},
                budget: '{{ number_format(($budgetStats['total'] ?? 0) / 1000000, 1) }}M',
                employes: {{ $echeancesPaie['employes_a_payer'] ?? 0 }},
                factures_retard: {{ $facturesRetard['total_retard'] ?? 0 }}
            });

        });
    </script>
@endsection
