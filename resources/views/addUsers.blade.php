@extends('layouts.general_layout')

@section('title', 'Add User')

@section('sidebar')
    @parent
    <li class="active"><a href="{{route('add_user')}}">Add a New User</a></li>
    <li><a href="{{route('get-all-users')}}">View All Users</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <div class="row">
            <form class="col s12" method="POST" action="{{route('create_user')}}">
                {{csrf_field()}}
                <h3 class="grey-text text-darken-4">Add New User</h3>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input name="name" id="name" type="text" value="{{old('name')}}" required=""
                               aria-required="true" class="validate">
                        <label for="name" data-error="Please enter your name">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input name="email" id="email" value="{{old('email')}}" type="email" required=""
                               aria-required="true" class="{{$errors->has('email') ? 'validate invalid' :
                                       'validate'}}">
                        <label for="email" data-error="{{$errors->has('email') ? $errors->first('email') :
                                        'Please enter a valid e-mail address'}}">
                            E-mail Address</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input name="password" id="password" type="password" required="" aria-required="true"
                               {{ $errors->has('password') ? 'placeholder=&bull;&bull;&bull;&bull;&bull;&bull;'
                               : "" }} class="{{$errors->has('password') ? 'validate invalid':'validate'}}">
                        <label for="password" data-error="{{$errors->has('password') ?
                                        $errors->first('password') : 'Please input a password'}}">Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock</i>
                        <input name="password_confirmation" id="password-confirm" type="password"
                               {{ $errors->has('password') ? 'placeholder=&bull;&bull;&bull;&bull;&bull;&bull;'
                               : "" }} class="validate" required="" aria-required="true">
                        <label for="password-confirm" data-error="Please confirm your password">
                            Confirm Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">accessibility</i>
                        <select name="usertype" id="user_type" required>
                            <option value="" disabled selected>Choose your option</option>
                            <option value="System Admin">System Admin</option>
                            <option value="Budget Requestee">Budget Requestee</option>
                            <option value="Budget Admin">Budget Admin</option>
                            <option value="Executive">Executive</option>
                        </select>
                        <label for="user_type">User Type</label>
                    </div>
                </div>
                <button class="waves-effect waves-light btn green darken-3 right"
                        type="submit">Create User</button>
            </form>
        </div>
    </div>
@endsection
<script>
    @section('script')
    $('select').material_select();
    $('#email').on("click", function(){
        $("#email").next('label').attr('data-error', "Please enter a valid E-mail address");
        Materialize.updateTextFields();
    });
    @endsection
</script>