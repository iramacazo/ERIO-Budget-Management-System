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
        <h1> Generate PRS </h1>
        <form action="{{ route('generatePRS') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="text" name="code">
            <input type="submit" value="Generate PRS">
        </form>
    </div>
@endsection