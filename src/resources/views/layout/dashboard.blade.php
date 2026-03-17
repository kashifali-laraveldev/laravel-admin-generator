<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ asset('Bitsoftsol/LaravelAdministration/assets/css/bootstrap.min.css') }}" />
    {{-- custome css --}}
    <link rel="stylesheet" href="{{ asset('Bitsoftsol/LaravelAdministration/assets/css/main.css') }}" />
    {{-- font awesome  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <title>Laravel administration</title>
    <style>
        /*Toastr CSS*/
        #toast-container>.toast-success {
            opacity: 1 !important;
            /* font-size: 1.5rem !important; */
        }

        #toast-container>.toast-error {
            opacity: 1 !important;
            /* font-size: 1.5rem !important; */
        }

        #toast-container>.toast-info {
            opacity: 1 !important;
            /* font-size: 1.5rem !important; */
        }
        .remove_pointer_events{
            pointer-events: none;
            background-color: grey;
        }
    </style>
    @yield('css')
</head>

<body class="">
    @include('laravel-admin::layout.header')

    <div class="wrapper">

        <!-- Sidebar -->
        @include('laravel-admin::layout.sidebar')

        <!-- Page Content -->
        <div id="main-content">
            {{-- Success and Error Messages on Each Page --}}
            {{--  @if (session('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    <strong>Failed!</strong> {{ session('error') }}
                </div>
            @elseif($errors->all())
                <div class="alert alert-danger">
                    <strong>Failed!</strong> {{ $errors->all()[0] }}
                </div>
            @endif  --}}
            {{-- // Success and Error Messages // --}}

            @yield('content')

        </div>

    </div>

@include('laravel-admin::layout.footer')
</body>

</html>
