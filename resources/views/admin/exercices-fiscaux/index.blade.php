{{-- resources/views/admin/exercices-fiscaux/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Exercices comptables')

@section('page_title', 'Exercices comptables')
@section('page_icon', 'fa-calendar-alt')

@section('breadcrumb')
    <li class="active">Exercices comptables</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddExercice">
        <i class="fas fa-plus"></i> Nouvel exercice
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .exercice-avatar {
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

        .statut-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .statut-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .statut-badge.danger { background: rgba(192, 57, 43, 0.12); color: #c0392b; }
        .statut-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

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
        .table-actions .btn-action.btn-close { color: #c0392b; }
        .table-actions .btn-action.btn-close:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-open { color: #2d8f5e; }
        .table-actions .btn-action.btn-open:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }
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

        .current-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #2d8f5e;
            color: #fff;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
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
                <div class="stat-number green">{{ $stats['ouverts'] ?? 0 }}</div>
                <div class="stat-label">Ouverts</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number red">{{ $stats['clotures'] ?? 0 }}</div>
                <div class="stat-label">Clôturés</div>
            </div>
        </div>
    </div>

    {{-- Exercice courant --}}
    @if($currentExercice)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-calendar-check fa-2x"></i>
                <div>
                    <strong>Exercice en cours :</strong>
                    {{ $currentExercice->nom }}
                    <span class="badge bg-success ms-2">Ouvert</span>
                    <br>
                    <small>
                        Du {{ $currentExercice->date_debut->format('d/m/Y') }}
                        au {{ $currentExercice->date_fin->format('d/m/Y') }}
                    </small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Aucun exercice ouvert en cours. Veuillez créer un nouvel exercice.
        </div>
    @endif

    {{-- Liste --}}
    <div class="row">
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center gap-3 flex-wrap" style="width: 100%;">
                        <h6 class="section-title mb-0">
                            <i class="fas fa-calendar-alt"></i>
                            Liste des exercices
                            <span class="badge bg-secondary ms-2" id="exerciceCount">{{ count($exercices) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
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
                        <table class="table table-hover align-middle" id="exercicesTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Nom</th>
                                <th>Période</th>
                                <th>Durée</th>
                                <th>Écritures</th>
                                <th>Statut</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="exercicesTableBody">
                            @forelse($exercices as $exercice)
                                <tr data-id="{{ $exercice->id }}" data-statut="{{ $exercice->statut }}">
                                    <td>
                                        <div class="exercice-avatar" style="position: relative;">
                                            <i class="fas fa-calendar"></i>
                                            @if($currentExercice && $currentExercice->id == $exercice->id)
                                                <span class="current-badge">En cours</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $exercice->nom }}</div>
                                        <small class="text-muted">
                                            {{ $exercice->etat == 1 ? 'Actif' : 'Inactif' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div>{{ $exercice->date_debut ? $exercice->date_debut->format('d/m/Y') : '-' }}</div>
                                        <small class="text-muted">→ {{ $exercice->date_fin ? $exercice->date_fin->format('d/m/Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $exercice->duree ?? 0 }} jours
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $exercice->ecritures_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="statut-badge {{ $exercice->statut_badge }}">
                                            {{ $exercice->statut_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editExercice({{ $exercice->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            @if($exercice->statut === 'ouvert')
                                                <button type="button" class="btn-action btn-close" title="Clôturer" onclick="closeExercice({{ $exercice->id }})">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn-action btn-open" title="Ouvrir" onclick="openExercice({{ $exercice->id }})">
                                                    <i class="fas fa-lock-open"></i>
                                                </button>
                                            @endif
                                            @if($exercice->ecritures_count == 0)
                                                <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteExercice({{ $exercice->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                                        Aucun exercice trouvé.
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
    <div class="modal fade" id="exerciceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exerciceModalTitle">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Nouvel exercice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="exerciceForm">
                    @csrf
                    <input type="hidden" name="exercice_id" id="exerciceId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="editNom" class="form-control" placeholder="Ex: Exercice 2026" required>
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
                                    <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" id="editDateFin" class="form-control" required>
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
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Un exercice peut être ouvert ou clôturé. Les écritures comptables ne peuvent être ajoutées que dans un exercice ouvert.
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
        let exerciceModal;

        $(document).ready(function() {
            exerciceModal = new bootstrap.Modal(document.getElementById('exerciceModal'));

            // Bouton Ajouter
            $('#btnAddExercice').on('click', function() {
                resetForm();
                $('#exerciceModalTitle').html('<i class="fas fa-calendar-plus me-2"></i>Nouvel exercice');
                $('#formMethod').val('POST');
                $('#exerciceForm').attr('action', '{{ route("admin.exercices-fiscaux.store") }}');
                $('#btnSubmit').html('<i class="fas fa-save"></i> Enregistrer');
                exerciceModal.show();
            });

            // Formulaire
            $('#exerciceForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...').prop('disabled', true);

                const formData = $(this).serialize();
                const action = $(this).attr('action');
                const method = $('#formMethod').val();

                let url = action;
                let type = 'POST';

                if (method === 'PUT') {
                    const id = $('#exerciceId').val();
                    url = '{{ route("admin.exercices-fiscaux.update", "") }}/' + id;
                    type = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            exerciceModal.hide();
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
            $('#filterStatut').on('change', function() {
                filterTable();
            });

            // Rafraîchir
            $('#btnRefresh').on('click', function() {
                window.location.reload();
            });
        });

        function filterTable() {
            const search = $('#searchInput').val().toLowerCase();
            const statut = $('#filterStatut').val();

            $('#exercicesTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowStatut = row.data('statut');

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (statut !== 'all' && rowStatut !== statut) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#exerciceCount').text($('#exercicesTableBody tr:visible').length);
        }

        function resetForm() {
            $('#exerciceForm')[0].reset();
            $('#exerciceId').val('');
        }

        function editExercice(id) {
            $.ajax({
                url: '{{ route("admin.exercices-fiscaux.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.exercice) {
                        const e = response.exercice;
                        $('#exerciceId').val(e.id);
                        $('#editNom').val(e.nom);
                        $('#editDateDebut').val(e.date_debut);
                        $('#editDateFin').val(e.date_fin);
                        $('#editStatut').val(e.statut);

                        $('#exerciceModalTitle').html('<i class="fas fa-edit me-2"></i>Modifier l\'exercice');
                        $('#formMethod').val('PUT');
                        $('#exerciceForm').attr('action', '{{ route("admin.exercices-fiscaux.update", "") }}/' + id);
                        $('#btnSubmit').html('<i class="fas fa-save"></i> Mettre à jour');
                        exerciceModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function closeExercice(id) {
            Swal.fire({
                title: 'Clôturer l\'exercice',
                text: 'Voulez-vous vraiment clôturer cet exercice ? Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, clôturer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.exercices-fiscaux.close", "") }}/' + id,
                        type: 'POST',
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
                            toastr.error('Erreur lors de la clôture');
                        }
                    });
                }
            });
        }

        function openExercice(id) {
            Swal.fire({
                title: 'Ouvrir l\'exercice',
                text: 'Voulez-vous rouvrir cet exercice ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2d8f5e',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, ouvrir',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.exercices-fiscaux.open", "") }}/' + id,
                        type: 'POST',
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
                            toastr.error('Erreur lors de l\'ouverture');
                        }
                    });
                }
            });
        }

        function deleteExercice(id) {
            Swal.fire({
                title: 'Supprimer l\'exercice',
                text: 'Voulez-vous vraiment supprimer cet exercice ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.exercices-fiscaux.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des exercices comptables chargée');
    </script>
@endsection
