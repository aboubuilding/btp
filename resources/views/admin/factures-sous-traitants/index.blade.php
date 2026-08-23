{{-- resources/views/admin/factures-sous-traitants/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Factures sous-traitants')

@section('page_title', 'Factures sous-traitants')
@section('page_icon', 'fa-file-invoice')

@section('breadcrumb')
    <li class="active">Factures sous-traitants</li>
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
            background: linear-gradient(135deg, #d4a745, #b8922e);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.primary { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .status-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }
        .status-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

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
        .table-actions .btn-action.btn-status { color: #d4a745; }
        .table-actions .btn-action.btn-status:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }
        .table-actions .btn-action.btn-pay { color: #2d8f5e; }
        .table-actions .btn-action.btn-pay:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }
        .table-actions .btn-action.btn-contest { color: #c0392b; }
        .table-actions .btn-action.btn-contest:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
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
        .stat-card-mini .stat-number.blue { color: #2b6cb0; }
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
                <div class="stat-number blue">{{ $stats['emises'] ?? 0 }}</div>
                <div class="stat-label">Émises</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['payees'] ?? 0 }}</div>
                <div class="stat-label">Payées</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number orange">{{ $stats['partiellement'] ?? 0 }}</div>
                <div class="stat-label">Partiellement</div>
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
                <div class="stat-number accent">
                    {{ number_format(($stats['montant_total'] ?? 0) / 1000000, 1, ',', ' ') }}M
                </div>
                <div class="stat-label">Montant total</div>
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
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; height: 38px;">
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
                                <th>Facture</th>
                                <th>Sous-traitant</th>
                                <th>Projet</th>
                                <th>Montant TTC</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th style="width: 200px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="facturesTableBody">
                            @forelse($factures as $facture)
                                <tr data-id="{{ $facture->id }}" data-status="{{ $facture->statut }}">
                                    <td>
                                        <div class="facture-avatar">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $facture->numero_facture }}</div>
                                        <small class="text-muted">HT: {{ $facture->montant_ht_formatted }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $facture->facturable->nom_entreprise ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $facture->facturable->personne_contact ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $facture->projet->nom ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $facture->projet->code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $facture->montant_ttc_formatted }}</div>
                                        <small class="text-muted">TVA: {{ $facture->tva }}%</small>
                                    </td>
                                    <td>
                                        <div>{{ $facture->date_facture ? $facture->date_facture->format('d/m/Y') : '-' }}</div>
                                        <small class="text-muted">
                                            Échéance: {{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $facture->status_badge }}">
                                            {{ $facture->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editFacture({{ $facture->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            @if($facture->statut !== 'payee' && $facture->statut !== 'annulee')
                                                <button type="button" class="btn-action btn-pay" title="Marquer payée" onclick="marquerPayee({{ $facture->id }})">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-contest" title="Contester" onclick="contesterFacture({{ $facture->id }})">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn-action btn-status" title="Changer le statut" onclick="openStatusModal({{ $facture->id }})">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteFacture({{ $facture->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
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
        MODALE AJOUT
    ============================================ --}}
    <div class="modal fade" id="addFactureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>Nouvelle facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addFactureForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sous-traitant <span class="text-danger">*</span></label>
                                    <select name="id_facturable" class="form-select" required>
                                        <option value="">Sélectionner un sous-traitant</option>
                                        @foreach($soustraitants as $soustraitant)
                                            <option value="{{ $soustraitant->id }}">
                                                {{ $soustraitant->nom_entreprise }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Projet</label>
                                    <select name="projet_id" class="form-select">
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
                                    <label class="form-label">Numéro de facture <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="numero_facture" id="addNumeroFacture" class="form-control" required>
                                        <button type="button" class="btn btn-outline-secondary" id="btnGenerateNumero">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de facture <span class="text-danger">*</span></label>
                                    <input type="date" name="date_facture" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'échéance</label>
                                    <input type="date" name="date_echeance" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" class="form-select">
                                        @foreach($statuts as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Montant HT (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="montant_ht" class="form-control" placeholder="0" min="0" step="1000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">TVA (%)</label>
                                    <input type="number" name="tva" class="form-control" placeholder="0" min="0" max="100" step="0.5">
                                    <small class="text-muted">Le montant TTC sera calculé automatiquement</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Créer la facture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editFactureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>Modifier la facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editFactureForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="facture_id" id="editFactureId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sous-traitant <span class="text-danger">*</span></label>
                                    <select name="id_facturable" id="editSoustraitant" class="form-select" required>
                                        <option value="">Sélectionner un sous-traitant</option>
                                        @foreach($soustraitants as $soustraitant)
                                            <option value="{{ $soustraitant->id }}">
                                                {{ $soustraitant->nom_entreprise }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <label class="form-label">Numéro de facture <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_facture" id="editNumeroFacture" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de facture <span class="text-danger">*</span></label>
                                    <input type="date" name="date_facture" id="editDateFacture" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'échéance</label>
                                    <input type="date" name="date_echeance" id="editDateEcheance" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" id="editStatut" class="form-select">
                                        @foreach($statuts as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Montant HT (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="montant_ht" id="editMontantHt" class="form-control" min="0" step="1000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">TVA (%)</label>
                                    <input type="number" name="tva" id="editTva" class="form-control" min="0" max="100" step="0.5">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Le montant TTC sera recalculé automatiquement
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnEditSubmit">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE CHANGER LE STATUT
    ============================================ --}}
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Changer le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm">
                    @csrf
                    <input type="hidden" name="facture_id" id="statusFactureId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statusSelect" class="form-select" required>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let addModal, editModal, statusModal;

        $(document).ready(function() {
            addModal = new bootstrap.Modal(document.getElementById('addFactureModal'));
            editModal = new bootstrap.Modal(document.getElementById('editFactureModal'));
            statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

            // Bouton Ajouter
            $('#btnAddFacture').on('click', function() {
                $('#addFactureForm')[0].reset();
                generateNumeroFacture('add');
                addModal.show();
            });

            // Générer numéro de facture
            $('#btnGenerateNumero').on('click', function() {
                generateNumeroFacture('add');
            });

            // Formulaire Ajout
            $('#addFactureForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Création en cours...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.factures-sous-traitants.store") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            addModal.hide();
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
                            toastr.error('Erreur lors de la création');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-save"></i> Créer la facture').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editFactureForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editFactureId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.factures-sous-traitants.update", "") }}/' + id,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            editModal.hide();
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
                            toastr.error('Erreur lors de la mise à jour');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-save"></i> Mettre à jour').prop('disabled', false);
                    }
                });
            });

            // Formulaire Statut
            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#statusFactureId').val();
                const btn = $(this).find('button[type="submit"]');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.factures-sous-traitants.update-status", "") }}/' + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            statusModal.hide();
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    },
                    error: function() {
                        toastr.error('Erreur lors de la mise à jour du statut');
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-check"></i> Mettre à jour').prop('disabled', false);
                    }
                });
            });

            // Recherche
            $('#searchInput').on('input', function() {
                filterTable();
            });

            // Filtres
            $('#filterStatus').on('change', function() {
                filterTable();
            });

            // Rafraîchir
            $('#btnRefresh').on('click', function() {
                window.location.reload();
            });
        });

        function filterTable() {
            const search = $('#searchInput').val().toLowerCase();
            const status = $('#filterStatus').val();

            $('#facturesTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowStatus = row.data('status');

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (status !== 'all' && rowStatus !== status) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#factureCount').text($('#facturesTableBody tr:visible').length);
        }

        function generateNumeroFacture(target) {
            $.ajax({
                url: '{{ route("admin.factures-sous-traitants.generate-numero") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        if (target === 'add') {
                            $('#addNumeroFacture').val(response.numero);
                        }
                    }
                },
                error: function() {
                    toastr.error('Erreur lors de la génération du numéro');
                }
            });
        }

        function editFacture(id) {
            $.ajax({
                url: '{{ route("admin.factures-sous-traitants.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.facture) {
                        const f = response.facture;
                        $('#editFactureId').val(f.id);
                        $('#editSoustraitant').val(f.id_facturable);
                        $('#editProjet').val(f.projet_id || '');
                        $('#editNumeroFacture').val(f.numero_facture);
                        $('#editDateFacture').val(f.date_facture);
                        $('#editDateEcheance').val(f.date_echeance || '');
                        $('#editStatut').val(f.statut);
                        $('#editMontantHt').val(f.montant_ht);
                        $('#editTva').val(f.tva || 0);
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function openStatusModal(id) {
            $('#statusFactureId').val(id);
            statusModal.show();
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
                        url: '{{ route("admin.factures-sous-traitants.marquer-payee", "") }}/' + id,
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

        function contesterFacture(id) {
            Swal.fire({
                title: 'Contester la facture',
                text: 'Voulez-vous contester cette facture ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, contester',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.factures-sous-traitants.contester", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la contestation');
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
                        url: '{{ route("admin.factures-sous-traitants.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des factures sous-traitants chargée');
    </script>
@endsection
