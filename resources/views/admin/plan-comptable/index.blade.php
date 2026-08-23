{{-- resources/views/admin/plan-comptable/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Plan comptable')

@section('page_title', 'Plan comptable')
@section('page_icon', 'fa-book')

@section('breadcrumb')
    <li class="active">Plan comptable</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddCompte">
        <i class="fas fa-plus"></i> Nouveau compte
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .compte-avatar {
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
        .compte-avatar.actif { background: linear-gradient(135deg, #2d8f5e, #1a6e3e); }
        .compte-avatar.passif { background: linear-gradient(135deg, #c0392b, #9b2c2c); }
        .compte-avatar.charge { background: linear-gradient(135deg, #b7950b, #8d6b0a); }
        .compte-avatar.produit { background: linear-gradient(135deg, #2b6cb0, #1a4d7a); }
        .compte-avatar.capitaux { background: linear-gradient(135deg, #6b46c1, #553c9a); }

        .type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .type-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .type-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }
        .type-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .type-badge.info { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .type-badge.primary { background: rgba(107, 70, 193, 0.12); color: #6b46c1; }

        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }

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
        .table-actions .btn-action.btn-move { color: #d4a745; }
        .table-actions .btn-action.btn-move:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }
        .table-actions .btn-action.btn-delete { color: #c0392b; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-restore { color: #6b46c1; }
        .table-actions .btn-action.btn-restore:hover { background: rgba(107, 70, 193, 0.08); border-color: #6b46c1; }

        .tree-indent {
            display: inline-block;
            color: #dce4ea;
            font-weight: 300;
            letter-spacing: 1px;
        }

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
        .stat-card-mini .stat-label {
            font-size: 0.7rem;
            color: var(--btp-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-backdrop {
            background-color: rgba(10, 22, 40, 0.5);
        }

        .compte-path {
            font-size: 0.7rem;
            color: var(--btp-muted);
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
                <div class="stat-label">Total comptes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['root_count'] ?? 0 }}</div>
                <div class="stat-label">Comptes racines</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['avec_enfants'] ?? 0 }}</div>
                <div class="stat-label">Avec sous-comptes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ count($stats['by_type'] ?? []) }}</div>
                <div class="stat-label">Types utilisés</div>
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
                            <i class="fas fa-book"></i>
                            Plan comptable
                            <span class="badge bg-secondary ms-2" id="compteCount">{{ count($flatList) }}</span>
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
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; height: 38px;">
                                    <option value="all">Tous les statuts</option>
                                    <option value="1">Actif</option>
                                    <option value="2">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="comptesTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Compte parent</th>
                                <th>Statut</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="comptesTableBody">
                            @forelse($flatList as $compte)
                                <tr data-id="{{ $compte['id'] }}"
                                    data-type="{{ $compte['type'] }}"
                                    data-status="{{ $compte['etat'] }}">
                                    <td>
                                        <div class="compte-avatar {{ $compte['type'] }}">
                                            <i class="fas {{ \App\Models\PlanComptable::getTypeIcons()[$compte['type']] ?? 'fa-tag' }}"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $compte['code'] }}</span>
                                        @if(isset($compte['children']) && count($compte['children']) > 0)
                                            <span class="badge bg-secondary ms-1">{{ count($compte['children']) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            @if(isset($compte['indent']))
                                                <span class="tree-indent">{{ $compte['indent'] }}</span>
                                            @endif
                                            {{ $compte['nom'] }}
                                        </div>
                                        @if(isset($compte['children']) && count($compte['children']) > 0)
                                            <small class="text-muted">{{ count($compte['children']) }} sous-compte(s)</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="type-badge {{ $compte['type_color'] ?? $compte['type'] }}">
                                            <i class="fas {{ \App\Models\PlanComptable::getTypeIcons()[$compte['type']] ?? 'fa-tag' }}"></i>
                                            {{ $compte['type_label'] ?? $compte['type'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($compte['parent_id'])
                                            <span class="compte-path">
                                                {{ $compte['parent'] ? $compte['parent']['code'] . ' - ' . $compte['parent']['nom'] : 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $compte['etat'] == 1 ? 'success' : 'danger' }}">
                                            {{ $compte['etat'] == 1 ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editCompte({{ $compte['id'] }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-move" title="Réorganiser" onclick="openMoveModal({{ $compte['id'] }})">
                                                <i class="fas fa-arrows-alt"></i>
                                            </button>
                                            @if($compte['etat'] == 1)
                                                <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteCompte({{ $compte['id'] }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn-action btn-restore" title="Restaurer" onclick="restoreCompte({{ $compte['id'] }})">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-book fa-2x d-block mb-2"></i>
                                        Aucun compte trouvé.
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
    <div class="modal fade" id="addCompteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Nouveau compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCompteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: 411" required>
                            <small class="text-muted">Code unique du compte selon le plan comptable</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" placeholder="Nom du compte" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">Sélectionner un type</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Compte parent</label>
                            <select name="parent_id" class="form-select">
                                <option value="">Aucun (compte racine)</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->nom }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Laissez vide pour un compte de niveau 1</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editCompteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Modifier le compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCompteForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="compte_id" id="editCompteId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="editCode" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="editNom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="editType" class="form-select" required>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Compte parent</label>
                            <select name="parent_id" id="editParent" class="form-select">
                                <option value="">Aucun (compte racine)</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->nom }}</option>
                                @endforeach
                            </select>
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
        MODALE RÉORGANISATION
    ============================================ --}}
    <div class="modal fade" id="moveCompteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-arrows-alt me-2"></i>Réorganiser le compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="moveCompteForm">
                    @csrf
                    <input type="hidden" name="compte_id" id="moveCompteId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nouveau compte parent</label>
                            <select name="parent_id" id="moveParent" class="form-select">
                                <option value="">Aucun (compte racine)</option>
                            </select>
                            <small class="text-muted">Déplacer ce compte sous un autre compte parent</small>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Attention : Le compte sera déplacé avec tous ses sous-comptes.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Déplacer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let addModal, editModal, moveModal;

        $(document).ready(function() {
            addModal = new bootstrap.Modal(document.getElementById('addCompteModal'));
            editModal = new bootstrap.Modal(document.getElementById('editCompteModal'));
            moveModal = new bootstrap.Modal(document.getElementById('moveCompteModal'));

            // Bouton Ajouter
            $('#btnAddCompte').on('click', function() {
                $('#addCompteForm')[0].reset();
                addModal.show();
            });

            // Formulaire Ajout
            $('#addCompteForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Création...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.plan-comptable.store") }}',
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
                        btn.html('<i class="fas fa-save"></i> Créer').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editCompteForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editCompteId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.plan-comptable.update", "") }}/' + id,
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

            // Formulaire Réorganisation
            $('#moveCompteForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#moveCompteId').val();
                const btn = $(this).find('button[type="submit"]');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Déplacement...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.plan-comptable.reorder", "") }}/' + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            moveModal.hide();
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
                            toastr.error('Erreur lors du déplacement');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-check"></i> Déplacer').prop('disabled', false);
                    }
                });
            });

            // Recherche
            $('#searchInput').on('input', function() {
                filterTable();
            });

            // Filtres
            $('#filterType, #filterStatus').on('change', function() {
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
            const status = $('#filterStatus').val();

            $('#comptesTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowType = row.data('type');
                const rowStatus = String(row.data('status'));

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (type !== 'all' && rowType !== type) {
                    show = false;
                }

                if (status !== 'all' && rowStatus !== status) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#compteCount').text($('#comptesTableBody tr:visible').length);
        }

        function editCompte(id) {
            $.ajax({
                url: '{{ route("admin.plan-comptable.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.compte) {
                        const c = response.compte;
                        $('#editCompteId').val(c.id);
                        $('#editCode').val(c.code);
                        $('#editNom').val(c.nom);
                        $('#editType').val(c.type);
                        $('#editParent').val(c.parent_id || '');
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function openMoveModal(id) {
            $('#moveCompteId').val(id);

            // Charger la liste des parents disponibles
            $.ajax({
                url: '{{ route("admin.plan-comptable.parents") }}',
                type: 'GET',
                data: { exclude_id: id },
                success: function(response) {
                    if (response.success) {
                        const select = $('#moveParent');
                        select.html('<option value="">Aucun (compte racine)</option>');
                        response.data.forEach(parent => {
                            select.append(`<option value="${parent.id}">${parent.code} - ${parent.nom}</option>`);
                        });
                        moveModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des parents');
                }
            });
        }

        function deleteCompte(id) {
            Swal.fire({
                title: 'Supprimer le compte',
                text: 'Voulez-vous vraiment supprimer ce compte ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.plan-comptable.destroy", "") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la suppression');
                        }
                    });
                }
            });
        }

        function restoreCompte(id) {
            Swal.fire({
                title: 'Restaurer le compte',
                text: 'Voulez-vous restaurer ce compte ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6b46c1',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.plan-comptable.restore", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la restauration');
                        }
                    });
                }
            });
        }

        console.log('✅ Gestion du plan comptable chargée');
    </script>
@endsection
