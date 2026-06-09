@extends('layout_clear')

@section('content')

<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="d-flex justify-content-center py-4">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                        <img src="{{ asset('NiceAdmin/assets/img/logo.png') }}" alt="">
                        <span class="d-none d-lg-block">Toko</span>
                    </a>
                </div><div class="card mb-3">
                    <div class="card-body">

                        <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                            <p class="text-center small">Enter your username & password to login</p>
                        </div>

                        @if (session('failed'))
                        <div class="col-12 alert alert-danger" role="alert">
                            <hr>
                            <p class="mb-0">{{ session('failed') }}</p>
                        </div>
                        @endif

                        <form action="{{ url('login') }}" method="POST" class="row g-3 needs-validation">
                            @csrf

                            <div class="col-12">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input
                                        type="text"
                                        name="username"
                                        id="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}"
                                        required
                                        minlength="6">
                                    
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Please enter your username (min. 6 characters).</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    minlength="7">
                                    
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Please enter your password (min. 7 characters).</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="credits">
                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection