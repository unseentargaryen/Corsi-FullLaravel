@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <title>ADMIN DASHBOARD: CATEGORIE</title>
    <!-- EDIT Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="1" aria-labelledby="categoryEditModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryEditModalLabel">Dettagli Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label id="category-name-label">Nome Categoria</label>
                        <input id="edit-name-input" name="name" class="form form-control">
                        <input id="details-id" name="id" type="hidden" class="category-id-hidden">
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
    <div class="modal fade" id="categoryAddModal" tabindex="1" aria-labelledby="categoryAddModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryAddModal">Aggiungi Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label>Nome Categoria</label>
                        @csrf
                        <input id="name" name="name" class="form form-control">
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

    <div class="w-100 h-100 p-3">
        <table id="table" class="w-100 h-100 bg-white"></table>
    </div>

    <script>
        let selected_cat;
        let new_cat;

        let editVisibilityBtn = $('#toggle-visibility-btn');
        const checkVisibilityButtonText = () => {
            if (selected_cat.visible) {
                editVisibilityBtn.text('NASCONDI');
            } else {
                editVisibilityBtn.text('MOSTRA');
            }
        }

        let editNameInput = $('#edit-name-input');
        let categoryIdHidden = $('.category-id-hidden');
        const onDetailsModalOpen = (id, name, visible) => {
            selected_cat = {
                id, name, visible
            }
            editNameInput.val(selected_cat.name);
            categoryIdHidden.val(selected_cat.id);
            checkVisibilityButtonText();
        }

        let editAlert = $('#edit-alert');
        let editAlertP = $('#edit-alert-p');
        let editAlertSpinner = $('#edit-alert-spinner');
        editAlertSpinner.hide();
        const handleEditSubmit = () => {
            axios('{{ route('categories-edit') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    id: selected_cat.id,
                    name: editNameInput.val(),
                }
            }).then((res) => {
                if (res.status === 200) {
                    if (res.data.success === true) {
                        editAlertP.text('Categoria aggiornata correttamente');
                        editAlert.removeClass('alert-danger');
                        editAlert.addClass('alert-success');
                        editAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiornare la categoria';
                    }
                } else {
                    throw 'Impossibile aggiornare la categoria';
                }
            }).catch((msg) => {
                editAlertP.text(msg);
                editAlert.addClass('alert-danger');
                editAlert.removeClass('alert-success');
            });
        }

        let addNameInput = $('#name');
        let addAlert = $('#add-alert');
        let addAlertP = $('#add-alert-p');
        let addAlertSpinner = $('#add-alert-spinner');
        addAlertSpinner.hide();

        const handleAddSubmit = () => {
            axios.post('{{ route('categories-create') }}',
                {
                    name: addNameInput.val(),
                }).then((res) => {
                console.log(res)
                if (res.status === 200) {
                    if (res.data.success === true) {
                        addAlertP.text('Categoria aggiunta correttamente');
                        addAlert.removeClass('alert-danger');
                        addAlert.addClass('alert-success');
                        addAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiungere la categoria';
                    }
                } else {
                    throw 'Impossibile aggiungere la categoria';
                }
            }).catch((msg) => {
                addAlertP.text(msg);
                addAlert.addClass('alert-danger');
                addAlert.removeClass('alert-success');
            });

        }

        const handleToggleVisibility = () => {
            editVisibilityBtn.attr('disabled', '');
            axios('{{ route('categories-toggle-visibility') }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    id: selected_cat.id
                }
            }).then((res) => {
                if (res.status === 200) {
                    if (res.data.success === true) {
                        selected_cat.visible = !selected_cat.visible;
                        checkVisibilityButtonText();
                        editAlertP.text('Categoria aggiornata correttamente');
                        editAlert.removeClass('alert-danger');
                        editAlert.addClass('alert-success');
                        editAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiornare la categoria';
                    }
                } else {
                    throw 'Impossibile aggiornare la categoria';
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
            return ('<button onclick="onDetailsModalOpen(' + row.id + ',\'' + row.name + '\',' + row.visible + ')" data-bs-toggle="modal" data-bs-target="#categoryEditModal" class="btn"><img src="{{ asset("images/settings-icon.svg") }}" alt="dettagli"></img></button>');
        }

        const visibilityColumnFormatter = (value) => {
            let src = "{{ asset("images/visibility-icon.svg") }}";
            if (!value) {
                src = "{{ asset("images/visibilityoff-icon.svg") }}";
            }

            return ('<img alt="visibility" src="' + src + '">');
        }

        var categoryAddModal = new bootstrap.Modal(document.getElementById("categoryAddModal"), {});
        const openAddModal = () => {
            categoryAddModal.show();
        }

        function actionBarButtons() {
            return {
                btnAdd: {
                    text: 'Aggiungi categoria',
                    icon: 'fa fa-plus',
                    event: function () {
                        openAddModal();
                    },
                    attributes: {
                        title: 'Aggiungi categoria'
                    }
                }
            }
        }

        $('#table').bootstrapTable({
            data: {{ Js::from($categories) }},
            search: true,
            searchHighlight: true,
            locale: 'en-US',
            buttons: actionBarButtons,
            columns: [{
                field: 'name',
                title: 'Nome Categoria'
            }, {
                field: 'visible',
                title: 'Attivo',
                class: 'text-center w-5',
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
