{{-- resources/views/admin/contrats-sous-traitants/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Gestion des contrats de sous-traitance')

@section('page_title', 'Contrats de sous-traitance')
@section('page_icon', 'fa-file-contract')

@section('breadcrumb')
    <li class="active">Contrats de sous-traitance</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddContrat">
        <i class="fas fa-plus"></i> Nouveau contrat
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .contrat-avatar {
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
            background: linear-gradient(135deg, #1a3a5c, #2b6cb0);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.info { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
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
        .table-actions .btn-action.btn-delete { color: #c0392b; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-download { color: #2d8f5e; }
        .table-actions .btn-action.btn-download:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }
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

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
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
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['en_cours'] ?? 0 }}</div>
                <div class="stat-label">En cours</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number blue">{{ $stats['termines'] ?? 0 }}</div>
                <div class="stat-label">Terminés</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
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
                            <i class="fas fa-file-contract"></i>
                            Liste des contrats
                            <span class="badge bg-secondary ms-2" id="contratCount">{{ count($contrats) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; height: 38px;">
                                    <option value="all">Tous les statuts</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="termine">Terminé</option>
                                    <option value="resilie">Résilié</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="contratsTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Contrat</th>
                                <th>Sous-traitant</th>
                                <th>Projet</th>
                                <th>Montant</th>
                                <th>Dates</th>
                                <th>Statut</th>
                                <th style="width: 200px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="contratsTableBody">
                            @forelse($contrats as $contrat)
                                <tr data-id="{{ $contrat->id }}" data-status="{{ $contrat->statut }}">
                                    <td>
                                        <div class="contrat-avatar">
                                            {{ strtoupper(substr($contrat->numero_contrat, -4)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $contrat->numero_contrat }}</div>
                                        <small class="text-muted">{{ Str::limit($contrat->description ?? '', 40) }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $contrat->sous_traitant->nom_entreprise ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $contrat->sous_traitant->personne_contact ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $contrat->projet->nom ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $contrat->projet->code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $contrat->montant_formatted }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '-' }}</div>
                                        <small class="text-muted">
                                            {{ $contrat->date_fin ? '→ ' . $contrat->date_fin->format('d/m/Y') : 'Sans date fin' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $contrat->status_badge }}">
                                            {{ $contrat->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editContrat({{ $contrat->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-status" title="Changer le statut" onclick="openStatusModal({{ $contrat->id }})">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            @if($contrat->chemin_fichier)
                                                <a href="{{ route('admin.contrats-sous-traitants.download', $contrat->id) }}" class="btn-action btn-download" title="Télécharger le fichier" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteContrat({{ $contrat->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-file-contract fa-2x d-block mb-2"></i>
                                        Aucun contrat trouvé.
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
    <div class="modal fade" id="addContratModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>Nouveau contrat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addContratForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sous-traitant <span class="text-danger">*</span></label>
                                    <select name="sous_traitant_id" class="form-select" required>
                                        <option value="">Sélectionner un sous-traitant</option>
                                        @foreach($soustraitants as $soustraitant)
                                            <option value="{{ $soustraitant->id }}">
                                                {{ $soustraitant->nom_entreprise }} ({{ $soustraitant->specialite_label ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Projet <span class="text-danger">*</span></label>
                                    <select name="projet_id" class="form-select" required>
                                        <option value="">Sélectionner un projet</option>
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
                                    <label class="form-label">Numéro de contrat <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="numero_contrat" id="addNumeroContrat" class="form-control" required>
                                        <button type="button" class="btn btn-outline-secondary" id="btnGenerateNumero">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="montant" class="form-control" placeholder="0" min="0" step="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" name="date_fin" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Description du contrat"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fichier du contrat</label>
                            <div class="file-upload-wrapper w-100">
                                <input type="file" name="fichier" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Formats acceptés : PDF, DOC, DOCX, JPG, PNG</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Créer le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editContratModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>Modifier le contrat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editContratForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="contrat_id" id="editContratId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sous-traitant <span class="text-danger">*</span></label>
                                    <select name="sous_traitant_id" id="editSousTraitant" class="form-select" required>
                                        <option value="">Sélectionner un sous-traitant</option>
                                        @foreach($soustraitants as $soustraitant)
                                            <option value="{{ $soustraitant->id }}">
                                                {{ $soustraitant->nom_entreprise }} ({{ $soustraitant->specialite_label ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Projet <span class="text-danger">*</span></label>
                                    <select name="projet_id" id="editProjet" class="form-select" required>
                                        <option value="">Sélectionner un projet</option>
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
                                    <label class="form-label">Numéro de contrat <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_contrat" id="editNumeroContrat" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="montant" id="editMontant" class="form-control" min="0" step="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" id="editDateDebut" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" name="date_fin" id="editDateFin" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fichier du contrat</label>
                            <div class="file-upload-wrapper w-100">
                                <input type="file" name="fichier" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Laissez vide pour conserver le fichier actuel</small>
                            </div>
                            <div id="currentFile" class="mt-2"></div>
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
                    <input type="hidden" name="contrat_id" id="statusContratId">
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
            addModal = new bootstrap.Modal(document.getElementById('addContratModal'));
            editModal = new bootstrap.Modal(document.getElementById('editContratModal'));
            statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

            // Bouton Ajouter
            $('#btnAddContrat').on('click', function() {
                $('#addContratForm')[0].reset();
                generateNumeroContrat('add');
                addModal.show();
            });

            // Générer numéro de contrat
            $('#btnGenerateNumero').on('click', function() {
                generateNumeroContrat('add');
            });

            // Formulaire Ajout
            $('#addContratForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                const formData = new FormData(this);
                btn.html('<i class="fas fa-spinner fa-spin"></i> Création en cours...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.contrats-sous-traitants.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
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
                        btn.html('<i class="fas fa-save"></i> Créer le contrat').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editContratForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editContratId').val();
                const btn = $('#btnEditSubmit');
                const formData = new FormData(this);
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.contrats-sous-traitants.update", "") }}/' + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
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
                const id = $('#statusContratId').val();
                const btn = $(this).find('button[type="submit"]');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.contrats-sous-traitants.update-status", "") }}/' + id,
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

            $('#contratsTableBody tr').each(function() {
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

            $('#contratCount').text($('#contratsTableBody tr:visible').length);
        }

        function generateNumeroContrat(target) {
            $.ajax({
                url: '{{ route("admin.contrats-sous-traitants.generate-numero") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        if (target === 'add') {
                            $('#addNumeroContrat').val(response.numero);
                        }
                    }
                },
                error: function() {
                    toastr.error('Erreur lors de la génération du numéro');
                }
            });
        }

        function editContrat(id) {
            $.ajax({
                url: '{{ route("admin.contrats-sous-traitants.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.contrat) {
                        const c = response.contrat;
                        $('#editContratId').val(c.id);
                        $('#editSousTraitant').val(c.sous_traitant_id);
                        $('#editProjet').val(c.projet_id);
                        $('#editNumeroContrat').val(c.numero_contrat);
                        $('#editMontant').val(c.montant);
                        $('#editDateDebut').val(c.date_debut);
                        $('#editDateFin').val(c.date_fin || '');
                        $('#editDescription').val(c.description || '');

                        if (c.chemin_fichier) {
                            $('#currentFile').html(`
                            <span class="badge bg-success">
                                <i class="fas fa-file"></i> Fichier actuel
                            </span>
                            <a href="{{ route('admin.contrats-sous-traitants.download', '') }}/${c.id}" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        `);
                        } else {
                            $('#currentFile').html('');
                        }

                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function openStatusModal(id) {
            $('#statusContratId').val(id);
            statusModal.show();
        }

        function deleteContrat(id) {
            Swal.fire({
                title: 'Supprimer le contrat',
                text: 'Voulez-vous vraiment supprimer ce contrat ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.contrats-sous-traitants.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des contrats de sous-traitance chargée');
    </script>
@endsection
