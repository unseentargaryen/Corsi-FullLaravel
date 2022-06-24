@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div>
                        @include("templates/logo")
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-1">
                                <div class="col-xs-6 offset-xs-3 col-md-6 offset-md-3">
                                    <label for="email" class="col-form-label text-md-end">Nome</label>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-xs-6 offset-xs-3 col-md-6 offset-md-3">
                                    <label for="email" class="col-form-label text-md-end">Cognome</label>
                                    <input id="surname" type="text"
                                           class="form-control @error('surname') is-invalid @enderror" name="surname"
                                           value="{{ old('surname') }}" required autocomplete="surname" autofocus>

                                    @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-xs-6 offset-xs-3 col-md-6 offset-md-3">
                                    <label for="email" class="col-form-label text-md-end">Indirizzo Email</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-xs-6 offset-xs-3 col-md-6 offset-md-3">
                                    <label for="email" class="col-form-label text-md-end">Password</label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-xs-6 offset-xs-3 col-md-6 offset-md-3">
                                    <label for="email" class="col-form-label text-md-end">Conferma Password</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 offset-md-3 text-center">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Registrati
                                    </button>
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        Hai già un account? Loggati </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
