@extends('layouts.general_layout')

@section('title', 'Finalize BRF')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li class="active"><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('content')
    <div class="col s6 offset-s3 white z-depth-2" style="padding: 25px">
        <h3>Finalize BRF</h3>
        <p><b>Date: </b>{{ \Carbon\Carbon::parse($brf->created_at)->toFormattedDateString() }}</p>
        <form action="{{ route('saveAmountBRF') }}" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="center">Quantity</th>
                        <th class="center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($brf->entries as $entry)
                            <td>{{ $entry->description }}</td>
                            <td class="center">{{ $entry->quantity }}</td>
                            <td class="right-align">
                                <input type="hidden" name="id[]" value="{{ $entry->id }}">
                                <div class="input-field">
                                    <i class="prefix">P</i>
                                    <input class="right-align number" type="text" name="amount[]" placeholder="Amount"
                                           required>
                                </div>
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
            <button class="waves-effect waves-light btn green darken-3 right" type="submit">Save</button>
            {{ csrf_field() }}
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