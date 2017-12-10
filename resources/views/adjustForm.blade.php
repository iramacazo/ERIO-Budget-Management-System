@extends('layouts.general_layout')

@section('title', 'Ledger')

@section('sidebar')
    @parent
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li class="active"><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        @isset($mrf)
            Material Requisition Form Entry<br>
            Form Num: {{ $mrf->mrf->form_num }} <br>
            Description: {{ $mrf->decsription }} <br>
            Previous Amount: {{ $mrf->unit_price * $mrf->quantity }} <br><br>
        @endisset
        @isset($brf)
            Bookstore Requisition Form<br>
            Date: {{ $brf->created_at }} <br>
            Entries: <br>
            @php( $total = 0)
            @foreach( $brf->entries as $entry)
                {{ $entry->description }} -> {{ $entry->amount }}<br>
                @php( $total += $entry->amount )
            @endforeach
            <br>
            Previous Amount: {{ $total }} <br><br>
        @endisset
        @isset($pcv)
            Petty Cash Voucher<br>
            Date: {{ $pcv->created_at }}<br>
            Amount Requested: {{ $pcv->amount }}<br>
            Amount Received: {{ $pcv->amount_received }}<br>
            Amount Spent: {{ $pcv->amount - $pcv->amount_received }}<br>
            Requested By: {{ $pcv->requested_by->name }}<br>
            Approved By: {{ $pcv->approved_by->name }}<br>
            Received By: {{ $pcv->received_by->name }}<br><br>
        @endisset
        @isset($transac)
            Other Transactions<br>
            Date: {{ $transac->created_at }}<br>
            Previous Amount: {{ $transac->amount }}<br><br>
        @endisset

        <form action="{{ route('adjustEntry') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $id }}" name="id">
            <input type="hidden" value="{{ $type }}" name="type">
            <label>Amount: </label>
            <input type="number" name="amount" placeholder="Amount"><br>
            <label>Reason: </label>
            <input type="text" name="reason" placeholder="Reason"><br>
            <input type="submit" name="Submit">
        </form>
    </div>
@endsection