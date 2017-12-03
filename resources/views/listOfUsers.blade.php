@extends('layouts.general_layout')

@section('title', 'List of Users')

@section('sidebar')
    @parent
    <li><a href="{{route('add_user')}}">Add a New User</a></li>
    <li class="active"><a href="{{route('get-all-users')}}">View All Users</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h2>List of Users</h2>
        <table class="bordered striped highlight">
            <thead>
            <tr>
                <th>Name</th>
                <th>E-mail Address</th>
                <th>User Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->usertype}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
<script>
    @section('script')
        @if(session()->has('newuser'))
        Materialize.toast("{{session('newuser')}} was successfully added!", 4000);
        @endif
    @endsection
</script>