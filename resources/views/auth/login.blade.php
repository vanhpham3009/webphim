@extends('layouts.layout_login')

@section('content_login')
<div id="logreg-forms">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal" style="text-align: center">Đăng nhập hệ thống</h1>
        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Địa chỉ Email:') }}</label>

            <div class="col-md-8">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mật khẩu:') }}</label>

            <div class="col-md-8">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100 py-2">
            <i class="fas fa-sign-in-alt"></i>
            {{ __('Đăng nhập') }}
        </button>
        <div class="text-center my-4">
            <hr class="my-2 mb-2">
            <span class="text-center font-weight-bold">Hoặc</span>
            <div class="d-flex justify-content-center mt-2">
                <a href="{{ route('google-auth') }}"
                    class="btn-google btn btn-primary d-flex align-items-center shadow-sm px-4 py-2"
                    style="color: white; text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24" height="24" class="mr-2">
                        <path fill="#4285F4" d="M24 9.5c3.03 0 5.6 1.01 7.67 2.66l5.74-5.74C33.82 3.22 29.28 1.5 24 1.5 14.8 1.5 7.18 7.86 4.56 16.12l6.82 5.29C12.66 13.87 17.91 9.5 24 9.5z"></path>
                        <path fill="#34A853" d="M46.5 24c0-1.63-.14-3.19-.41-4.71H24v9.15h12.7c-.55 2.95-2.22 5.44-4.79 7.14l7.53 5.84C43.53 37.01 46.5 31.05 46.5 24z"></path>
                        <path fill="#FBBC05" d="M11.38 27.21c-.41-1.21-.64-2.5-.64-3.83 0-1.33.23-2.62.64-3.83L4.56 14.26C2.82 17.72 1.5 21.64 1.5 25.88c0 4.24 1.32 8.16 3.06 11.62l6.82-5.29z"></path>
                        <path fill="#EA4335" d="M24 46.5c5.89 0 10.84-1.94 14.46-5.27l-7.53-5.84c-2.08 1.39-4.77 2.2-7.67 2.2-6.09 0-11.34-4.37-13.1-10.33l-6.82 5.29C7.18 40.14 14.8 46.5 24 46.5z"></path>
                        <path fill="none" d="M1.5 1.5h45v45h-45z"></path>
                    </svg>
                    <span>Đăng nhập bằng Google</span>
                </a>
            </div>
        </div>

        <!-- <div class="row mb-0">
            <div class="col-12 d-flex flex-column">
                <div class="d-flex justify-content-end mt-2">
                    <a href="{{url('/')}}" id="cancel_signup" class="text-decoration-none text-success">
                        <i class="fas fa-angle-left"></i> Quay lại trang chính
                    </a>
                </div>
            </div>
        </div> -->
    </form>
</div>
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection