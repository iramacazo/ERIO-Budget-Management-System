@extends('layouts.general_layout')

@section('title', 'Edit account')

@section('sidebar')
    @parent
    @if(Auth::user()->usertype == "System Admin")
        <li><a href="{{route('add_user')}}">Add a New User</a></li>
        <li><a href="{{route('get-all-users')}}">View All Users</a></li>
    @elseif(Auth::user()->usertype == "Executive")
        <li><a href="{{route('requestsForAccess')}}">Account Access Requests</a></li>
        <li><a href="{{ route('execMRF') }}">Material Requisition Forms</a></li>
        <li><a class="subheader">Reports</a></li>
        <li><a href="{{ route('accountsActivityPA') }}"> Accounts Activity </a></li>
        <li><a href="{{ route('transactionsToday') }}"> Transactions Today </a></li>
        <li><a href="{{ route('budgetVariance') }}"> Budget Variance </a></li>
    @elseif(Auth::user()->usertype == "Budget Requestee")
        <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
        <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
        <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
        <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
        <li><a href="{{ route('transacView') }}"> Transactions </a></li>
    @elseif(Auth::user()->usertype == "Budget Admin")
        <li><a href="{{route('budget_dash')}}">Budget</a></li>
        <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
        <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
        <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
        <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
        <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
    @endif
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <div class="row">
            <form class="col s12" method="POST" action="{{route('save_account')}}">
                {{csrf_field()}}
                <h3 class="grey-text text-darken-4">Edit Account</h3>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input name="name" id="name" type="text" value="{{Auth::user()->name}}" disabled>
                        <label for="name" data-error="Please enter your name">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input name="email" id="email" value="{{Auth::user()->email}}" type="email" disabled>
                        <label for="email">
                            E-mail Address</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">accessibility</i>
                        <select name="usertype" id="user_type" disabled>
                            <option value="" disabled selected>{{Auth::user()->usertype}}</option>
                        </select>
                        <label for="user_type">User Type</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input name="password" id="password" type="password" required="" aria-required="true"
                               {{ $errors->has('password') ? 'placeholder=&bull;&bull;&bull;&bull;&bull;&bull;'
                               : "" }} class="{{$errors->has('password') ? 'validate invalid':'validate'}}">
                        <label for="password" data-error="{{$errors->has('password') ?
                                        $errors->first('password') : 'Please input a password'}}">New Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock</i>
                        <input name="password_confirmation" id="password-confirm" type="password"
                               {{ $errors->has('password') ? 'placeholder=&bull;&bull;&bull;&bull;&bull;&bull;'
                               : "" }} class="validate" required="" aria-required="true">
                        <label for="password-confirm" data-error="Please confirm your password">
                            Confirm New Password</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input name="old_password" id="old_password" type="password" required="" aria-required="true"
                               {{ $errors->has('old_password') ? 'placeholder=&bull;&bull;&bull;&bull;&bull;&bull;'
                               : "" }} class="{{$errors->has('old_password') ? 'validate invalid':'validate'}}">
                        <label for="old_password" data-error="{{$errors->has('old_password') ?
                                        $errors->first('old_password') : 'Please input a old password'}}">
                            Input Old Password</label>
                    </div>
                </div>

                <button class="waves-effect waves-light btn green darken-3 right"
                        type="submit">Confirm Changes</button>
            </form>
        </div>
    </div>
@endsection
<script>
    @section('script')
    $('select').material_select();
    @if($errors->any())
            console.log("{{$errors}}");
    @endif
    @if(session()->has('edited'))
        Materialize.toast("Account was successfully edited!", 4000);
    @endif
    @endsection
</script>