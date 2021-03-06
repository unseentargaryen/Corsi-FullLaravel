@extends('layouts.app')
@section('content')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <title>CORSO DI DETAILING: {{ $course->name}}</title>
    <div class="modal fade" id="prenotationModal" tabindex="1" aria-labelledby="prenotationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fw-bolder" id="prenotationModalLabel">PRENOTA ORA!</h2>

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
                    <div class="d-flex flex-row mt-1">
                        <label style="margin-right:5px" class="fw-bold">Sede del Corso:</label>
                        <p id="sedeP"></p>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    @auth()
                        <form action='{{ route('lessons-pay') }}' method="post" id="payment-form">
                            @csrf
                            <input type="hidden" id="user_id" name="user_id"
                                   value="{{ \Illuminate\Support\Facades\Auth::guard()->user()->id }}">
                            <input type="hidden" id="lesson_id" name="lesson_id">
                            <input type="hidden" id="amount" name="amount" value="{{ $course->price }}">
                            <button class="paypal-btn align-items-center p-1">
                                @include('components/paypalbtn')
                            </button>
                        </form>
                        <p id="noseats-p" class="text-center"></p>
                    @endauth()
                    @guest
                        <button class="paypal-btn opacity-50 align-items-center p-1 fc-not-allowed" disabled>
                            @include('components/paypalbtn')
                        </button>
                        <p><a href="/login">Accedi</a> per prenotare questa data!</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <div class="col px-5">
        <div class="row">
            <div class="col-12 col-xs-6 mx-auto">
                <h1 class="fw-bold text-uppercase p-1">{{ $course->name }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xs-6 col-lg-6">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @if($course->images->count() > 0)
                            @foreach($course->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ url("/")."/courses_images/".$image->filename }}" class="img-fluid"
                                         alt="slide del corso {{ $course->name }}"/>
                                </div>
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <a href="https://placeholder.com"><img src="https://via.placeholder.com/300"></a>
                            </div>
                        @endif
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <div class="col-12 col-xs-6 col-lg-6 mt-3 mt-md-0">
                <h3 class="p-1">{{ $course->description }}</h3>
            </div>
        </div>
        <div class="row mt-3">

        </div>
        <div class="row mt-3 text-end">
            <h2 class="text-danger fw-bolder">{{ $course->price }}€</h2>
        </div>

        <div class="row mt-3 mx-auto">
            <div class="col-12">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    @if(Cookie::get('no_seats') === 'true')
        <script>
            Swal.fire({
                title: 'Errore!',
                icon: 'error',
                confirmButtonText: 'Chiudi',
                html: 'Non ci sono posti per questa data!<br>Seleziona un\'altra data.',
            });
        </script>
    @elseif(Cookie::get('purchase_cancelled') === 'true')
        <script>
            Swal.fire({
                title: 'Annullato!',
                icon: 'error',
                confirmButtonText: 'Ok',
                html: 'Hai annullato l\'acquisto.',
            });
        </script>
    @elseif(Cookie::get('purchase_complete') === 'true')
        <script>
            Swal.fire({
                title: 'CONGRATULAZIONI!',
                icon: 'success',
                confirmButtonText: 'OK!',
                html: 'Hai effettuato una prenotazione con successo!<br>Ti manderemo ulteriori dettagli via email!',
            });
        </script>
    @elseif(Cookie::get('general_error') === 'true')
        <script>
            Swal.fire({
                title: 'Annullato!',
                icon: 'error',
                confirmButtonText: 'Ok',
                html: 'C\'è stato un errore.<br>Riprova più tardi.',
            });
        </script>
    @endif

    <script src="/js/moment.js"></script>
    <script>
        $('#noseats-p').hide();

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

            $('#lesson_id').val(event.id);
            $('#selectedDateStartP').text(moment(event.start).locale("it").format('D MMMM YYYY, HH:mm'));
            $('#selectedDateEndP').text(moment(event.end).locale("it").format('HH:mm'));
            $('#seatsP').text(event.extendedProps.seats_available);
            $('#sedeP').text(event.extendedProps.sede);
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
                eventMinHeight: 150,
                eventDisplay: "auto",
                firstDay: 1,
                startDate: 'today',
                buttonText: {
                    today: 'VAI AD OGGI',
                    listMonth: "LISTA MESE",
                    dayGridMonth: "VISUALIZZAZIONE MENSILE",
                },
                eventClick: function (info) {
                    openEventModal(info.event);
                }
            });
            calendar.render();
        });

    </script>
@endsection
