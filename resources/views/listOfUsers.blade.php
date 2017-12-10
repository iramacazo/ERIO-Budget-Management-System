@extends('layouts.general_layout')

@section('title', 'List of Users')

@section('sidebar')
    @parent
    <li><a href="{{route('add_user')}}">Add a New User</a></li>
    <li class="active"><a href="{{route('get-all-users')}}">View All Users</a></li>
@endsection

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('css/datatables_style.css')}}">
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>List of Users</h3>
        <div class="divider"></div>
        <div class="section">
            <h5>Active Users</h5>
            <table class="bordered striped highlight" id="listofusers">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>E-mail Address</th>
                    <th>User Type</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @if($user->status == 'active')
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->usertype}}</td>
                            <td class="right">
                                <a class="dropdown-button table-action" data-activates="dropdown{{$user->id}}">
                                    <i class="material-icons">arrow_drop_down</i></a>
                                <ul id="dropdown{{$user->id}}" class="dropdown-content" style="min-width: 155px;">
                                    <li><a href="{{route('edit-user', $user->id)}}" style="color: #2E7D32">Edit User</a></li>
                                    @if($user->usertype != 'System Admin')
                                        <li><a href="{{route('deactivate-user', $user->id)}}" style="color: #2E7D32">Deactivate User</a></li>
                                    @endif
                                </ul>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        @if($users->where('status', "=", 'inactive')->isEmpty() == false)
            <div class="divider"></div>
            <br>
            <div class="section">
                <h5>Inactive Users</h5>
                <table class="bordered striped highlight" id="listofinactive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-mail Address</th>
                        <th>User Type</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @if($user->status == 'inactive')
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->usertype}}</td>
                                <td class="right">
                                    <a class="dropdown-button table-action" data-activates="dropdown{{$user->id}}">
                                        <i class="material-icons">arrow_drop_down</i></a>
                                    <ul id="dropdown{{$user->id}}" class="dropdown-content" style="min-width: 155px;">
                                        <li><a href="{{route('edit-user', $user->id)}}" style="color: #2E7D32">Edit User</a></li>
                                        @if($user->usertype != 'System Admin')
                                            <li><a href="{{route('reactivate-user', $user->id)}}" style="color: #2E7D32">
                                                    Reactivate User</a></li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

<script>
    @section('script')

    @if(session()->has('newuser'))
    Materialize.toast("{{session('newuser')}} was added!", 4000);
    @endif
    @if(session()->has('edited'))
    Materialize.toast("{{session('edited')}} was edited!", 4000);
    @endif
    @if(session()->has('deactivate'))
    Materialize.toast("{{session('deactivate')}} has been deactivated", 4000);
    @endif
    @if(session()->has('active'))
    Materialize.toast("{{session('active')}} has been reactivated", 4000);
    @endif

        $('#listofusers').DataTable({
        "columnDefs": [ {
            "targets": 3,
            "orderable": false
        } ]});

        $('#listofinactive').DataTable({
        "columnDefs": [ {
            "targets": 3,
            "orderable": false
        } ]});
    @endsection
</script>