@extends('layouts.general_layout')

@section('title', 'Not Allowed')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3 class="center">Your account is currently inactive</h3>
        <h5 class="center">Please contact your system administrator</h5>
    </div>
@endsection
