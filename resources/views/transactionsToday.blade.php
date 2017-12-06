@extends('layouts.general_layout')

@section('title', 'Daily Transaction Report')

@section('sidebar')
    @parent
    <li><a href="{{route('requestsForAccess')}}">Account Access Requests</a></li>
    <li><a href="{{ route('execMRF') }}">Material Requisition Forms</a></li>
    <li><a class="subheader">Reports</a></li>
    <li><a href="{{ route('accountsActivityPA') }}"> Accounts Activity </a></li>
    <li class="active"><a href="{{ route('transactionsToday') }}">Daily Transaction Report</a></li>
    <li><a href="{{ route('budgetVariance') }}"> Budget Variance </a></li>
@endsection

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <div class="print_stuff">
            <h3 class="center">Daily Transaction Report</h3>
            <p class="center"><b>Date: </b>{{\Carbon\Carbon::now()->toFormattedDateString()}}</p>
            <br>
            <table class="bordered">
                <thead>
                    <tr>
                        <td colspan="3" class="center bold">Bookstore Requisition Forms</td>
                    </tr>
                    @if($brf->isEmpty() == false)
                    <tr>
                        <td class="center bold">Time</td>
                        <td class="center bold">User</td>
                        <td class="center bold">Status</td>
                    </tr>
                    @endif
                </thead>
                @if($brf->isEmpty() == false)
                <tbody>
                @foreach($brf as $b)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($b->updated_at)->format("g:ia") }}</td>
                        <td>{{ $b->user->name }}</td>
                        <td>{{$b->status}}</td>
                @endforeach
                </tbody>
                @endif
            </table>
            @if($brf->isEmpty() == true)
                <p class="center">No BRF Transactions Shown for Today</p>
            @endif
            <br>
            <table class="bordered">
                <thead>
                    <tr>
                        <td class="center bold" colspan="4">Material Requisition Forms</td>
                    </tr>
                    @if($mrf->isEmpty() == false)
                        <tr>
                            <td class="center bold">Form No.</td>
                            <td class="center bold">Time</td>
                            <td class="center bold">User</td>
                            <td class="center bold">Status</td>
                        </tr>
                    @endif
                </thead>
                <tbody>
                @foreach($mrf as $m)
                    <tr>
                        <td class="center">{{ $m->form_num }}</td>
                        <td>{{  \Carbon\Carbon::parse($m->updated_at)->format("g:ia")}}</td>
                        <td>{{ $m->requester->name }}</td>
                        <td>{{ $m->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($mrf->isEmpty() == true)
                <p class="center">No MRF Transactions today</p>
            @endif
            <br>
            <table>
                <thead>
                    <tr>
                        <td class="center bold" colspan="4">Petty Cash Transactions</td>
                    </tr>
                    @if($pcv->isEmpty() == false)
                        <tr>
                            <td class="center bold">Time</td>
                            <td class="center bold">User</td>
                            <td class="center bold">Purpose</td>
                            <td class="center bold">Status</td>
                        </tr>
                    @endif
                </thead>
                <tbody>
                @foreach($pcv as $p)
                    <tr>
                        <td>{{\Carbon\Carbon::parse($p->updated_at)->format("g:ia")}}</td>
                        <td>{{ $p->requester->name }}</td>
                        <td>{{ $p->purpose }}</td>
                        <td>{{ $p->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($pcv->isEmpty() == true)
                <p class="center">No Petty Cash Transactions Shown for today</p>
            @endif
            <br>
            <table>
                <thead>
                    <tr>
                        <td class="bold center" colspan="3">Other Transactions</td>
                    </tr>
                    @if($transac->isEmpty() == false)
                        <tr>
                            <td class="bold center">Time</td>
                            <td class="bold center">User</td>
                            <td class="bold center">Description</td>
                        </tr>
                    @endif
                </thead>
                <tbody>
                @foreach($transac as $t)
                    <tr>
                        <td>{{\Carbon\Carbon::parse($t->updated_at)->format("g:ia")}}</td>
                        <td>{{ $t->user->name }}</td>
                        <td>{{$t->description}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($transac->isEmpty() == true)
                <br>
                <p class="center">No Transactions Shown for Today</p>
            @endif
            <h3 class="center">**END OF REPORT**</h3>
        </div>
        <button onclick="window.print()" type="button" class="waves-effect waves-light btn green darken-3 right">
            <i class="material-icons left">print</i> Print</button>
    </div>
@endsection
<html>
    <head>
        <title> Transactions Today </title>
    </head>
    <body>

    </body>
</html>