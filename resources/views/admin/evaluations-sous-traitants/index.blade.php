{{-- resources/views/admin/evaluations-sous-traitants/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Évaluations des sous-traitants')

@section('page_title', 'Évaluations des sous-traitants')
@section('page_icon', 'fa-star-half-stroke')

@section('breadcrumb')
    <li class="active">Évaluations des sous-traitants</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddEvaluation">
        <i class="fas fa-plus"></i> Nouvelle évaluation
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .evaluation-avatar {
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
            background: linear-gradient(135deg, #6b46c1, #553c9a);
        }

        .star-rating {
            color: #d4a745;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        .star-rating .empty {
            color: #dce4ea;
        }

        .note-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #fff;
        }
        .note-circle.success { background: #2d8f5e; }
        .note-circle.primary { background: #2b6cb0; }
        .note-circle.warning { background: #b7950b; }
        .note-circle.danger { background: #c0392b; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .status-badge.primary { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .status-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
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
        .table-actions .btn-action.btn-delete { color: #c0392b; }
        .table-actions .btn-action.btn-delete:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-restore { color: #6b46c1; }
        .table-actions .btn-action.btn-restore:hover { background: rgba(107, 70, 193, 0.08); border-color: #6b46c1; }
        .table-actions .btn-action.btn-view { color: #d4a745; }
        .table-actions .btn-action.btn-view:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }

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
                <div class="stat-label">Total évaluations</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['moyenne_qualite'] ?? 0 }}</div>
                <div class="stat-label">Qualité</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number blue">{{ $stats['moyenne_delai'] ?? 0 }}</div>
                <div class="stat-label">Délai</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number orange">{{ $stats['moyenne_securite'] ?? 0 }}</div>
                <div class="stat-label">Sécurité</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number accent">{{ $stats['moyenne_generale'] ?? 0 }}</div>
                <div class="stat-label">Moyenne générale</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number">{{ count($stats['par_mois'] ?? []) }}</div>
                <div class="stat-label">Mois avec évaluations</div>
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
                            <i class="fas fa-star-half-stroke"></i>
                            Liste des évaluations
                            <span class="badge bg-secondary ms-2" id="evaluationCount">{{ count($evaluations) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterNote" style="width: auto; height: 38px;">
                                    <option value="all">Toutes les notes</option>
                                    <option value="5">⭐ 5 - Excellent</option>
                                    <option value="4">⭐ 4 - Très bon</option>
                                    <option value="3">⭐ 3 - Bon</option>
                                    <option value="2">⭐ 2 - Moyen</option>
                                    <option value="1">⭐ 1 - Médiocre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="evaluationsTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Sous-traitant</th>
                                <th>Projet</th>
                                <th>Qualité</th>
                                <th>Délai</th>
                                <th>Sécurité</th>
                                <th>Moyenne</th>
                                <th>Date</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="evaluationsTableBody">
                            @forelse($evaluations as $evaluation)
                                <tr data-id="{{ $evaluation->id }}" data-note="{{ round($evaluation->note_moyenne) }}">
                                    <td>
                                        <div class="evaluation-avatar">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $evaluation->sousTraitant->nom_entreprise ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $evaluation->sousTraitant->personne_contact ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $evaluation->projet->nom ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $evaluation->projet->code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $evaluation->note_qualite ? '' : ' empty' }}"></i>
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        <span class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $evaluation->note_delai ? '' : ' empty' }}"></i>
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        <span class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $evaluation->note_securite ? '' : ' empty' }}"></i>
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        <span class="note-circle {{ $evaluation->note_badge }}">
                                            {{ $evaluation->note_moyenne }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $evaluation->date_formatted }}</div>
                                        <small class="text-muted">
                                            par {{ $evaluation->evaluateur->nom ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-view" title="Voir le détail" onclick="viewEvaluation({{ $evaluation->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editEvaluation({{ $evaluation->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteEvaluation({{ $evaluation->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-star fa-2x d-block mb-2"></i>
                                        Aucune évaluation trouvée.
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
    <div class="modal fade" id="addEvaluationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-star me-2"></i>Nouvelle évaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addEvaluationForm">
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Qualité <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <select name="note_qualite" class="form-select" required>
                                            <option value="">Sélectionner</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">
                                                    {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Délai <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <select name="note_delai" class="form-select" required>
                                            <option value="">Sélectionner</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">
                                                    {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Sécurité <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <select name="note_securite" class="form-select" required>
                                            <option value="">Sélectionner</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">
                                                    {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Commentaires</label>
                            <textarea name="commentaires" class="form-control" rows="3" placeholder="Détails sur l'évaluation..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date d'évaluation <span class="text-danger">*</span></label>
                            <input type="date" name="date_evaluation" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Créer l'évaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editEvaluationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-star me-2"></i>Modifier l'évaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEvaluationForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="evaluation_id" id="editEvaluationId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sous-traitant <span class="text-danger">*</span></label>
                                    <select name="sous_traitant_id" id="editSousTraitant" class="form-select" required>
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Qualité <span class="text-danger">*</span></label>
                                    <select name="note_qualite" id="editNoteQualite" class="form-select" required>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Délai <span class="text-danger">*</span></label>
                                    <select name="note_delai" id="editNoteDelai" class="form-select" required>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Sécurité <span class="text-danger">*</span></label>
                                    <select name="note_securite" id="editNoteSecurite" class="form-select" required>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }} - {{ ['Médiocre', 'Moyen', 'Bon', 'Très bon', 'Excellent'][$i-1] }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Commentaires</label>
                            <textarea name="commentaires" id="editCommentaires" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date d'évaluation <span class="text-danger">*</span></label>
                            <input type="date" name="date_evaluation" id="editDate" class="form-control" required>
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
        MODALE DÉTAIL
    ============================================ --}}
    <div class="modal fade" id="viewEvaluationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-star me-2"></i>Détail de l'évaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewEvaluationContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let addModal, editModal, viewModal;

        $(document).ready(function() {
            addModal = new bootstrap.Modal(document.getElementById('addEvaluationModal'));
            editModal = new bootstrap.Modal(document.getElementById('editEvaluationModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewEvaluationModal'));

            // Bouton Ajouter
            $('#btnAddEvaluation').on('click', function() {
                $('#addEvaluationForm')[0].reset();
                addModal.show();
            });

            // Formulaire Ajout
            $('#addEvaluationForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Création...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.evaluations-sous-traitants.store") }}',
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
                        btn.html('<i class="fas fa-save"></i> Créer l\'évaluation').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editEvaluationForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editEvaluationId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.evaluations-sous-traitants.update", "") }}/' + id,
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
            $('#filterNote').on('change', function() {
                filterTable();
            });

            // Rafraîchir
            $('#btnRefresh').on('click', function() {
                window.location.reload();
            });
        });

        function filterTable() {
            const search = $('#searchInput').val().toLowerCase();
            const note = $('#filterNote').val();

            $('#evaluationsTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowNote = parseInt(row.data('note'));

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (note !== 'all' && rowNote !== parseInt(note)) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#evaluationCount').text($('#evaluationsTableBody tr:visible').length);
        }

        function editEvaluation(id) {
            $.ajax({
                url: '{{ route("admin.evaluations-sous-traitants.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.evaluation) {
                        const e = response.evaluation;
                        $('#editEvaluationId').val(e.id);
                        $('#editSousTraitant').val(e.sous_traitant_id);
                        $('#editProjet').val(e.projet_id || '');
                        $('#editNoteQualite').val(e.note_qualite);
                        $('#editNoteDelai').val(e.note_delai);
                        $('#editNoteSecurite').val(e.note_securite);
                        $('#editCommentaires').val(e.commentaires || '');
                        $('#editDate').val(e.date_evaluation);
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function viewEvaluation(id) {
            $('#viewEvaluationContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        `);
            viewModal.show();

            $.ajax({
                url: '{{ route("admin.evaluations-sous-traitants.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.evaluation) {
                        const e = response.evaluation;
                        const moyenne = ((e.note_qualite + e.note_delai + e.note_securite) / 3).toFixed(1);

                        $('#viewEvaluationContent').html(`
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Sous-traitant</strong>
                                <p class="mb-0">${e.sous_traitant?.nom_entreprise || 'N/A'}</p>
                            </div>
                            <div class="col-6">
                                <strong>Projet</strong>
                                <p class="mb-0">${e.projet?.nom || 'N/A'}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-center">
                                <div class="note-circle ${e.note_qualite >= 4 ? 'success' : e.note_qualite >= 3 ? 'primary' : e.note_qualite >= 2 ? 'warning' : 'danger'}" style="width:50px;height:50px;font-size:1.2rem;margin:0 auto;">
                                    ${e.note_qualite}
                                </div>
                                <small class="text-muted">Qualité</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="note-circle ${e.note_delai >= 4 ? 'success' : e.note_delai >= 3 ? 'primary' : e.note_delai >= 2 ? 'warning' : 'danger'}" style="width:50px;height:50px;font-size:1.2rem;margin:0 auto;">
                                    ${e.note_delai}
                                </div>
                                <small class="text-muted">Délai</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="note-circle ${e.note_securite >= 4 ? 'success' : e.note_securite >= 3 ? 'primary' : e.note_securite >= 2 ? 'warning' : 'danger'}" style="width:50px;height:50px;font-size:1.2rem;margin:0 auto;">
                                    ${e.note_securite}
                                </div>
                                <small class="text-muted">Sécurité</small>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <div class="note-circle ${moyenne >= 4.5 ? 'success' : moyenne >= 3.5 ? 'primary' : moyenne >= 2.5 ? 'warning' : 'danger'}" style="width:60px;height:60px;font-size:1.5rem;margin:0 auto;">
                                ${moyenne}
                            </div>
                            <small class="text-muted">Moyenne générale</small>
                        </div>
                        ${e.commentaires ? `
                            <div class="mb-3">
                                <strong>Commentaires</strong>
                                <p class="mb-0">${e.commentaires}</p>
                            </div>
                        ` : ''}
                        <div class="row">
                            <div class="col-6">
                                <strong>Date</strong>
                                <p class="mb-0">${e.date_evaluation}</p>
                            </div>
                            <div class="col-6">
                                <strong>Évaluateur</strong>
                                <p class="mb-0">${e.evaluateur?.nom || 'N/A'}</p>
                            </div>
                        </div>
                    `);
                    }
                },
                error: function() {
                    $('#viewEvaluationContent').html(`
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x d-block mb-2"></i>
                        Erreur lors du chargement des données
                    </div>
                `);
                }
            });
        }

        function deleteEvaluation(id) {
            Swal.fire({
                title: 'Supprimer l\'évaluation',
                text: 'Voulez-vous vraiment supprimer cette évaluation ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.evaluations-sous-traitants.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des évaluations des sous-traitants chargée');
    </script>
@endsection
