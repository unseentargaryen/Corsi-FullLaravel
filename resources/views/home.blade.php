@extends('layouts.app')

@section('content')
    <title>CORSI DI DETAILING</title>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                @include('templates/logo')
            </div>
            <div class="col-12 d-flex justify-content-center">
                <h1 class="fw-bolder">PRENOTA I TUOI CORSI!</h1>
            </div>
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center mt-4 mt-sm-4 mt-md-3">
                <label id="category-labell">Categorie</label>
                <select id="category-select" class="select2">
                    <option selected value=null>SELEZIONA UNA CATEGORIA</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center mt-4 mt-sm-4 mt-md-3">
                <label id="category-label">Sottocategorie</label>
                <select id="subcategory-select" class="select2" disabled> <!-- disabilitata al caricamento -->
                    <option selected>SELEZIONA UNA SOTTOCATEGORIA</option>
                    @foreach($subcategories as $subcat)
                        <option value="{{ $subcat['id'] }}">{{ $subcat['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-center mt-5">
            <div class="row" id="courses-div">
                @foreach($courses as $course)
                    <div class="col-12 col-sm-5 col-md-4 mx-auto my-1" role="button"
                         onclick="handleCoverClick({{ $course->id }})">
                        <div class="d-flex flex-column img-thumbnail m-1">
                            <img src='{{ url("/")."/courses_images/".$course->cover_filename }}'
                                 class="w-100 img-responsive" alt="immagine del corso {{ $course->name }}"/>
                            <div class="text-center">
                                <h4 class="my-auto">{{$course->name}}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        let categorySelect = $('#category-select');
        let subcategorySelect = $('#subcategory-select');
        let coursesDiv = $('#courses-div');


        let categories = {{ Js::from($categories) }};
        let subcategories = {{ Js::from($subcategories) }};
        let courses = {{ Js::from($courses) }};

        let subcategories_displayed = [];
        let courses_displayed = [];

        let selected_cat = 0;
        let selected_subcat = 0;

        $('.select2').select2();

        categorySelect.on('change', function (e) {
            selected_cat = parseInt(e.target.value);
            filterSubcategories();
        });

        subcategorySelect.on('change', function (e) {
            selected_subcat = parseInt(e.target.value);
            filterCourses();
        });

        const filterSubcategories = () => {
            subcategorySelect.empty();
            subcategorySelect.append(new Option('SELEZIONA LA SOTTOCATEGORIA', null, true));

            if (selected_cat) {
                subcategories_displayed = subcategories.filter(subcategory => parseInt(subcategory.category_id) === selected_cat);
                subcategories_displayed.map((subcategory) => {
                    subcategorySelect.append(new Option(subcategory.name, subcategory.id))
                });
                subcategorySelect.removeAttr('disabled');
            } else {
                subcategorySelect.attr('disabled', '');
                selected_subcat = -1;
                resetWithAllCourses();
            }
        }

        const handleCoverClick = (id) => {
            location.href = "courses/" + id;
        }

        const filterCourses = () => {
            courses_displayed = courses.filter(course => parseInt(course.subcategory_id) === selected_subcat);
            coursesDiv.empty();
            courses_displayed.map((course) => {
                coursesDiv.append(courseComponent(course))
            });
        }

        const resetWithAllCourses = () => {
            coursesDiv.empty();
            courses.map((course) => {
                coursesDiv.append(courseComponent(course))
            });
        }
        const courseComponent = (course) => {
            return ('<div class="col-12 col-sm-5 col-md-4 mx-auto my-1"><div class="d-flex flex-column img-thumbnail m-1"><img src="{{ url("/") }}/courses_images/' + course.cover_filename + '" class="w-100 img-responsive" onclick="handleCoverClick('+course.id+')"/> <div class="text-center"> <p class="my-auto">' + course.name + '</p> </div> </div> </div>');
        }
    </script>
@endsection
