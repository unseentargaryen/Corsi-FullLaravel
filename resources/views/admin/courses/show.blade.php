@extends('layouts.app')
@section('content')

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <style>
        .dz-details {
            display: none;
        }

    </style>

    <div class="row mb-3">
        <div class="col-6 offset-3">
            <form action="#"
                  class="card bg-white border-0 shadow p-5"
                  id="editCourseForm" method="POST">

                <div class="row">
                    <div class="col-6">
                        <label>Nome del corso:</label>
                        <input type="text" id="name" name="name" value="{{$course->name}}" class="form form-control"
                               required/>
                    </div>
                    <div class="col-6">
                        <label>Prezzo in €</label>
                        <input type="text" id="price" name="price" value="{{$course->price}}" class="form form-control"
                               required/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" name="visible" type="checkbox" value="true" id="visible"
                                   @if($course->visible === 1) checked @endif>
                            <label class="form-check-label" for="visible">
                                Attivo
                            </label>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column">
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
                                  class="form form-control">{{$course->description}}</textarea>
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
                    <div class="col-4 offset-4">
                        <button class="btn btn-success w-100">SALVA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-6 offset-3">
            <h3>Immagine principale</h3>
            <form action=""
                  class="dropzone bg-white border-0 card shadow h-100"
                  id="coverDropzone" method="POST">
                <input type="file" class="btn btn-secondary"/>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-6 offset-3">
            <h3>Immagini</h3>
            <form action=""
                  class="dropzone bg-white border-0 card shadow h-100"
                  id="imagesDropzone" method="POST">
                <input type="file" class="btn btn-secondary"/>
            </form>
        </div>
    </div>


    <script>
        $('#subcategoryPERROR').hide();
        $('#submitAlert').hide();
        $('#submitSuccessAlert').hide();

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
            acceptedFiles: 'image/*',
            autoProcessQueue: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                let myDropzone = this;

                // If you only have access to the original image sizes on your server,
                // and want to resize them in the browser:
                axios.get('{{ route('get-course-cover',['course_id' => $course->id]) }}').then((response) => {
                    myDropzone.displayExistingFile({}, "data:image/jpeg;base64, " + response.data.data);
                })

                this.on("success", () => {
                    location.reload();
                });
            },
        };

        Dropzone.options.imagesDropzone = {
            maxFiles: 1,
            url: '{{route("add-course-image",["course_id" => $course->id])}}',
            acceptedFiles: 'image/*',
            autoProcessQueue: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                let myDropzone = this;

                // If you only have access to the original image sizes on your server,
                // and want to resize them in the browser:
                axios.get('{{ route('get-course-images',['course_id' => $course->id]) }}').then((response) => {

                    response.data.data.map((img) => {
                        myDropzone.displayExistingFile({}, "data:image/jpeg;base64, " + img.src);
                    });
                })

                this.on("success", () => {
                    location.reload();
                });
            },
        };
    </script>
@endsection
