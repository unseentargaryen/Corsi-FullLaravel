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
    <title>ADMIN DASHBOARD: CORSI</title>

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
    <div class="modal fade" id="courseAddModal" tabindex="1" aria-labelledby="courseAddModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseAddModal">Aggiungi Corso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label>Nome Corso</label>
                        <input id="add-name-input" name="name" class="form form-control">
                    </div>
                    <div class="w-100 mt-3 d-flex flex-column">
                        <label>Sottocategoria</label>
                        <select id="add-subcategory-select" class="select2" data-live-search="true">
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-100 mt-3 d-flex flex-column">
                        <label>Prezzo in €</label>
                        <input id="add-price-input" name="price" class="form form-control" type="number" min="0">
                    </div>
                    <div class="w-100 mt-3 d-flex flex-column">
                        <label>Descrizione</label>
                        <textarea class="form-control" name="description" id="add-description-input"
                                  rows="3"></textarea>
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
        let selected_course;
        let new_course;
        let subcategories = {{ Js::from($subcategories) }};

        $('.select2').select2();

        let addNameInput = $('#add-name-input');
        let addPriceInput = $('#add-price-input');
        let addDescriptionInput = $('#add-description-input');
        let addSubcategorySelect = $('#add-subcategory-select');
        let addAlert = $('#add-alert');
        let addAlertP = $('#add-alert-p');
        let addAlertSpinner = $('#add-alert-spinner');
        addAlertSpinner.hide();

        const handleAddSubmit = () => {
            axios('{{ route('courses-create') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    name: addNameInput.val(),
                    subcategory_id: addSubcategorySelect.val(),
                    price: addPriceInput.val(),
                    description: addDescriptionInput.val(),
                }
            }).then((res) => {
                if (res.status === 200) {
                    if (res.data.success === true) {
                        addAlertP.text('Corso aggiunto correttamente');
                        addAlert.removeClass('alert-danger');
                        addAlert.addClass('alert-success');
                        addAlertSpinner.show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        throw 'Impossibile aggiungere il Corso';
                    }
                } else {
                    throw 'Impossibile aggiungere il Corso';
                }
            }).catch((msg) => {
                addAlertP.text(msg);
                addAlert.addClass('alert-danger');
                addAlert.removeClass('alert-success');
            });

        }
        const showCourse = (id) => {
            location.href = 'courses/show/' + id;
        }
        const actionsFormatter = (value, row) => {
            return ('<button class="btn" onclick="showCourse(' + row.id + ')"><img src="{{ asset("images/zoom-icon.svg") }}" alt="dettagli"></img></button>');
        }

        const visibilityColumnFormatter = (value) => {
            let src = "{{ asset("images/visibility-icon.svg") }}";
            if (!value) {
                src = "{{ asset("images/visibilityoff-icon.svg") }}";
            }

            return ('<img alt="visibility" src="' + src + '">');
        }

        const subcategoryColumnFormatter = (value) => {
            let subcat = subcategories.find((cat) => {
                if (parseInt(value) === cat.id) {
                    return cat;
                }
            })
            return subcat.name;
        }

        const priceColumnFormatter = (value) => {
            return value + "€";
        }

        var courseAddModal = new bootstrap.Modal(document.getElementById("courseAddModal"), {});
        const openAddModal = () => {
            courseAddModal.show();
        }

        function actionBarButtons() {
            return {
                btnAdd: {
                    text: 'Aggiungi Corso',
                    icon: 'fa fa-plus',
                    event: function () {
                        openAddModal();
                    },
                    attributes: {
                        title: 'Aggiungi Corso'
                    }
                }
            }
        }

        $('#table').bootstrapTable({
            data: {{ Js::from($courses) }},
            search: true,
            searchHighlight: true,
            locale: 'en-US',
            buttons: actionBarButtons,
            columns: [{
                field: 'name',
                title: 'Nome Corso'
            }, {
                field: 'subcategory_id',
                title: 'Sottocategoria',
                formatter: subcategoryColumnFormatter,
            }, {
                field: 'price',
                title: 'Prezzo',
                class: 'text-center w-5',
                formatter: priceColumnFormatter,
            }, {
                field: 'description',
                title: 'Descrizione',
                class: 'text-center w-25',
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
