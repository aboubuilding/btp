{{-- resources/views/admin/paiements-sous-traitants/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Paiements sous-traitants')

@section('page_title', 'Paiements sous-traitants')
@section('page_icon', 'fa-money-bill-transfer')

@section('breadcrumb')
    <li class="active">Paiements sous-traitants</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddPaiement">
        <i class="fas fa-plus"></i> Nouveau paiement
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .paiement-avatar {
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
            background: linear-gradient(135deg, #2d8f5e, #1a6e3e);
        }

        .mode-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .mode-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .mode-badge.primary { background: rgba(43, 108, 176, 0.12); color: #2b6cb0; }
        .mode-badge.info { background: rgba(43, 108, 176, 0.08); color: #2b6cb0; }
        .mode-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .mode-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

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
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total paiements</div>
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
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['par_mois'][0]['total'] ?? 0 }}</div>
                <div class="stat-label">Ce mois</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number">{{ count($stats['par_mode'] ?? []) }}</div>
                <div class="stat-label">Modes utilisés</div>
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
                            <i class="fas fa-money-bill-transfer"></i>
                            Liste des paiements
                            <span class="badge bg-secondary ms-2" id="paiementCount">{{ count($paiements) }}</span>
                        </h6>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="filterMode" style="width: auto; height: 38px;">
                                    <option value="all">Tous les modes</option>
                                    @foreach($modes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="paiementsTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Facture</th>
                                <th>Sous-traitant</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Mode</th>
                                <th>Référence</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="paiementsTableBody">
                            @forelse($paiements as $paiement)
                                <tr data-id="{{ $paiement->id }}" data-mode="{{ $paiement->mode }}">
                                    <td>
                                        <div class="paiement-avatar">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $paiement->facture->numero_facture ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $paiement->facture->statut_label ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $paiement->facture->facturable->nom_entreprise ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $paiement->facture->facturable->personne_contact ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $paiement->montant_formatted }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $paiement->date_formatted }}</div>
                                    </td>
                                    <td>
                                        <span class="mode-badge {{ $paiement->mode_badge }}">
                                            {{ $paiement->mode_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $paiement->reference ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editPaiement({{ $paiement->id }})">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deletePaiement({{ $paiement->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-money-bill-wave fa-2x d-block mb-2"></i>
                                        Aucun paiement trouvé.
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
    <div class="modal fade" id="addPaiementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Enregistrer un paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPaiementForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Facture <span class="text-danger">*</span></label>
                            <select name="facture_sous_traitant_id" class="form-select" required>
                                <option value="">Sélectionner une facture</option>
                                @foreach($factures as $facture)
                                    <option value="{{ $facture->id }}" data-montant="{{ $facture->montant_ttc }}" data-reste="{{ $facture->reste_a_payer ?? $facture->montant_ttc }}">
                                        {{ $facture->numero_facture }} -
                                        {{ $facture->facturable->nom_entreprise ?? 'N/A' }} -
                                        {{ number_format($facture->reste_a_payer ?? $facture->montant_ttc, 0, ',', ' ') }} FCFA
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Seules les factures impayées sont affichées</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" id="addMontant" class="form-control" placeholder="0" min="1" step="1000" required>
                            <small class="text-muted" id="resteInfo">Reste à payer: </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date de paiement <span class="text-danger">*</span></label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                            <select name="mode" class="form-select" required>
                                <option value="">Sélectionner un mode</option>
                                @foreach($modes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" class="form-control" placeholder="Numéro de chèque, transaction, etc.">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="btnAddSubmit">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================
        MODALE ÉDITION
    ============================================ --}}
    <div class="modal fade" id="editPaiementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Modifier le paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPaiementForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="paiement_id" id="editPaiementId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Facture <span class="text-danger">*</span></label>
                            <select name="facture_sous_traitant_id" id="editFacture" class="form-select" required>
                                <option value="">Sélectionner une facture</option>
                                @foreach($factures as $facture)
                                    <option value="{{ $facture->id }}" data-montant="{{ $facture->montant_ttc }}">
                                        {{ $facture->numero_facture }} -
                                        {{ $facture->facturable->nom_entreprise ?? 'N/A' }} -
                                        {{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCFA
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" id="editMontant" class="form-control" min="1" step="1000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date de paiement <span class="text-danger">*</span></label>
                            <input type="date" name="date_paiement" id="editDate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                            <select name="mode" id="editMode" class="form-select" required>
                                @foreach($modes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" id="editReference" class="form-control" placeholder="Numéro de chèque, transaction, etc.">
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
            addModal = new bootstrap.Modal(document.getElementById('addPaiementModal'));
            editModal = new bootstrap.Modal(document.getElementById('editPaiementModal'));

            // Bouton Ajouter
            $('#btnAddPaiement').on('click', function() {
                $('#addPaiementForm')[0].reset();
                $('#addMontant').val('');
                $('#resteInfo').text('Reste à payer: ');
                addModal.show();
            });

            // Mise à jour du reste à payer
            $('select[name="facture_sous_traitant_id"]').on('change', function() {
                const selected = $(this).find('option:selected');
                const reste = selected.data('reste');
                if (reste) {
                    $('#resteInfo').text('Reste à payer: ' + numberFormat(reste) + ' FCFA');
                    $('#addMontant').attr('max', reste);
                } else {
                    const montant = selected.data('montant');
                    if (montant) {
                        $('#resteInfo').text('Reste à payer: ' + numberFormat(montant) + ' FCFA');
                        $('#addMontant').attr('max', montant);
                    }
                }
            });

            // Formulaire Ajout
            $('#addPaiementForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnAddSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.paiements-sous-traitants.store") }}',
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
                            toastr.error('Erreur lors de l\'enregistrement');
                        }
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-save"></i> Enregistrer').prop('disabled', false);
                    }
                });
            });

            // Formulaire Édition
            $('#editPaiementForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editPaiementId').val();
                const btn = $('#btnEditSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.paiements-sous-traitants.update", "") }}/' + id,
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
            $('#filterMode').on('change', function() {
                filterTable();
            });

            // Rafraîchir
            $('#btnRefresh').on('click', function() {
                window.location.reload();
            });
        });

        function filterTable() {
            const search = $('#searchInput').val().toLowerCase();
            const mode = $('#filterMode').val();

            $('#paiementsTableBody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                const rowMode = row.data('mode');

                let show = true;

                if (search && !text.includes(search)) {
                    show = false;
                }

                if (mode !== 'all' && rowMode !== mode) {
                    show = false;
                }

                row.toggle(show);
            });

            $('#paiementCount').text($('#paiementsTableBody tr:visible').length);
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('fr-FR').format(number);
        }

        function editPaiement(id) {
            $.ajax({
                url: '{{ route("admin.paiements-sous-traitants.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.paiement) {
                        const p = response.paiement;
                        $('#editPaiementId').val(p.id);
                        $('#editFacture').val(p.facture_sous_traitant_id);
                        $('#editMontant').val(p.montant);
                        $('#editDate').val(p.date_paiement);
                        $('#editMode').val(p.mode);
                        $('#editReference').val(p.reference || '');
                        editModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function deletePaiement(id) {
            Swal.fire({
                title: 'Supprimer le paiement',
                text: 'Voulez-vous vraiment supprimer ce paiement ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.paiements-sous-traitants.destroy", "") }}/' + id,
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

        console.log('✅ Gestion des paiements sous-traitants chargée');
    </script>
@endsection
