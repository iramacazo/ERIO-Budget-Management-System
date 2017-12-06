@extends('layouts.general_layout')

@section('title', 'Add Range')

@section('sidebar')
    @parent
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li class="active"><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection

@section('content')
    <div class="col s6 offset-s3 white z-depth-2" style="padding: 20px">
        <h4>Input Date Range</h4>
        <br>
        <form action="{{route('emptyBudget')}}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s6">
                    <input id="start_date" type="date" name="start_date" class="datepicker" required>
                    <label for="start_date">Start Date</label>
                </div>
                <div class="input-field col s6">
                    <input id="end_date" type="date" name="end_date" class="datepicker" required>
                    <label for="end_date">End Date</label>
                </div>
            </div>
            <button class="waves-effect waves-light btn green darken-3 right" type="submit">Create Budget</button>
        </form>
    </div>
@endsection

<script>
    @section('script')
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        format: 'dd/mm/yyyy',
        min: 'Today',
        closeOnSelect: true // Close upon selecting a date,
    });
    @endsection
</script>