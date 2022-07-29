@extends('layouts.app')
@section('content')

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/it.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <style>
        .dz-details {
            display: none;
        }
    </style>
    <title>ADMIN DASHBOARD: MODIFICA CORSO</title>

    <div class="modal fade" id="lessonEditModal" tabindex="1" aria-labelledby="subcategoryAddModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subcategoryAddModal">Modifica Lezione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100 mt-3">
                        <label>Sede corso</label>
                        <input id="edit-lesson-sede-input" name="sede" class="form form-control">
                    </div>
                    <div class="w-100 mt-3">
                        <label>Numero partecipanti</label>
                        <input id="edit-lesson-number-input" name="max_participants" class="form form-control">
                    </div>
                    <div class="w-100 mt-3">
                        <label>Inizio</label>
                        <input type="datetime-local" id="startDateEdit" class="form form-control flatpickr" required/>
                    </div>
                    <div class="w-100 mt-3">
                        <label>Fine</label>
                        <input type="datetime-local" id="endDateEdit" class="form form-control flatpickr" required/>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" name="visible" type="checkbox" value="true"
                               id="editLessonVisible">
                        <label class="form-check-label" for="editLessonVisible">
                            Attivo
                        </label>
                    </div>
                    <div class="mt-3 alert d-flex flex-row align-items-center justify-content-center" role="alert"
                         id="edit-lesson-alert" style="height: 75px">
                        <p id="edit-lesson-alert-p" class="my-auto w-100 h-100 text-center"></p>
                        <div class="spinner-border text-secondary" role="status" id="edit-lesson-alert-spinner"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="edit-lesson-id-hidden">
                    <button type="button" class="btn btn-success" onclick="handleAddSubmit()">SALVA</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        CHIUDI
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col px-5">
        <div class="row mb-3">
            <div class="col-12 col-md-8 offset-md-2">
                <h2 class="fw-bold">Dettagli Corso</h2>
                <form action="#"
                      class="card bg-white border-0 shadow p-5"
                      id="editCourseForm" method="POST">
                    <div class="row">
                        <div class="col-12 col-md-6 mt-3">
                            <label>Nome del corso:</label>
                            <input type="text" id="name" name="name" value="{{$course->name}}" class="form form-control"
                                   required/>
                        </div>
                        <div class="col-12 col-md-6 mt-3">
                            <label>Prezzo in €</label>
                            <input type="number" id="price" name="price" value="{{$course->price}}"
                                   class="form form-control"
                                   required/>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-md-6 d-flex align-items-center mt-3">
                            <div class="form-check">
                                <input class="form-check-input" name="visible" type="checkbox" value="true" id="visible"
                                       @if($course->visible === 1) checked @endif>
                                <label class="form-check-label" for="visible">
                                    Attivo
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column mt-3">
                            <label id="category-label">Sottocategorie</label>
                            <select id="subcategory-select" name="subcategory" class="select2" required>
                                <option>SELEZIONA UNA SOTTOCATEGORIA</option>
                                @foreach($subcategories as $subcat)
                                    <option value="{{ $subcat['id'] }}"
                                            @if($course->subcategory_id === $subcat->id) selected @endif>{{ $subcat['name'] }}</option>
                                @endforeach
                            </select>
                            <p class="text-danger" id="subcategoryPERROR">Seleziona un' opzione</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="description"> Descrizione del corso:</label>
                            <textarea type="text" id="description" name="description"
                                      class="form form-control" required>{{$course->description}}</textarea>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="alert alert-danger" role="alert" id="submitAlert">
                            Errore durante il salvataggio
                        </div>
                        <div class="alert alert-success" role="alert" id="submitSuccessAlert">
                            Modifiche salvate correttamente
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 col-md-4 offset-md-4">
                            <button class="btn btn-success w-100">SALVA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 col-md-8 offset-md-2">
                <h2 class="fw-bold">Lezioni del corso</h2>
                <form action="#"
                      class="card bg-white border-0 shadow p-5"
                      id="form" method="POST">
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="description">Numero partecipanti</label>
                            <input type="number" id="max_participants" min=1 step=1 value=4 required
                                   class="form form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="description">Date</label>
                            <input type="text" id="dates" class="form form-control flatpickr" required/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="description">Ora inizio</label>
                            <input type="text" id="startTime" class="form form-control flatpickr" required/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="description">Ora fine</label>
                            <input type="text" id="endTime" class="form form-control flatpickr" required/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="sede"> Sede del corso:</label>
                            <input type="text" id="sede" name="sede" value="{{$course->sede}}"
                                   class="form form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="alert alert-success" role="alert" id="submitLessonSuccessAlert">
                            Lezione aggiunta correttamente
                        </div>
                        <div class="alert alert-danger" role="alert" id="submitLessonErrorAlert">
                            Errore, impossibile aggiungere la lezione
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 col-md-4 offset-md-4">
                            <button class="btn btn-success w-100">AGGIUNGI</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <h2 class="col-12 col-md-8 offset-md-2 fw-bold">Lista Lezioni</h2>
            <div class="col-12 col-md-8 offset-md-2 card p-3">
                <table id="table" class="w-100 h-100 bg-white"></table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-md-8 offset-md-2">
                <h2 class="fw-bold">Immagine principale</h2>
                <form action=""
                      class="dropzone bg-white border-0 shadow h-100"
                      id="coverDropzone" method="POST">
                    <div class="dz-message"></div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 col-md-8 offset-md-2">
                <h2 class="fw-bold">Immagini del Corso</h2>
                <form action=""
                      class="dropzone bg-white border-0 shadow"
                      id="imagesDropzone" method="POST">
                    <div class="dz-message"></div>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/moment.js"></script>
    <script>
        $('#subcategoryPERROR').hide();
        $('#submitAlert').hide();
        $('#submitSuccessAlert').hide();
        $('#submitLessonSuccessAlert').hide();
        $('#submitLessonErrorAlert').hide();

        $('#form').on('submit', function (e) {
            e.preventDefault();
            $('#submitLessonSuccessAlert').hide();
            $('#submitLessonErrorAlert').hide();

            payload = {
                course_id: {{ $course->id }},
                max_participants: $("#max_participants").val(),
                dates: $("#dates").val(),
                startTime: $("#startTime").val(),
                endTime: $("#endTime").val(),
                sede: $("#sede").val(),
            }
            axios.post('{{ route('lessons-create')}}', payload).then((res) => {
                if (res.status === 200) {
                    $('#submitLessonSuccessAlert').show();
                } else {
                    throw 500;
                }
            }).catch(() => {
                $('#submitLessonErrorAlert').show();
            });
        });

        $("#dates").flatpickr({
            mode: "multiple",
            locale: "it",
            minDate: "today",
            dateFormat: "Y-m-d",
        });

        $("#startTime").flatpickr({
            locale: "it",
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: "09:00",
            onChange: function (selectedDates, dateStr, instance) {
                console.log(selectedDates);
            },
        });

        $("#endTime").flatpickr({
            locale: "it",
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: "18:00",
            onChange: function (selectedDates, dateStr, instance) {
                console.log(selectedDates);
            },
        });

        $("#dataEdit").flatpickr({
            mode: "multiple",
            locale: "it",
            minDate: "today",
            dateFormat: "Y-m-d",
        });

        $("#startTimeEdit").flatpickr({
            locale: "it",
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: "09:00",
            onChange: function (selectedDates, dateStr, instance) {
                console.log(selectedDates);
            },
        });

        $("#endTimeEdit").flatpickr({
            locale: "it",
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: "18:00",
            onChange: function (selectedDates, dateStr, instance) {
                console.log(selectedDates);
            },
        });

        // $("#startDateEdit").flatpickr({
        //     locale: "it",
        //     enableTime: true,
        // });
        // $("#endDateEdit").flatpickr({
        //     locale: "it",
        //     enableTime: true,
        // });

        $('#editCourseForm').on('submit', function (e) {
            $('#submitAlert').hide();
            $('#subcategoryPERROR').hide();

            e.preventDefault();
            if (parseInt($('#subcategory-select').val())) {
                axios.post('{{ route('course-edit',['id' => $course->id]) }}', {
                    name: $('#name').val(),
                    price: $('#price').val(),
                    description: $('#description').val(),
                    visible: $('#visible').is(':checked') ? 1 : 0,
                    subcategory_id: parseInt($('#subcategory-select').val()),
                }).then((res) => {
                    if (res.data.success) {
                        $('#submitSuccessAlert').show();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        $('#submitAlert').show();
                    }
                }).catch(() => {
                    $('#submitAlert').show();
                });
            }
        })

        $('.select2').select2();
        Dropzone.options.coverDropzone = {
            maxFiles: 1,
            url: '{{route("set-course-cover",["course_id" => $course->id])}}',
            method: "POST",
            acceptedFiles: 'image/*',
            autoProcessQueue: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                let myDropzone = this;

                axios.get('{{ route('get-course-cover',['course_id' => $course->id]) }}').then((response) => {
                    myDropzone.displayExistingFile({}, "{{ url("/") }}/courses_images/" + response.data.data);
                })

                this.on("success", () => {
                    location.reload();
                });

            },
        };

        Dropzone.options.imagesDropzone = {
            url: '{{route("add-course-image",["course_id" => $course->id])}}',
            method: "POST",
            acceptedFiles: 'image/*',
            autoProcessQueue: true,
            addRemoveLinks: true,
            dictRemoveFile: "<div><span class='fa fa-trash text-danger btn' style='font-size: 2em'></span></div>",

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                let myDropzone = this;

                // If you only have access to the original image sizes on your server,
                // and want to resize them in the browser:
                axios.get('{{ route('get-course-images',['course_id' => $course->id]) }}').then((response) => {
                    response.data.data.map((img) => {
                        myDropzone.displayExistingFile({name: img.filename}, "{{ url("/") }}/courses_images/" + img.filename);
                    });
                })

                this.on("success", () => {
                    location.reload();
                });
                $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em'></span></div>");
            },
            removedfile: function (file) {

                var name = file.name;
                axios.post("{{ route('remove-course-image') }}", {
                    filename: name,
                }).then((res) => {
                    if (res.data.success) {
                        location.reload();
                    }


                }).catch(() => {

                });
            }
        };

        const handleAddSubmit = () => {
            let alertP = $('#edit-lesson-alert-p');
            let spinner = $('#edit-lesson-alert-spinner');
            spinner.show();
            let url = '{{ route('lessons-edit') }}';

            axios(url,{
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: {
                    id: $('#edit-lesson-id-hidden').val(),
                    sede: $('#edit-lesson-sede-input').val(),
                    max_participants: $('#edit-lesson-number-input').val(),
                    start: $('#startDateEdit').val(),
                    end: $('#endDateEdit').val(),
                    visible: document.getElementById('editLessonVisible').checked == true,
                }
            }).then((res) => {
                if (res.status === 200) {
                        alertP.text('Lezione aggiornata correttamente');
                        alertP.removeClass('alert-danger');
                        alertP.addClass('alert-success');
                        spinner.hide();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                } else {
                    throw 'Impossibile aggiornare la lezione';
                }
            }).catch((msg) => {
                alertP.text(msg);
                alertP.addClass('alert-danger');
                alertP.removeClass('alert-success');
            });

        }

        const onDetailsModalOpen = (id, max_participants, visible, sede, start, end) => {
            $('#edit-lesson-alert-spinner').hide();
            $('#edit-lesson-id-hidden').val(id);
            let editLessonSedeInput = $('#edit-lesson-sede-input');
            editLessonSedeInput.val(sede);
            let editLessonNumPartInput = $('#edit-lesson-number-input');
            editLessonNumPartInput.val(max_participants);

            let editLessonVisible = $('#editLessonVisible');
            editLessonVisible.prop('checked', visible);

            const startDateEdit = document.querySelector("#startDateEdit");
            const endDateEdit = document.querySelector("#endDateEdit");

            const tomorrow = new Date()
            tomorrow.setDate(new Date().getDate() + 1)
            startDateEdit.value = moment(start).add('h',2).toISOString().slice(0, 16);
            endDateEdit.value = moment(end).add('h',2).toISOString().slice(0, 16);

            startDateEdit.min = tomorrow.toISOString().slice(0, 16);
            endDateEdit.min = tomorrow.toISOString().slice(0, 16);
        }

        const actionsFormatter = (value, row) => {
            return ('<button onclick="onDetailsModalOpen(' + row.id + ',' + row.max_participants + ',' + row.visible + ',\'' + row.sede + '\',\'' + row.start + '\',\'' + row.end + '\')" data-bs-toggle="modal" data-bs-target="#lessonEditModal" class="btn"><img src="{{ asset("images/settings-icon.svg") }}" alt="dettagli"></img></button>');
        }

        $('#table').bootstrapTable({
            data: {{ Js::from($lessons)}},
            search: true,
            searchHighlight: true,
            locale: 'en-US',
            columns: [{
                field: 'start',
                title: 'Data',
                class: 'text-center w-10',
            }, {
                field: 'sede',
                title: 'Sede',
                class: 'text-center w-10',
            }, {
                field: 'max_participants',
                title: 'Posti massimi',
                class: 'text-center w-10',
            }, {
                field: 'actions',
                title: 'Azioni',
                class: 'text-center w-10',
                formatter: actionsFormatter,
            }]
        });
    </script>
@endsection
