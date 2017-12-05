@extends('layouts.general_layout')

@section('title', 'Transactions')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li class="active"><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px;">
        <h3> Transactions </h3> <br>
        @if(Auth::user()->otherTransactions()->count() > 0)
            <table class="bordered highlight" id="transaction_table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Account Name</th>
                        <th class="center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(Auth::user()->otherTransactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->toFormattedDateString() }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            @if($transaction->list_pa_id != null)
                                {{ $transaction->list_PA->primary_accounts->name }}<br>
                            @elseif($transaction->list_sa_id != null)
                                {{ $transaction->list_SA->secondary_accounts->name }}
                                ({{ $transaction->list_SA->secondary_accounts->primary_accounts->name }})<br>
                            @else
                                {{ $transaction->list_TA->tertiary_accounts->name }}
                                ({{ $transaction->list_TA->tertiary_accounts->secondary_accounts->name }})
                                ({{ $transaction->list_TA->tertiary_accounts->secondary_accounts->primary_accounts->name }})<br>
                            @endif
                        </td>
                        <td class="right-align">P{{ number_format($transaction->amount, 2) }}</td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        @else
            <p class="center">There are currently no transactions</p>
        @endif
        <br>
        <a href="{{ route('addTransaction') }}" class="waves-effect waves-light btn green darken-3 right">
            Add Transaction </a><br>
    </div>
@endsection

<script>
    @section('script')
    $('#transaction_table').DataTable();
    @if(session()->has('recently_added'))
    Materialize.toast("{{session('recently_added')}} was added!", 4000);
    @endif
    @endsection
</script>