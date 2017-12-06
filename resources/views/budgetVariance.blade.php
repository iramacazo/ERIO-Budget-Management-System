@extends('layouts.general_layout')

@section('title', 'Budget Variance Report')

@section('sidebar')
    @parent
    <li><a href="{{route('requestsForAccess')}}">Account Access Requests</a></li>
    <li><a href="{{ route('execMRF') }}">Material Requisition Forms</a></li>
    <li><a class="subheader">Reports</a></li>
    <li><a href="{{ route('accountsActivityPA') }}"> Accounts Activity </a></li>
    <li><a href="{{ route('transactionsToday') }}">Daily Transaction Report</a></li>
    <li class="active"><a href="{{ route('budgetVariance') }}"> Budget Variance </a></li>
@endsection

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3 class="center">Budget Variance Report</h3>
        <p class="center"><b>As of </b>{{\Carbon\Carbon::now()->toFormattedDateString()}}</p>
        <br>
        <table class="bordered">
            <thead>
                <tr>
                    <th class="center">Oracle Code</th>
                    <th class="center">Account</th>
                    <th class="center">Budget</th>
                    <th class="center">Actual</th>
                    <th class="center">Variance</th>
                    <th class="center">Variance Percentage</th>
                </tr>
            </thead>
            <tbody>
            @foreach($list as $l)
                <tr>
                    <td class="center">{{ $l['code'] }}</td>
                    <td>{{ $l['name'] }}</td>
                    <td>P{{ number_format($l['budget'], 2) }}</td>
                    <td>P{{ number_format($l['actual'], 2) }}</td>
                    <td>
                        @if( $l['diff'] < 0)
                            (P{{ number_format($l['diff'] * -1, 2) }})
                        @else
                            P{{ number_format($l['diff'], 2) }}
                        @endif
                    </td>
                    <td>{{ number_format($l['variance'], 2) }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <button onclick="window.print()" type="button" class="waves-effect waves-light btn green darken-3 right">
            <i class="material-icons left">print</i> Print</button>
    </div>
@endsection