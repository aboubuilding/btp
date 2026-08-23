{{-- resources/views/admin/clients/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Gestion des clients')

@section('page_title', 'Gestion des clients')
@section('page_icon', 'fa-address-card')

@section('breadcrumb')
    <li class="active">Gestion des clients</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddClient">
        <i class="fas fa-plus"></i> Nouveau client
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .client-avatar {
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
        .client-avatar.blue { background: linear-gradient(135deg, #2b6cb0, #2c5282); }
        .client-avatar.green { background: linear-gradient(135deg, #2d8f5e, #276749); }
        .client-avatar.purple { background: linear-gradient(135deg, #6b46c1, #553c9a); }
        .client-avatar.orange { background: linear-gradient(135deg, #d4a745, #b8922e); }
        .client-avatar.red { background: linear-gradient(135deg, #c0392b, #9b2c2c); }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }
        .status-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

        .type-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .type-badge.primary { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .type-badge.info { background: rgba(43, 108, 176, 0.08); color: #2b6cb0; }
        .type-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .type-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

        .projects-count {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--btp-primary);
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
        .table-actions .btn-action.btn-toggle { color: #d4a745; }
        .table-actions .btn-action.btn-toggle:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }
        .table-actions .btn-action.btn-delete { color: #c0392b; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-restore { color: #2d8f5e; }
        .table-actions .btn-action.btn-restore:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }

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
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['actifs'] ?? 0 }}</div>
                <div class="stat-label">Actifs</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number red">{{ $stats['inactifs'] ?? 0 }}</div>
                <div class="stat-label">Inactifs</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number" style="color: #d4a745;">
                    {{ collect($stats['types'] ?? [])->sum('total') }}
                </div>
                <div class="stat-label">Types</div>
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
                            <i class="fas fa-address-card"></i>
                            Liste des clients
                            <span class="badge bg-secondary ms-2" id="clientCount">{{ count($clients) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; height: 38px;">
                                    <option value="all">Tous les statuts</option>
                                    <option value="1">Actif</option>
                                    <option value="2">Inactif</option>
                                </select>
                                <select class="form-select form-select-sm" id="filterType" style="width: auto; height: 38px;">
                                    <option value="all">Tous les types</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="clientsTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Projets</th>
                                <th>Statut</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="clientsTableBody">
                            @forelse($clients as $client)
                                <tr data-id="{{ $client->id }}"
                                    data-status="{{ $client->etat }}"
                                    data-type="{{ $client->type }}">
                                    <td>
                                        <div class="client-avatar {{ ['blue', 'green', 'purple', 'orange', 'red'][$client->id % 5] }}">
                                            {{ strtoupper(substr($client->nom, 0, 2)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $client->nom }}</div>
                                        <small class="text-muted">{{ $client->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $client->personne_contact ?? '-' }}</div>
                                        <small class="text-muted">{{ $client->telephone ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="type-badge {{ $client->type_badge }}">
                                            {{ $client->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="projects-count">{{ $client->projets_count ?? 0 }}</span>
                                        @if(($client->projets_en_cours ?? 0) > 0)
                                            <small class="text-muted d-block">
                                                {{ $client->projets_en_cours }} en cours
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $client->status_badge }}">
                                            {{ $client->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editClient({{ $client->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-toggle" title="{{ $client->etat == 1 ? 'Désactiver' : 'Activer' }}" onclick="toggleClient({{ $client->id }})">
                                                <i class="fas {{ $client->etat == 1 ? 'fa-pause-circle' : 'fa-play-circle' }}"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteClient({{ $client->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-address-card fa-2x d-block mb-2"></i>
                                        Aucun client trouvé.
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
    <div class="modal fade" id="addClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Ajouter un client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addClientForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" placeholder="Nom du client" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Personne de contact</label>
                            <input type="text" name="personne_contact" class="form-control" placeholder="Nom du contact">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control" placeholder="+228 00 00 00 00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="exemple@domaine.com">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" placeholder="Adresse complète">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIF (Numéro d'identification fiscale)</label>
                            <input type="text" name="nif" class="form-control" placeholder="Numéro NIF">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Modifier le client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editClientForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="client_id" id="editClientId">
                    <div class="modal-body">
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
                            <label class="form-label">Personne de contact</label>
                            <input type="text" name="personne_contact" id="editPersonneContact" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" id="editTelephone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" id="editEmail" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" id="editAdresse" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIF</label>
                            <input type="text" name="nif" id="editNif" class="form-control">
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

@endsection

@section('js')
    <script>
        let addModal, editModal;

        $(document).ready(function() {
            addModal = new bootstrap.Modal(document.getElementById('addClientModal'));
            editModal = new bootstrap.Modal(document.getElementById('editClientModal'));

            // Bouton Ajouter
            $('#btnAddClient').on('click', function() {
                $('#addClientForm')[0].reset();
                addModal.show();
            });

            // Formulaire Ajout
            $('#addClientForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Ajout en cours...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.clients.store") }}',
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
                            toastr.error('Erreur lors de l\'ajout');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-save"></i> Ajouter').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editClientForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editClientId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.clients.update", "") }}/' + id,
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

            // Recherche
            $('#searchInput').on('input', function() {
                filterTable();
            });

            // Filtres
            $('#filterStatus, #filterType').on('change', function() {
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
            const type = $('#filterType').val();

            $('#clientsTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowStatus = String(row.data('status'));
                const rowType = row.data('type');

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (status !== 'all' && rowStatus !== status) {
                    show = false;
                }

                if (type !== 'all' && rowType !== type) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#clientCount').text($('#clientsTableBody tr:visible').length);
        }

        function editClient(id) {
            $.ajax({
                url: '{{ route("admin.clients.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.client) {
                        $('#editClientId').val(response.client.id);
                        $('#editNom').val(response.client.nom);
                        $('#editType').val(response.client.type);
                        $('#editPersonneContact').val(response.client.personne_contact || '');
                        $('#editTelephone').val(response.client.telephone || '');
                        $('#editEmail').val(response.client.email || '');
                        $('#editAdresse').val(response.client.adresse || '');
                        $('#editNif').val(response.client.nif || '');
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function toggleClient(id) {
            Swal.fire({
                title: 'Confirmation',
                text: 'Voulez-vous modifier le statut de ce client ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d4a745',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.clients.toggle-active", "") }}/' + id,
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

        function deleteClient(id) {
            Swal.fire({
                title: 'Supprimer le client',
                text: 'Voulez-vous vraiment supprimer ce client ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.clients.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des clients chargée');
    </script>
@endsection
