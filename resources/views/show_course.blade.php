@extends('layouts.app')
@section('content')

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>


    <link
        rel="stylesheet"
        href="https://unpkg.com/swiper@8/swiper-bundle.min.css"
    />
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <link href='/plugins/fullcalendar/main.css' rel='stylesheet'/>
    <script src='/plugins/fullcalendar/main.js'></script>

    <div class="modal fade" id="prenotationModal" tabindex="1" aria-labelledby="prenotationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder" id="prenotationModalLabel">Prenota ora!</h5>

                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row">
                        <label style="margin-right:5px" class="fw-bold">Corso:</label>
                        {{ $course->name }}
                    </div>
                    <div class="d-flex flex-row mt-3">
                        <label style="margin-right:5px" class="fw-bold">Data selezionata:</label>
                        <p id="selectedDateStartP"></p>-
                        <p id="selectedDateEndP"></p>
                    </div>
                    <div class="d-flex flex-row">
                        <label style="margin-right:5px" class="fw-bold">Prezzo:</label>
                        {{ $course->price }}€
                    </div>
                    <div class="d-flex flex-row mt-3">
                        <label style="margin-right:5px" class="fw-bold">Posti disponibili:</label>
                        <p id="seatsP"></p>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-10 offset-1 col-md-6 offset-md-3">
            <h1 class="fw-bold text-uppercase">{{ $course->name }}</h1>
            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach($course->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ url("/")."/courses_images/".$image->filename }}" class="img-fluid"
                                 alt="course image"/>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-10 offset-1 col-md-6 offset-md-3">
            <h3>{{ $course->description }}</h3>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-10 offset-1 col-md-6 offset-md-3 text-end">
            <p>Prezzo: {{ $course->price }} €</p>
        </div>
    </div>

    <div class="row mt-3 mx-auto">
        <div class="col-12">
            <div id='calendar'></div>
        </div>
    </div>



    <script src="/js/moment.js"></script>

    <script>
        const swiper = new Swiper('.swiper', {
            speed: 400,
            spaceBetween: 100,
        });

        $('.swiper-button-next').on('click', function () {
            swiper.slideNext();
        })

        $('.swiper-button-prev').on('click', function () {
            swiper.slidePrev();
        })

        const prenotationModal = new bootstrap.Modal('#prenotationModal', {
            keyboard: false
        })

        const openEventModal = (event) => {
            console.log(event.extendedProps)
            $('#selectedDateStartP').text(moment(event.start).locale("it").format('D MMMM YYYY, h:mm'));
            $('#selectedDateEndP').text(moment(event.end).locale("it").format('HH:mm'));
            $('#seatsP').text(event.extendedProps.seats_available);
            prenotationModal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                events: "{{ route('get-course-lessons',['course_id' => $course->id]) }}",
                initialView: 'dayGridMonth',
                locale: "it-IT",
                themeSystem: "bootstrap5",
                height: "auto",
                eventDisplay: "auto",
                buttonText: {
                    today: 'OGGI',
                    listMonth: "LISTA MESE",
                    dayGridMonth: "GRIGLIA MESE"
                },

                eventColor: '#378006',
                eventBackgroundColor: '#378006',

                eventClick: function (info) {
                    openEventModal(info.event);
                }
            });
            calendar.render();
        });

    </script>
@endsection
