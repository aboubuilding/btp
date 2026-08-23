{{-- resources/views/admin/ecritures-comptables/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Écritures comptables')

@section('page_title', 'Écritures comptables')
@section('page_icon', 'fa-pen-to-square')

@section('breadcrumb')
    <li class="active">Écritures comptables</li>
@endsection

@section('page_actions')
    <button class="btn btn-primary btn-sm" id="btnAddEcriture">
        <i class="fas fa-plus"></i> Nouvelle écriture
    </button>
    <button class="btn btn-outline-secondary btn-sm" id="btnRefresh">
        <i class="fas fa-sync-alt"></i>
    </button>
@endsection

@section('css')
    <style>
        .ecriture-avatar {
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
            background: linear-gradient(135deg, #2b6cb0, #1a4d7a);
        }

        .statut-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .statut-badge.warning { background: rgba(183, 149, 11, 0.12); color: #b7950b; }
        .statut-badge.success { background: rgba(45, 143, 94, 0.12); color: #2d8f5e; }
        .statut-badge.secondary { background: rgba(107, 122, 143, 0.12); color: #6b7a8f; }

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
        .table-actions .btn-action.btn-validate { color: #2d8f5e; }
        .table-actions .btn-action.btn-validate:hover { background: rgba(45, 143, 94, 0.08); border-color: #2d8f5e; }
        .table-actions .btn-action.btn-counter { color: #c0392b; }
        .table-actions .btn-action.btn-counter:hover { background: rgba(192, 57, 43, 0.08); border-color: #c0392b; }
        .table-actions .btn-action.btn-view { color: #d4a745; }
        .table-actions .btn-action.btn-view:hover { background: rgba(212, 167, 69, 0.08); border-color: #d4a745; }
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
        .stat-card-mini .stat-number.orange { color: #b7950b; }
        .stat-card-mini .stat-number.blue { color: #2b6cb0; }
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

        .ligne-item {
            padding: 8px 12px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid var(--btp-border);
            margin-bottom: 8px;
        }
        .ligne-item:last-child {
            margin-bottom: 0;
        }

        .total-line {
            padding-top: 12px;
            border-top: 2px solid var(--btp-border);
            font-weight: 700;
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
                <div class="stat-number orange">{{ $stats['brouillons'] ?? 0 }}</div>
                <div class="stat-label">Brouillons</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card-mini">
                <div class="stat-number green">{{ $stats['validees'] ?? 0 }}</div>
                <div class="stat-label">Validées</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number blue">
                    {{ number_format(($stats['total_debit'] ?? 0) / 1000000, 1, ',', ' ') }}M
                </div>
                <div class="stat-label">Total débit</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-number accent">
                    {{ number_format(($stats['total_credit'] ?? 0) / 1000000, 1, ',', ' ') }}M
                </div>
                <div class="stat-label">Total crédit</div>
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
                            <i class="fas fa-pen-to-square"></i>
                            Liste des écritures
                            <span class="badge bg-secondary ms-2" id="ecritureCount">{{ count($ecritures) }}</span>
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
                        <table class="table table-hover align-middle" id="ecrituresTable">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Numéro</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Référence</th>
                                <th>Débit</th>
                                <th>Crédit</th>
                                <th>Statut</th>
                                <th style="width: 200px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ecrituresTableBody">
                            @forelse($ecritures as $ecriture)
                                <tr data-id="{{ $ecriture['id'] }}" data-statut="{{ $ecriture['statut'] }}">
                                    <td>
                                        <div class="ecriture-avatar">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $ecriture['numero_ecriture'] }}</div>
                                        <small class="text-muted">
                                            {{ $ecriture['lignes_count'] ?? 0 }} lignes
                                        </small>
                                    </td>
                                    <td>
                                        {{ $ecriture['date_ecriture'] ? \Carbon\Carbon::parse($ecriture['date_ecriture'])->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        <div>{{ Str::limit($ecriture['description'] ?? 'Sans description', 50) }}</div>
                                        <small class="text-muted">
                                            {{ $ecriture['exercice_fiscal']['nom'] ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($ecriture['type_reference'])
                                            <span class="type-badge">
                                                {{ $ecriture['type_reference_label'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($ecriture['total_debit'] ?? 0, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger">
                                            {{ number_format($ecriture['total_credit'] ?? 0, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="statut-badge {{ $ecriture['statut_badge'] }}">
                                            {{ $ecriture['statut_label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions d-flex gap-1">
                                            <button type="button" class="btn-action btn-view" title="Voir les lignes" onclick="viewLignes({{ $ecriture['id'] }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($ecriture['statut'] === 'brouillon')
                                                <button type="button" class="btn-action btn-edit" title="Modifier" onclick="editEcriture({{ $ecriture['id'] }})">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-validate" title="Valider" onclick="validerEcriture({{ $ecriture['id'] }})">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-delete" title="Supprimer" onclick="deleteEcriture({{ $ecriture['id'] }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @elseif($ecriture['statut'] === 'valide')
                                                <button type="button" class="btn-action btn-counter" title="Contre-passer" onclick="contrePasserEcriture({{ $ecriture['id'] }})">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-pen-to-square fa-2x d-block mb-2"></i>
                                        Aucune écriture trouvée.
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
    <div class="modal fade" id="ecritureModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ecritureModalTitle">
                        <i class="fas fa-pen-to-square me-2"></i>
                        Nouvelle écriture
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="ecritureForm">
                    @csrf
                    <input type="hidden" name="ecriture_id" id="ecritureId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Exercice fiscal <span class="text-danger">*</span></label>
                                    <select name="exercice_fiscal_id" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($exercices as $exercice)
                                            <option value="{{ $exercice->id }}">{{ $exercice->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Date d'écriture <span class="text-danger">*</span></label>
                                    <input type="date" name="date_ecriture" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Type de référence</label>
                                    <select name="type_reference" class="form-select">
                                        <option value="">Aucun</option>
                                        @foreach($typesReference as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Description de l'écriture"></textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-list me-2"></i>Lignes d'écriture
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="btnAddLigne">
                                <i class="fas fa-plus"></i> Ajouter une ligne
                            </button>
                        </h6>

                        <div id="lignesContainer">
                            <!-- Les lignes seront ajoutées dynamiquement -->
                            <div class="text-center text-muted py-3" id="emptyLignes">
                                <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                Cliquez sur "Ajouter une ligne" pour commencer
                            </div>
                        </div>

                        <div class="row total-line mt-3">
                            <div class="col-6 text-end fw-bold">Total Débit :</div>
                            <div class="col-3 text-end text-success fw-bold" id="totalDebitDisplay">0</div>
                            <div class="col-3 text-end text-danger fw-bold" id="totalCreditDisplay">0</div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <span id="equilibreStatus" class="badge bg-warning">En attente</span>
                            </div>
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

    {{-- ============================================
        MODALE VISUALISATION DES LIGNES
    ============================================ --}}
    <div class="modal fade" id="viewLignesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-list me-2"></i>Lignes de l'écriture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewLignesContent">
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
        let ecritureModal, viewLignesModal;
        let ligneIndex = 0;

        $(document).ready(function() {
            ecritureModal = new bootstrap.Modal(document.getElementById('ecritureModal'));
            viewLignesModal = new bootstrap.Modal(document.getElementById('viewLignesModal'));

            // Bouton Ajouter
            $('#btnAddEcriture').on('click', function() {
                resetForm();
                $('#ecritureModalTitle').html('<i class="fas fa-pen-to-square me-2"></i>Nouvelle écriture');
                $('#formMethod').val('POST');
                $('#ecritureForm').attr('action', '{{ route("admin.ecritures-comptables.store") }}');
                $('#btnSubmit').html('<i class="fas fa-save"></i> Enregistrer');
                ecritureModal.show();
                generateNumeroEcriture();
            });

            // Ajouter une ligne
            $('#btnAddLigne').on('click', function() {
                addLigne();
            });

            // Formulaire
            $('#ecritureForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmit');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...').prop('disabled', true);

                // Vérifier l'équilibre
                if (!$('#equilibreStatus').hasClass('bg-success')) {
                    toastr.warning('L\'écriture doit être équilibrée (Total Débit = Total Crédit)');
                    btn.html('<i class="fas fa-save"></i> Enregistrer').prop('disabled', false);
                    return;
                }

                const formData = new FormData(this);
                const action = $(this).attr('action');
                const method = $('#formMethod').val();

                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            ecritureModal.hide();
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

            $('#ecrituresTableBody tr').each(function() {
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

            $('#ecritureCount').text($('#ecrituresTableBody tr:visible').length);
        }

        function resetForm() {
            $('#ecritureForm')[0].reset();
            $('#lignesContainer').empty().append(`
            <div class="text-center text-muted py-3" id="emptyLignes">
                <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                Cliquez sur "Ajouter une ligne" pour commencer
            </div>
        `);
            ligneIndex = 0;
            updateTotals();
        }

        function addLigne(data = null) {
            const container = $('#lignesContainer');
            $('#emptyLignes').remove();

            const index = ligneIndex++;
            const html = `
            <div class="ligne-item" id="ligne-${index}">
                <div class="row g-2">
                    <div class="col-md-5">
                        <select name="lignes[${index}][compte_id]" class="form-select form-select-sm" required>
                            <option value="">Compte</option>
                            ${comptesOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lignes[${index}][debit]" class="form-control form-control-sm ligne-debit" placeholder="Débit" min="0" step="100" value="${data?.debit || ''}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lignes[${index}][credit]" class="form-control form-control-sm ligne-credit" placeholder="Crédit" min="0" step="100" value="${data?.credit || ''}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="lignes[${index}][description]" class="form-control form-control-sm" placeholder="Description" value="${data?.description || ''}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-ligne" onclick="removeLigne(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

            container.append(html);

            // Événements pour mettre à jour les totaux
            container.find(`#ligne-${index} .ligne-debit, #ligne-${index} .ligne-credit`).on('input', function() {
                updateTotals();
            });

            updateTotals();
        }

        function removeLigne(index) {
            $(`#ligne-${index}`).remove();
            updateTotals();
            if ($('#lignesContainer .ligne-item').length === 0) {
                $('#lignesContainer').append(`
                <div class="text-center text-muted py-3" id="emptyLignes">
                    <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                    Cliquez sur "Ajouter une ligne" pour commencer
                </div>
            `);
            }
        }

        function updateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            $('.ligne-debit').each(function() {
                const val = parseFloat($(this).val()) || 0;
                totalDebit += val;
            });

            $('.ligne-credit').each(function() {
                const val = parseFloat($(this).val()) || 0;
                totalCredit += val;
            });

            $('#totalDebitDisplay').text(numberFormat(totalDebit));
            $('#totalCreditDisplay').text(numberFormat(totalCredit));

            const status = $('#equilibreStatus');
            if (totalDebit === totalCredit && totalDebit > 0) {
                status.removeClass('bg-warning').addClass('bg-success').text('✓ Équilibrée');
            } else if (totalDebit > 0 || totalCredit > 0) {
                status.removeClass('bg-success').addClass('bg-danger').text('✗ Non équilibrée');
            } else {
                status.removeClass('bg-success bg-danger').addClass('bg-warning').text('En attente');
            }
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('fr-FR').format(number);
        }

        // ============================================
        // ACTIONS
        // ============================================

        function editEcriture(id) {
            $.ajax({
                url: '{{ route("admin.ecritures-comptables.index") }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.ecriture) {
                        const e = response.ecriture;
                        resetForm();
                        $('#ecritureModalTitle').html('<i class="fas fa-edit me-2"></i>Modifier l\'écriture');
                        $('#formMethod').val('PUT');
                        $('#ecritureForm').attr('action', '{{ route("admin.ecritures-comptables.update", "") }}/' + id);
                        $('#ecritureId').val(e.id);
                        $('#btnSubmit').html('<i class="fas fa-save"></i> Mettre à jour');

                        $('select[name="exercice_fiscal_id"]').val(e.exercice_fiscal_id);
                        $('input[name="date_ecriture"]').val(e.date_ecriture);
                        $('select[name="type_reference"]').val(e.type_reference || '');
                        $('textarea[name="description"]').val(e.description || '');

                        // Charger les lignes
                        $.ajax({
                            url: '{{ route("admin.ecritures-comptables.lignes", "") }}/' + id,
                            type: 'GET',
                            success: function(response) {
                                if (response.success && response.data.lignes) {
                                    response.data.lignes.forEach(ligne => {
                                        addLigne({
                                            compte_id: ligne.compte_id,
                                            debit: ligne.debit,
                                            credit: ligne.credit,
                                            description: ligne.description
                                        });
                                        // Sélectionner le compte
                                        $(`select[name="lignes[${ligneIndex-1}][compte_id]"]`).val(ligne.compte_id);
                                    });
                                    updateTotals();
                                }
                            }
                        });

                        ecritureModal.show();
                    }
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des données');
                }
            });
        }

        function viewLignes(id) {
            $('#viewLignesContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        `);
            viewLignesModal.show();

            $.ajax({
                url: '{{ route("admin.ecritures-comptables.lignes", "") }}/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        let html = `
                        <div class="mb-3">
                            <strong>${data.ecriture.numero_ecriture}</strong>
                            <span class="badge ${data.ecriture.statut_badge} ms-2">${data.ecriture.statut_label}</span>
                            <p class="text-muted small">${data.ecriture.description || 'Sans description'}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Compte</th>
                                        <th>Libellé</th>
                                        <th class="text-end">Débit</th>
                                        <th class="text-end">Crédit</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                        data.lignes.forEach(ligne => {
                            html += `
                            <tr>
                                <td><strong>${ligne.compte?.code || 'N/A'}</strong></td>
                                <td>${ligne.compte?.nom || ''}</td>
                                <td class="text-end text-success">${ligne.debit > 0 ? numberFormat(ligne.debit) : '-'}</td>
                                <td class="text-end text-danger">${ligne.credit > 0 ? numberFormat(ligne.credit) : '-'}</td>
                            </tr>
                        `;
                        });

                        html += `
                                    <tr class="fw-bold">
                                        <td colspan="2" class="text-end">TOTAUX</td>
                                        <td class="text-end text-success">${numberFormat(data.ecriture.total_debit || 0)}</td>
                                        <td class="text-end text-danger">${numberFormat(data.ecriture.total_credit || 0)}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;

                        $('#viewLignesContent').html(html);
                    }
                },
                error: function() {
                    $('#viewLignesContent').html(`
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x d-block mb-2"></i>
                        Erreur lors du chargement des données
                    </div>
                `);
                }
            });
        }

        function validerEcriture(id) {
            Swal.fire({
                title: 'Valider l\'écriture',
                text: 'Voulez-vous valider cette écriture ? Elle ne pourra plus être modifiée.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2d8f5e',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.ecritures-comptables.valider", "") }}/' + id,
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
                            toastr.error('Erreur lors de la validation');
                        }
                    });
                }
            });
        }

        function contrePasserEcriture(id) {
            Swal.fire({
                title: 'Contre-passer l\'écriture',
                text: 'Voulez-vous contre-passer cette écriture ? Une nouvelle écriture inverse sera créée.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, contre-passer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.ecritures-comptables.contre-passer", "") }}/' + id,
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
                            toastr.error('Erreur lors de la contre-passation');
                        }
                    });
                }
            });
        }

        function deleteEcriture(id) {
            Swal.fire({
                title: 'Supprimer l\'écriture',
                text: 'Voulez-vous vraiment supprimer cette écriture ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6b7a8f',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.ecritures-comptables.destroy", "") }}/' + id,
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

        function generateNumeroEcriture() {
            $.ajax({
                url: '{{ route("admin.ecritures-comptables.generate-numero") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Ajouter le numéro dans un champ hidden ou l'afficher
                    }
                }
            });
        }

        // ============================================
        // COMPTES OPTIONS (généré depuis PHP)
        // ============================================
        const comptesOptions = `
        <option value="">Sélectionner un compte</option>
        @foreach($comptes as $compte)
        <option value="{{ $compte['id'] }}" style="padding-left: {{ ($compte['level'] ?? 0) * 20 }}px;">
                {{ $compte['code'] }} - {{ $compte['nom'] }}
        </option>
@if(isset($compte['children']))
        @foreach($compte['children'] as $enfant)
        <option value="{{ $enfant['id'] }}" style="padding-left: {{ (($enfant['level'] ?? 0) + 1) * 20 }}px;">
                        {{ $enfant['code'] }} - {{ $enfant['nom'] }}
        </option>
@endforeach
        @endif
        @endforeach
        `;

        console.log('✅ Gestion des écritures comptables chargée');
    </script>
@endsection
