
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koperasi Kota Padang</title>
    <link rel="icon" type="image/svg" href="{{ asset('assets/images/logo_icon.svg') }}">

    <!-- Global stylesheets -->
    <link href="https://fonts.cdnfonts.com/css/inter" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body>

<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <!-- Login form -->
                <form class="login-form" method="POST" action="{{ route('login') }}"> @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center mb-2 mt-2">
                                    <img src="{{ asset('assets/images/logo-padang.png') }}" style="height: 90px" alt="">
                                </div>
                                <h5 class="mb-0">Login</h5>
                                <span class="d-block text-muted">Masukkan akun anda dibawah ini</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="text"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="username" autofocus>

                                    <div class="form-control-feedback-icon">
                                        <i class="ph-user-circle text-muted"></i>
                                    </div>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" required>

                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                </div>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Masuk</button>
                            </div>

                        </div>
                    </div>
                </form>
                <!-- /login form -->

            </div>
            <!-- /content area -->




        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->



</body>
</html>
