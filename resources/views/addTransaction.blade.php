@extends('layouts.general_layout')

@section('title', 'Add Transaction')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li class="active"><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>Add Transaction</h3>
        <form action="{{ route('saveTransaction') }}" method="POST" class="col s12">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s6">
                    <input type="text" name="description" id="description" required>
                    <label for="description">Description</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <i class="prefix">P</i>
                    <input type="text" class="number right-align" name="amount" id="amount" required>
                    <label for="amount">Amount</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <select name="account" id="select_account">
                        @foreach(Auth::user()->accessedPA as $p)
                            <option value="p-{{ $p->accessedPrimaryAccount->id }}">{{ $p->accessedPrimaryAccount
                            ->primary_accounts->name }}
                            </option>
                        @endforeach
                        @foreach(Auth::user()->accessedSA as $s)
                            <option value="s-{{ $s->accessedSecondaryAccount->id }}">{{ $s->accessedSecondaryAccount
                            ->secondary_accounts->name . ": " . $s->accessedSecondaryAccount->secondary_accounts
                            ->primary_accounts->name }}</option>
                        @endforeach
                        @foreach(Auth::user()->accessedTA as $t)
                            <option value="t-{{ $t->accessedTertiaryAccount->id }}">{{ $t->accessedTertiaryAccount
                            ->tertiary_accounts->name . ": ". $t->accessedTertiaryAccount->tertiary_accounts
                            ->secondary_accounts->name . ": " . $t->accessedTertiaryAccount->tertiary_accounts
                            ->secondary_accounts->primary_accounts->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="select_account">Account</label>
                </div>
            </div><br>
            <button class="waves-effect waves-light btn green darken-3 right" type="submit">Submit</button>
        </form>
    </div>
@endsection

<script>
    @section('script')
    $(".number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
            (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
            (e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('select').material_select();
    @endsection
</script>