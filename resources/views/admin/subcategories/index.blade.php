@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <style>
        .select2-container {
            z-index: 100000;
        }
    </style>
    <!-- EDIT Modal -->
    <div class="modal fade" id="subcategoryEditModal" tabindex="1" aria-labelledby="subcategoryEditModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subcategoryEditModalLabel">Dettagli Sottocategoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label id="subcategory-name-label">Nome Sottocategoria</label>
                        <input id="edit-name-input" name="name" class="form form-control">
                        <input id="details-id" name="id" type="hidden" class="subcategory-id-hidden">
                    </div>
                    <div class="w-100 mt-3 d-flex flex-column">
                        <label>Categoria</label>
                        <select id="edit-category-select" class="select2">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3 alert d-flex flex-row align-items-center justify-content-between" role="alert"
                         id="edit-alert">
                        <p id="edit-alert-p" class="my-auto"></p>
                        <div class="spinner-border text-secondary" role="status" id="edit-alert-spinner"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="handleEditSubmit()">SALVA</button>
                    <button type="button" class="btn btn-outline-primary" id="toggle-visibility-btn"
                            onclick="handleToggleVisibility()"></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        CHIUDI
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- ADD Modal -->
    <div class="modal fade" id="subcategoryAddModal" tabindex="1" aria-labelledby="subcategoryAddModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subcategoryAddModal">Aggiungi Sottocategoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label>Nome Sottocategoria</label>
                        <input id="add-name-input" name="name" class="form form-control">
                    </div>
                    <div class="w-100 mt-3 d-flex flex-column">
                        <label>Categoria</label>
                        <select id="add-category-select" class="select2" data-live-search="true">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3 alert d-flex flex-row align-items-center justify-content-between" role="alert"
                         id="add-alert">
                        <p id="add-alert-p" class="my-auto"></p>
                        <div class="spinner-border text-secondary" role="status" id="add-alert-spinner"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="handleAddSubmit()">SALVA</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        CHIUDI
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD Modal -->

    <div class="w-100 h-100 p-5">
        <table id="table" class="w-100 h-100 bg-white"></table>
    </div>

    <script>
        let selected_subcat;
        let new_cat;

        $('.select2').select2();

        let editVisibilityBtn = $('#toggle-visibility-btn');
        const checkVisibilityButtonText = () => {
            if (selected_subcat.visible) {
                editVisibilityBtn.text('NASCONDI');
            } else {
                editVisibilityBtn.text('MOSTRA');
            }
        }

        let editNameInput = $('#edit-name-input');
        let editCategorySelect = $('#edit-category-select');
        let subcategoryIdHidden = $('.subcategory-id-hidden');
        const onDetailsModalOpen = (id, name, visible, category_id) => {
            selected_subcat = {
                id, name, visible, category_id
            }
            console.log(category_id);
            editNameInput.val(selected_subcat.name);
            editCategorySelect.val(selected_subcat.category_id);
            editCategorySelect.trigger('change');
            subcategoryIdHidden.val(selected_subcat.id);
            checkVisibilityButtonText();
        }

        let editAlert = $('#edit-alert');
        let editAlertP = $('#edit-alert-p');
        let editAlertSpinner = $('#edit-alert-spinner');
        editAlertSpinner.hide();
        const handleEditSubmit = () => {
            axios('{{ route('subcategories-edit') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    id: selected_subcat.id,
                    name: editNameInput.val(),
                }
            }).then((res) => {
                if (res.status === 200) {
                    if (res.data.success === true) {
                        editAlertP.text('Sottocategoria aggiornata correttamente');
                        editAlert.removeClass('alert-danger');
                        editAlert.addClass('alert-success');
                        editAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiornare la Sottocategoria';
                    }
                } else {
                    throw 'Impossibile aggiornare la Sottocategoria';
                }
            }).catch((msg) => {
                editAlertP.text(msg);
                editAlert.addClass('alert-danger');
                editAlert.removeClass('alert-success');
            });
        }

        let addNameInput = $('#add-name-input');
        let addAlert = $('#add-alert');
        let addAlertP = $('#add-alert-p');
        let addAlertSpinner = $('#add-alert-spinner');
        addAlertSpinner.hide();

        const handleAddSubmit = () => {
            axios('{{ route('subcategories-create') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    name: addNameInput.val(),
                }
            }).then((res) => {
                console.log(res)
                if (res.status === 200) {
                    if (res.data.success === true) {
                        addAlertP.text('Sottocategoria aggiunta correttamente');
                        addAlert.removeClass('alert-danger');
                        addAlert.addClass('alert-success');
                        addAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiungere la Sottocategoria';
                    }
                } else {
                    throw 'Impossibile aggiungere la Sottocategoria';
                }
            }).catch((msg) => {
                addAlertP.text(msg);
                addAlert.addClass('alert-danger');
                addAlert.removeClass('alert-success');
            });

        }

        const handleToggleVisibility = () => {
            editVisibilityBtn.attr('disabled', '');
            axios('{{ route('subcategories-toggle-visibility') }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    id: selected_subcat.id
                }
            }).then((res) => {
                if (res.status === 200) {
                    if (res.data.success === true) {
                        selected_subcat.visible = !selected_subcat.visible;
                        checkVisibilityButtonText();
                        editAlertP.text('Sottocategoria aggiornata correttamente');
                        editAlert.removeClass('alert-danger');
                        editAlert.addClass('alert-success');
                        editAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiornare la Sottocategoria';
                    }
                } else {
                    throw 'Impossibile aggiornare la Sottocategoria';
                }
            }).catch((msg) => {
                editAlertP.text(msg);
                editAlert.addClass('alert-danger');
                editAlert.removeClass('alert-success');
            }).finally(() => {
                editVisibilityBtn.removeAttr('disabled');
            });
        }

        const actionsFormatter = (value, row) => {
            return ('<button onclick="onDetailsModalOpen(' + row.id + ',\'' + row.name + '\',' + row.visible + "," + row.category_id + ')" data-bs-toggle="modal" data-bs-target="#subcategoryEditModal" class="btn"><img src="{{ asset("images/settings-icon.svg") }}" alt="dettagli"></img></button>');
        }

        const visibilityColumnFormatter = (value) => {
            let src = "{{ asset("images/visibility-icon.svg") }}";
            if (!value) {
                src = "{{ asset("images/visibilityoff-icon.svg") }}";
            }

            return ('<img alt="visibility" src="' + src + '">');
        }

        var subcategoryAddModal = new bootstrap.Modal(document.getElementById("subcategoryAddModal"), {});
        const openAddModal = () => {
            subcategoryAddModal.show();
        }

        function actionBarButtons() {
            return {
                btnAdd: {
                    text: 'Aggiungi Sottocategoria',
                    icon: 'fa fa-plus',
                    event: function () {
                        openAddModal();
                    },
                    attributes: {
                        title: 'Aggiungi Sottocategoria'
                    }
                }
            }
        }

        $('#table').bootstrapTable({
            data: {{ Js::from($subcategories) }},
            search: true,
            showColumns: true,
            searchHighlight: true,
            locale: 'en-US',
            buttons: actionBarButtons,
            columns: [{
                field: 'name',
                title: 'Nome Sottocategoria'
            }, {
                field: 'category_id',
                title: 'Categoria',
                class: 'text-center',
            }, {
                field: 'visible',
                title: 'Attivo',
                class: 'text-center w-10',
                formatter: visibilityColumnFormatter,
            }, {
                field: 'actions',
                title: 'Azioni',
                class: 'text-center w-10',
                formatter: actionsFormatter,
            }]
        })
    </script>
@endsection
