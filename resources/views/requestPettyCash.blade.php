@extends('layouts.general_layout')

@section('title', 'Request Petty Cash')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li class="active"><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('content')
    <div class="col s6 offset-s3 white z-depth-2">
        <h4>Request Petty Cash</h4>
        <form action='{{ route("recordRequestPCV") }}' method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s6">
                    <input id="purpose" type="text" name="purpose" required>
                    <label for="purpose">Purpose</label>
                </div>
                <div class="input-field col s6">
                    <i class="prefix">P</i>
                    <input type="text" class="number right-align" name="amount" id="amount" required>
                    <label for="amount">Amount</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <select name="account" id="account">
                        <!-- TODO REVISE FOR BETTER UX and Create Fields when Account is Transportation and Meeting Expense -->
                        @foreach($accounts as $a)
                            <option value="p-{{ $a->primary_accounts->id }}">{{ $a->primary_accounts->name }}</option>
                            @foreach($a->list_of_secondary_accounts as $s)
                                <option value="s-{{ $s->secondary_accounts->id }}">{{ $a->primary_accounts->name
                                    . ": " . $s->secondary_accounts->name}}</option>
                                @foreach($s->list_of_tertiary_accounts as $t)
                                    <option value="t-{{ $t->tertiary_accounts->id }}">{{ $a->primary_accounts->name
                                        . ": " . $s->secondary_accounts->name . ": " . $t->tertiary_accounts->name}}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    <label for="account">Account</label>
                </div>
            </div>
            <div class="row">
                <button type="submit" name="submit" class="waves-effect waves-light btn green darken-3 right"
                        id="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection

<script>
    @section('script')
        $('select').material_select();

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
    @endsection
</script>