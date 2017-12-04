@extends('layouts.general_layout')

@section('title', 'Finalize Entries')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li class="active"><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3> Receive Amounts </h3>
        <p><b>Form Num: </b>{{ $mrf->form_num }}</p>
        <p><b>Account: </b>{{ $mrf->list_PA->primary_accounts->name }}</p>
        <p><b>Date Needed: </b>{{ \Carbon\Carbon::parse($mrf->date_needed)->toFormattedDateString() }}</p>
        <form action="{{ route('saveAmountsMRF') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $mrf->id }}" name="mrf_id">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="center">Quantity</th>
                        <th class="center">Amount</th>
                        <th class="center">Supplier</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($mrf->entries as $entry)
                    <tr>
                        <td>{{ $entry->description }}</td>
                        <td class="center">{{ $entry->quantity }}</td>
                        <td class="right-align">
                            <div class="input-field">
                                <i class="prefix">P</i>
                                <input type="text" class="number right-align" name="unit_price[]"
                                       placeholder="Unit Price" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-field">
                                <input type="text" name="supplier[]" placeholder="Supplier" required>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="waves-effect waves-light btn green darken-3 right" type="submit">Save</button>
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
    @endsection
</script>