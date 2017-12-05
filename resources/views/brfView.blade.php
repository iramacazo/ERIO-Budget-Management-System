@extends('layouts.general_layout')

@section('title', 'Bookstore Requisition Forms')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li class="active"><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>Bookstore Requisition Forms</h3>
        <div style="padding-left: 2%; padding-right: 2%;">
            @if($brfA->isEmpty() == false)
                <ul class="collapsible" data-collapsible="accordion">
            @foreach($brfA as $a)
                    <li>
                        <div class="collapsible-header"><span><b>Date: </b>{{ \Carbon\Carbon::parse($a->created_at)
                        ->toFormattedDateString() }}</span></div>
                        <div class="collapsible-body">
                            <table class="bordered highlight">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit price</th>
                                        <th class="center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php($total = 0)
                                @foreach($a->entries as $entry)
                                    <tr>
                                        <td>{{ $entry->description }}</td>
                                        <td>{{ $entry->quantity }}</td>
                                        <td class="right-align">P{{ number_format($entry->amount/$entry->quantity, 2) }}
                                        </td>
                                        <td class="right-align">P{{ number_format($entry->amount, 2) }}</td>
                                        @php($total += $entry->amount)
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td class="right-align" colspan="4"><b>Total Amount: </b>P
                                            {{number_format($total, 2)}}</td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <form action="{{ route('printBRF') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{ $a->id }}" name="id">
                                <button type="submit" class="waves-effect waves-light btn green darken-3 right">
                                    <i class="material-icons left">print</i>Print Preview</button>
                            </form>
                            <br>
                        </div>
                    </li>
                @endforeach
                </ul>
                @else
                <p class="center">There are no Complete Bookstore Requisition Forms</p>
            @endif
            <a class="waves-effect waves-light btn green darken-3 right" href="{{ route('brfAdd')}}">Add BRF</a><br><br>
            <h4>Incomplete Bookstore Requisition Forms</h4>
            @if($brfB->isEmpty() == false)
                @foreach($brfB as $b)
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title"><b>Date: </b>{{ \Carbon\Carbon::parse($b->created_at)
                            ->toFormattedDateString() }}</span>
                            <table class="bordered highlight">
                                <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="center">Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($b->entries as $entry)
                                    <tr>
                                        <td>{{ $entry->description }}</td>
                                        <td class="center">{{ $entry->quantity }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                            <div class="right-align">
                                <form action="{{ route('brfAccess') }}" method="POST"
                                      style="margin: 0; display: inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $b->id }}" name="id">
                                        <button class="waves-effect waves-light btn green darken-3" type="submit" name='submit'
                                                value="Retrieve Amounts">Finalize BRF</button>
                                </form>
                                <form action="{{ route('printBRF') }}" method="POST" style="display: inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $b->id }}" name="id">
                                    <button type="submit" class="waves-effect waves-light btn green darken-3">
                                        <i class="material-icons left">print</i>Print Preview</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="center">There are no Pending Bookstore Requisition Forms</p>
            @endif
        </div>
    </div>
@endsection
