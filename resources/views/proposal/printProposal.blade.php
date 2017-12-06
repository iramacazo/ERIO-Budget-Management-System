@extends('layouts.general_layout')

@section('title', 'Print Budget Proposal')

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
@endsection

@section('sidebar')
    @parent
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li class="active"><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
    <style>
        td.amount{
            text-align: right;
        }
    </style>
@endsection

@section('content')
    @php
        $prev_grand_total = 0;
        $grand_total = 0;
            foreach($prev_primary_accounts_list as $prev)
                $prev_grand_total+=$prev->amount;
    @endphp

    <div class="col s8 offset-s2 white z-depth-2" style="padding: 20px">
        <div class="print_stuff">
            <h4 class="center">Budget proposal for <br>
                                A.Y. {{ $proposed_ay }}</h4>
            <table>
                <th>BUDGET: ERI {{ $proposed_ay }}</th> <!-- TODO Academic Year -->
                <th>ORACLE CODE</th>
                <th>APPROVED BUDGET {{ $previous_ay }}</th>
                <th>PROPOSED BUDGET {{ $proposed_ay }}</th>
                <th>REMARKS</th>
                <tr>
                    <td>  </td>
                </tr>
                <tr>
                    <td>Account Number</td>
                    <td class="center"><b>01220</b></td>
                </tr>
                <tr>
                    <td>  </td>
                </tr>
                <tr>
                    <td>OPERATING ACCOUNTS</td>
                </tr>
                @if($primary_accounts_list != null)
                    @foreach($primary_accounts_list as $pal)
                        <tr>
                            <td>{{ $pal->name }}</td>
                            <td class="center"><b>{{ $pal->code }}</b></td>
                            @foreach($prev_primary_accounts_list as $prev)
                                @if($prev->code == $pal->code)
                                    @php($previous = $prev->amount)
                                @endif
                            @endforeach
                            @php($grand_total+=$pal->amount)
                            @if(isset($previous) && $previous != null)
                                <td class="amount">{{ $previous }}</td>
                                @php($previous = null)
                            @else
                                <td class="amount">N/A</td>
                            @endif
                            <td class="amount">{{ number_format($pal->amount) }}</td>
                            <td><textarea style="resize: none"></textarea></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>  </td>
                    </tr>
                    <tr>
                        <td>GRAND TOTAL: Operating Accounts Budget</td>
                        <td></td>
                        <td class="amount"><b>{{ number_format($prev_grand_total) }}</b></td>
                        <td class="amount"><b>{{ number_format($grand_total) }}</b></td>
                        <td><textarea style="resize: none"></textarea></td>
                    </tr>
                @endif
            </table>
            <br>
            <button class="waves-effect waves-light btn green darken-3 right" id="print_button">
                <i class="material-icons left">print</i>Print</button>
        </div>
    </div>
@endsection
