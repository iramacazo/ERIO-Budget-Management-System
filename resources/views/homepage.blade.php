<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERIO BMS</title>

    {{-- JQuery --}}
    <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css\materialize.min.css') }}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>
</head>
<body class="grey lighten-4">
    <div class="valign-wrapper" style="height: 100vh;" class="container">
        <div class="row">
            <div class="row">
                <div class="col s2"></div>
                <img src="{{asset('images/Logo-2.png')}}" height="250px">
            </div>
            <div class="row center-align">
                @if(\Illuminate\Support\Facades\Auth::guest())
                    <a class="green-text text-darken-3" href="{{route('login')}}" style="font-size: 2em">Login</a>
                @endif
            </div>
        </div>

    </div>
</body>
</html>