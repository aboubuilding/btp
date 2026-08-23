{{-- resources/views/admin/users/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('page_title', 'Gestion des utilisateurs')
@section('page_icon', 'fa-users-cog')

@section('breadcrumb')
    <li class="active">Gestion des utilisateurs</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddUser">
        <i class="fas fa-plus"></i> Nouvel utilisateur
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .user-avatar {
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
        .user-avatar.blue { background: linear-gradient(135deg, #2b6cb0, #2c5282); }
        .user-avatar.green { background: linear-gradient(135deg, #2d8f5e, #276749); }
        .user-avatar.purple { background: linear-gradient(135deg, #6b46c1, #553c9a); }
        .user-avatar.orange { background: linear-gradient(135deg, #d4a745, #b8922e); }
        .user-avatar.red { background: linear-gradient(135deg, #c0392b, #9b2c2c); }
        .user-avatar.teal { background: linear-gradient(135deg, #2c7a7b, #285e61); }
        .user-avatar.pink { background: linear-gradient(135deg, #d53f8c, #97266d); }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .status-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }
        .status-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

        .role-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            background: rgba(212, 167, 69, 0.12);
            color: #b8922e;
            white-space: nowrap;
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
        .table-actions .btn-action.btn-toggle { color: #b7950b; }
        .table-actions .btn-action.btn-toggle:hover { background: rgba(183, 149, 11, 0.08); border-color: #b7950b; }
        .table-actions .btn-action.btn-reset { color: #6b46c1; }
        .table-actions .btn-action.btn-reset:hover { background: rgba(107, 70, 193, 0.08); border-color: #6b46c1; }
        .table-actions .btn-action.btn-delete { color: #c0392b; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-role { color: #d4a745; }
        .table-actions .btn-action.btn-role:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }

        .last-login {
            font-size: 0.78rem;
            color: var(--btp-muted);
        }
        .last-login i {
            margin-right: 4px;
            font-size: 0.7rem;
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

        .modal-backdrop {
            background-color: rgba(10, 22, 40, 0.5);
        }

        @media (max-width: 768px) {
            .search-box {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
@endsection

@section('contenu')

    <div class="row">
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center gap-3 flex-wrap" style="width: 100%;">
                        <h6 class="section-title mb-0">
                            <i class="fas fa-users"></i>
                            Liste des utilisateurs
                            <span class="badge bg-secondary ms-2" id="userCount">{{ count($users) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterStatus" style="width: auto; height: 38px;">
                                    <option value="all">Tous les statuts</option>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                    <option value="supprime">Supprimé</option>
                                </select>
                                <select class="form-select form-select-sm" id="filterRole" style="width: auto; height: 38px;">
                                    <option value="all">Tous les rôles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="usersTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                                <th style="width: 220px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="usersTableBody">
                            @forelse($users as $user)
                                <tr data-id="{{ $user->id }}" data-status="{{ $user->etat == 2 ? 'supprime' : ($user->est_actif ? 'actif' : 'inactif') }}" data-role="{{ $user->role_id ?? '' }}">
                                    <td>
                                        <div class="user-avatar {{ ['blue', 'green', 'purple', 'orange', 'red', 'teal', 'pink'][$user->id % 7] }}">
                                            {{ strtoupper(substr($user->nom, 0, 2)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $user->nom }}</div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telephone ?? '-' }}</td>
                                    <td>
                                        <span class="role-badge">
                                            {{ $user->role->nom ?? 'Aucun rôle' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $user->status_badge }}">
                                            {{ $user->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="last-login">
                                            <i class="fas fa-clock"></i>
                                            {{ $user->derniere_connexion_le ? $user->derniere_connexion_le->diffForHumans() : 'Jamais' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editUser({{ $user->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-toggle" title="{{ $user->etat == 2 ? 'Restaurer' : ($user->est_actif ? 'Désactiver' : 'Activer') }}" onclick="toggleUser({{ $user->id }})">
                                                <i class="fas {{ $user->etat == 2 ? 'fa-undo' : ($user->est_actif ? 'fa-pause-circle' : 'fa-play-circle') }}"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-reset" title="Réinitialiser le mot de passe" onclick="resetPassword({{ $user->id }})">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-role" title="Assigner un rôle" onclick="openAssignRoleModal({{ $user->id }})">
                                                <i class="fas fa-user-tag"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteUser({{ $user->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-users-slash fa-2x d-block mb-2"></i>
                                        Aucun utilisateur trouvé.
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
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Ajouter un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" placeholder="Nom complet" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="exemple@domaine.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="+228 90 00 00 00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="mot_de_passe" class="form-control" placeholder="Min 8 caractères" required minlength="8">
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
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="editNom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" id="editTelephone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role_id" id="editRole" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe <span class="text-muted">(laisser vide pour conserver)</span></label>
                            <input type="password" name="mot_de_passe" id="editPassword" class="form-control" placeholder="Min 8 caractères" minlength="8">
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
        MODALE ASSIGNER UN RÔLE
    ============================================ --}}
    <div class="modal fade" id="assignRoleModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-tag me-2"></i>Assigner un rôle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="assignRoleForm">
                    @csrf
                    <input type="hidden" name="user_id" id="assignUserId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role_id" id="assignRoleSelect" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Assigner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let addModal, editModal, assignModal;

        $(document).ready(function() {
            addModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            assignModal = new bootstrap.Modal(document.getElementById('assignRoleModal'));

            // Bouton Ajouter
            $('#btnAddUser').on('click', function() {
                $('#addUserForm')[0].reset();
                addModal.show();
            });

            // Formulaire Ajout
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Ajout en cours...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.users.store") }}',
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
                        btn.html('<i class="fas fa-save"></i> Ajouter').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editUserId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.users.update", "") }}/' + id,
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

            // Formulaire Assignation Rôle
            $('#assignRoleForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#assignUserId').val();
                const btn = $(this).find('button[type="submit"]');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Assignation...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.users.assign-role", "") }}/' + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            assignModal.hide();
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    },
                    error: function() {
                        toastr.error('Erreur lors de l\'assignation');
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-check"></i> Assigner').prop('disabled', false);
                    }
                });
            });

            // Recherche
            $('#searchInput').on('input', function() {
                filterTable();
            });

            // Filtres
            $('#filterStatus, #filterRole').on('change', function() {
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
            const role = $('#filterRole').val();

            $('#usersTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowStatus = row.data('status');
                const rowRole = String(row.data('role'));

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (status !== 'all' && rowStatus !== status) {
                    show = false;
                }

                if (role !== 'all' && rowRole !== role) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#userCount').text($('#usersTableBody tr:visible').length);
        }

        function editUser(id) {
            // Récupérer les données via AJAX
            $.ajax({
                url: '{{ route("admin.users.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.user) {
                        $('#editUserId').val(response.user.id);
                        $('#editNom').val(response.user.nom);
                        $('#editEmail').val(response.user.email);
                        $('#editTelephone').val(response.user.telephone || '');
                        $('#editRole').val(response.user.role_id || '');
                        $('#editPassword').val('');
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function toggleUser(id) {
            Swal.fire({
                title: 'Confirmation',
                text: 'Voulez-vous modifier le statut de cet utilisateur ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d4a745',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.users.toggle-active", "") }}/' + id,
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

        function resetPassword(id) {
            Swal.fire({
                title: 'Réinitialiser le mot de passe',
                text: 'Un nouveau mot de passe sera généré automatiquement.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d4a745',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, réinitialiser',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.users.reset-password", "") }}/' + id,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Erreur lors de la réinitialisation');
                        }
                    });
                }
            });
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Supprimer l\'utilisateur',
                text: 'Voulez-vous vraiment supprimer cet utilisateur ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.users.destroy", "") }}/' + id,
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

        function openAssignRoleModal(id) {
            $('#assignUserId').val(id);
            assignModal.show();
        }

        console.log('✅ Gestion des utilisateurs chargée');
    </script>
@endsection
