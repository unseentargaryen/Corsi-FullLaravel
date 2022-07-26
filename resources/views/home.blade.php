@extends('layouts.app')
<link
    rel="stylesheet"
    href="https://unpkg.com/swiper@8/swiper-bundle.min.css"
/>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
@section('content')
    <title>CORSI DI DETAILING</title>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center">
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
        <div class="d-md-none col-12 d-flex justify-content-center mt-5">
            <div class="row" id="courses-div">
                @foreach($courses as $course)
                    <div class="col-12 col-sm-5 col-md-4 mx-auto my-1" role="button"
                         onclick="handleCoverClick({{ $course->id }})">
                        <div class="d-flex flex-column  m-1">
                            <img src='{{ url("/")."/courses_images/".$course->cover_filename }}'
                                 class="w-100 img-responsive" alt="immagine del corso {{ $course->name }}"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-none d-md-block col-12 mt-5">
            <div class="row" id="courses-div">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($courses as $course)
                            <div class="swiper-slide" onclick="handleCoverClick({{ $course->id }})">
                                <img src='{{ url("/")."/courses_images/".$course->cover_filename }}'
                                     class="w-100 img-responsive" alt="immagine del corso {{ $course->name }}"/>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        initSwiper();
        let sliderWrapper = $('.swiper-wrapper');
        let coursesDiv = $('#courses-div');

        let categorySelect = $('#category-select');
        let subcategorySelect = $('#subcategory-select');

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
            mobileFilterCourses();
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
            sliderWrapper.empty();
            courses_displayed.map((course) => {
                sliderWrapper.append(mobileCourseComponent(course))
            });
        }

        const mobileFilterCourses = () => {
            courses_displayed = courses.filter(course => parseInt(course.subcategory_id) === selected_subcat);
            coursesDiv.empty();
            courses_displayed.map((course) => {
                coursesDiv.append(courseComponent(course))
            });
            initSwiper();
        }

        const resetWithAllCourses = () => {
            sliderWrapper.empty();
            courses.map((course) => {
                sliderWrapper.append(courseComponent(course))
            });
        }

        const courseComponent = (course) => {
            return ('<div class="swiper-slide" onclick="handleCoverClick(' + course.id + ')"><img src="{{ url("/") }}/courses_images/' + course.cover_filename + '" class="w-100 img-responsive"/></div>');
        }

        const mobileCourseComponent = (course) => {
            return ('<div class="col-12 col-sm-5 mx-auto my-1"><div class="d-flex flex-column  m-1"><img src="{{ url("/") }}/courses_images/' + course.cover_filename + '" class="w-100 img-responsive" onclick="handleCoverClick(' + course.id + ')"/> </div> </div>');
        }

        function initSwiper() {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 3,
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        }
    </script>
@endsection
