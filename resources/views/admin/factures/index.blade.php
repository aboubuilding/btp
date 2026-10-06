{{-- resources/views/admin/factures/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Factures')

@section('page_title', 'Factures')
@section('page_icon', 'fa-file-invoice')

@section('breadcrumb')
    <li class="active">Factures</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddFacture">
        <i class="fas fa-plus"></i> Nouvelle facture
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .facture-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            flex-shrink: 0;
        }
        .facture-avatar.client { background: linear-gradient(135deg, #2b6cb0, #1a4d7a); }
        .facture-avatar.fournisseur { background: linear-gradient(135deg, #c0392b, #9b2c2c); }

        .statut-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .statut-badge.primary { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .statut-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .statut-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .statut-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }
        .statut-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }

        .type-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            background: rgba(212, 167, 69, 0.12);
            color: #b8922e;
        }

        .table-actions .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
        }
        .table-actions .btn-action:hover {
            transform: translateY(-2px);
        }
        .table-actions .btn-action.btn-edit { color: #2b6cb0; }
        .table-actions .btn-action.btn-edit:hover { background: rgba(43, 108, 176, 0.08); border-color: #2b6cb0; }
        .table-actions .btn-action.btn-pay { color: #2d8f5e; }
        .table-actions .btn-action.btn-pay:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }
        .table-actions .btn-action.btn-cancel { color: #c0392b; }
        .table-actions .btn-action.btn-cancel:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-send { color: #d4a745; }
        .table-actions .btn-action.btn-send:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }
        .table-actions .btn-action.btn-remind { color: #6b46c1; }
        .table-actions .btn-action.btn-remind:hover { background: rgba(107, 70, 193, 0.08); border-color: #6b46c1; }
        .table-actions .btn-action.btn-delete { color: #6b7a8f; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(107, 122, 143, 0.08); border-color: #6b7a8f; }
        .table-actions .btn-action.btn-restore { color: #6b46c1; }
        .table-actions .btn-action.btn-restore:hover { background: rgba(107, 70, 193, 0.08); border-color: #6b46c1; }

        .search-box {
            position: relative;
            max-width: 300px;
        }
        .search-box input {
            padding-right: 40px;
            border-radius: var(--btp-radius);
            border: 1px solid var(--btp-border);
            height: 38px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .search-box input:focus {
            border-color: var(--btp-accent);
            box-shadow: 0 0 0 3px rgba(212, 167, 69, 0.12);
            outline: none;
        }
        .search-box .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--btp-muted);
            pointer-events: none;
        }

        .stat-card-mini {
            background: var(--btp-card-bg);
            border-radius: var(--btp-radius);
            padding: 12px 16px;
            border: 1px solid var(--btp-border);
            text-align: center;
        }
        .stat-card-mini .stat-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--btp-primary);
        }
        .stat-card-mini .stat-number.green { color: #2d8f5e; }
        .stat-card-mini .stat-number.red { color: #c0392b; }
        .stat-card-mini .stat-number.orange { color: #b7950b; }
        .stat-card-mini .stat-number.accent { color: #d4a745; }
        .stat-card-mini .stat-label {
            font-size: 0.7rem;
            color: var(--btp-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-backdrop {
            background-color: rgba(10, 22, 40, 0.5);
        }

        .facture-retard {
            animation: blink-red 1.5s ease-in-out infinite;
        }

        @keyframes blink-red {
            0%, 100% { background-color: transparent; }
            50% { background-color: rgba(192, 57, 43, 0.05); }
        }

        @media (max-width: 768px) {
            .search-box {
                max-width: 100%;
                width: 100%;
            }
            .stat-card-mini .stat-number {
                font-size: 1.2rem;
            }
        }
    </style>
@endsection

@section('contenu')

    {{-- Statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number red">{{ $stats['en_retard'] ?? 0 }}</div>
                <div class="stat-label">En retard</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number orange">{{ $stats['echeances_proches'] ?? 0 }}</div>
                <div class="stat-label">Échéances proches</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number accent">
                    {{ number_format(($stats['total_ttc'] ?? 0) / 1000000, 1, ',', ' ') }}M
                </div>
                <div class="stat-label">Total TTC</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number green">
                    {{ number_format(($stats['total_ht'] ?? 0) / 1000000, 1, ',', ' ') }}M
                </div>
                <div class="stat-label">Total HT</div>
            </div>
        </div>
    </div>

    {{-- Liste --}}
    <div class="row">
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center gap-3 flex-wrap" style="width: 100%;">
                        <h6 class="section-title mb-0">
                            <i class="fas fa-file-invoice"></i>
                            Liste des factures
                            <span class="badge bg-secondary ms-2" id="factureCount">{{ count($factures) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterType" style="width: auto; height: 38px;">
                                    <option value="all">Tous les types</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select form-select-sm" id="filterStatut" style="width: auto; height: 38px;">
                                    <option value="all">Tous les statuts</option>
                                    @foreach($statuts as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="facturesTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Numéro</th>
                                <th>Tiers</th>
                                <th>Type</th>
                                <th>Projet</th>
                                <th>Montant TTC</th>
                                <th>Échéance</th>
                                <th>Statut</th>
                                <th style="width: 220px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="facturesTableBody">
                            @forelse($factures as $facture)
                                <tr data-id="{{ $facture['id'] }}"
                                    data-type="{{ $facture['type'] }}"
                                    data-statut="{{ $facture['statut'] }}"
                                    class="{{ $facture['statut'] == 'en_retard' || ($facture['statut'] != 'payee' && $facture['statut'] != 'annulee' && $facture['date_echeance'] && \Carbon\Carbon::parse($facture['date_echeance'])->isPast()) ? 'facture-retard' : '' }}">
                                    <td>
                                        <div class="facture-avatar {{ $facture['type'] }}">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $facture['numero_facture'] }}</div>
                                        <small class="text-muted">{{ $facture['date_facture_formatted'] }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $facture['facturable_name'] ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $facture['type_facturable_label'] }}</small>
                                    </td>
                                    <td>
                                        <span class="type-badge">{{ $facture['type_label'] }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $facture['projet']['nom'] ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $facture['projet']['code'] ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $facture['montant_ttc_formatted'] }}</div>
                                        <small class="text-muted">HT: {{ $facture['montant_ht_formatted'] }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $facture['date_echeance_formatted'] }}</div>
                                        @if($facture['statut'] != 'payee' && $facture['statut'] != 'annulee' && $facture['date_echeance'] && \Carbon\Carbon::parse($facture['date_echeance'])->isPast())
                                            <span class="badge bg-danger">
                                                Retard: {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($facture['date_echeance'])) }}j
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="statut-badge {{ $facture['statut_badge'] }}">
                                            {{ $facture['statut_label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editFacture({{ $facture['id'] }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            @if($facture['statut'] != 'payee' && $facture['statut'] != 'annulee')
                                                <button type="button" class="btn-action btn-pay" title="Marquer payée" onclick="marquerPayee({{ $facture['id'] }})">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-cancel" title="Annuler" onclick="annulerFacture({{ $facture['id'] }})">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-remind" title="Relancer" onclick="relancerFacture({{ $facture['id'] }})">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn-action btn-send" title="Envoyer" onclick="envoyerFacture({{ $facture['id'] }})">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                            @if($facture['statut'] == 'brouillon')
                                                <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteFacture({{ $facture['id'] }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-file-invoice fa-2x d-block mb-2"></i>
                                        Aucune facture trouvée.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE AJOUT / ÉDITION
    ============================================ --}}
    <div class="modal fade" id="factureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="factureModalTitle">
                        <i class="fas fa-file-invoice me-2"></i>
                        Nouvelle facture
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="factureForm">
                    @csrf
                    <input type="hidden" name="facture_id" id="factureId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="editType" class="form-select" required>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Type de tiers <span class="text-danger">*</span></label>
                                    <select name="type_facturable" id="editTypeFacturable" class="form-select" required>
                                        @foreach($typesFacturable as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tiers <span class="text-danger">*</span></label>
                                    <select name="id_facturable" id="editTiers" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" data-type="Client">{{ $client->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Numéro de facture <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="numero_facture" id="editNumero" class="form-control" required>
                                        <button type="button" class="btn btn-outline-secondary" id="btnGenerateNumero">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Projet</label>
                                    <select name="projet_id" id="editProjet" class="form-select">
                                        <option value="">Aucun projet</option>
                                        @foreach($projets as $projet)
                                            <option value="{{ $projet->id }}">{{ $projet->code }} - {{ $projet->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de facture <span class="text-danger">*</span></label>
                                    <input type="date" name="date_facture" id="editDateFacture" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'échéance</label>
                                    <input type="date" name="date_echeance" id="editDateEcheance" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Montant HT (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="montant_ht" id="editMontantHt" class="form-control" min="0" step="100" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">TVA (%)</label>
                                    <input type="number" name="tva" id="editTva" class="form-control" min="0" max="100" step="0.5">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Montant TTC</label>
                                    <input type="text" id="editMontantTtc" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" id="editStatut" class="form-select">
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let factureModal;

        $(document).ready(function() {
            factureModal = new bootstrap.Modal(document.getElementById('factureModal'));

            // Bouton Ajouter
            $('#btnAddFacture').on('click', function() {
                resetForm();
                $('#factureModalTitle').html('<i class="fas fa-file-invoice me-2"></i>Nouvelle facture');
                $('#formMethod').val('POST');
                $('#factureForm').attr('action', '{{ route("admin.factures.store") }}');
                $('#btnSubmit').html('<i class="fas fa-save"></i> Enregistrer');
                factureModal.show();
                generateNumero();
            });

            // Générer numéro
            $('#btnGenerateNumero').on('click', function() {
                generateNumero();
            });

            // Calcul automatique du TTC
            $('#editMontantHt, #editTva').on('input', function() {
                calculerTtc();
            });

            // Changement de type facturable
            $('#editTypeFacturable').on('change', function() {
                chargerTiers();
            });

            // Formulaire
            $('#factureForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...').prop('disabled', true);

                const formData = $(this).serialize();
                const action = $(this).attr('action');
                const method = $('#formMethod').val();

                let url = action;
                let type = 'POST';

                if (method === 'PUT') {
                    const id = $('#factureId').val();
                    url = '{{ route("admin.factures.update", "") }}/' + id;
                    type = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            factureModal.hide();
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            Object.values(errors).forEach(err => {
                                toastr.error(err[0]);
                            });
                        } else {
                            toastr.error('Erreur lors de l\'enregistrement');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-save"></i> Enregistrer').prop('disabled', false);
                    }
                });
            });

            // Recherche
            $('#searchInput').on('input', function() {
                filterTable();
            });

            // Filtres
            $('#filterType, #filterStatut').on('change', function() {
                filterTable();
            });

            // Rafraîchir
            $('#btnRefresh').on('click', function() {
                window.location.reload();
            });
        });

        function filterTable() {
            const search = $('#searchInput').val().toLowerCase();
            const type = $('#filterType').val();
            const statut = $('#filterStatut').val();

            $('#facturesTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowType = row.data('type');
                const rowStatut = row.data('statut');

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (type !== 'all' && rowType !== type) {
                    show = false;
                }

                if (statut !== 'all' && rowStatut !== statut) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#factureCount').text($('#facturesTableBody tr:visible').length);
        }

        function resetForm() {
            $('#factureForm')[0].reset();
            $('#factureId').val('');
            $('#editMontantTtc').val('');
        }

        function calculerTtc() {
            const ht = parseFloat($('#editMontantHt').val()) || 0;
            const tva = parseFloat($('#editTva').val()) || 0;
            const ttc = ht + (ht * tva / 100);
            $('#editMontantTtc').val(numberFormat(ttc) + ' FCFA');
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('fr-FR').format(number);
        }

        function generateNumero() {
            const type = $('#editType').val();
            $.ajax({
                url: '{{ route("admin.factures.generate-numero") }}',
                type: 'GET',
                data: { type: type },
                success: function(response) {
                    if (response.success) {
                        $('#editNumero').val(response.numero);
                    }
                }
            });
        }

        function chargerTiers() {
            const type = $('#editTypeFacturable').val();
            $.ajax({
                url: '{{ route("admin.factures.tiers") }}',
                type: 'GET',
                data: { type_facturable: type },
                success: function(response) {
                    if (response.success) {
                        const select = $('#editTiers');
                        select.html('<option value="">Sélectionner</option>');
                        response.data.forEach(item => {
                            const label = item.nom || item.nom_entreprise || 'N/A';
                            select.append(`<option value="${item.id}">${label}</option>`);
                        });
                    }
                }
            });
        }

        function editFacture(id) {
            $.ajax({
                url: '{{ route("admin.factures.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.facture) {
                        const f = response.facture;
                        $('#factureId').val(f.id);
                        $('#editType').val(f.type);
                        $('#editTypeFacturable').val(f.type_facturable);
                        $('#editTiers').val(f.id_facturable);
                        $('#editProjet').val(f.projet_id || '');
                        $('#editNumero').val(f.numero_facture);
                        $('#editDateFacture').val(f.date_facture);
                        $('#editDateEcheance').val(f.date_echeance || '');
                        $('#editMontantHt').val(f.montant_ht);
                        $('#editTva').val(f.tva || 0);
                        $('#editStatut').val(f.statut);
                        calculerTtc();

                        $('#factureModalTitle').html('<i class="fas fa-edit me-2"></i>Modifier la facture');
                        $('#formMethod').val('PUT');
                        $('#factureForm').attr('action', '{{ route("admin.factures.update", "") }}/' + id);
                        $('#btnSubmit').html('<i class="fas fa-save"></i> Mettre à jour');
                        factureModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function marquerPayee(id) {
            Swal.fire({
                title: 'Marquer comme payée',
                text: 'Voulez-vous marquer cette facture comme payée ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2d8f5e',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, marquer payée',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures.marquer-payee", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de l\'opération');
                        }
                    });
                }
            });
        }

        function annulerFacture(id) {
            Swal.fire({
                title: 'Annuler la facture',
                text: 'Voulez-vous vraiment annuler cette facture ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures.annuler", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de l\'annulation');
                        }
                    });
                }
            });
        }

        function relancerFacture(id) {
            Swal.fire({
                title: 'Relancer la facture',
                text: 'Voulez-vous envoyer une relance pour cette facture ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6b46c1',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, relancer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures.relancer", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la relance');
                        }
                    });
                }
            });
        }

        function envoyerFacture(id) {
            Swal.fire({
                title: 'Envoyer la facture',
                text: 'Voulez-vous envoyer cette facture par email ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d4a745',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, envoyer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures.envoyer", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de l\'envoi');
                        }
                    });
                }
            });
        }

        function deleteFacture(id) {
            Swal.fire({
                title: 'Supprimer la facture',
                text: 'Voulez-vous vraiment supprimer cette facture ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures.destroy", "") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la suppression');
                        }
                    });
                }
            });
        }

        console.log('✅ Gestion des factures chargée');
    </script>
@endsection
