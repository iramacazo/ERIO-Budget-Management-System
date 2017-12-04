@extends('layouts.general_layout')

@section('title', 'Material Requisition Forms')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li class="active"><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3> Material Requisition Forms </h3>
        <div style="padding-left: 3%; padding-right: 3%;">
            <h4>Pending Approval</h4>
            @if($pending->isEmpty() == false)
                <ul class="collapsible" data-collapsible="expandable">
                @foreach($pending as $p)
                    <li>
                        <div class="collapsible-header"><p><b>Form No: </b>{{ $p->form_num }}
                                <br><b>Date: </b>{{ $p->created_at }}<br>
                                <b>Account Name: </b> {{$p->list_PA->primary_accounts->name}}</p>
                        </div>
                        <div class="collapsible-body">
                            <table class="bordered highlight">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th class="center">Quantity</th>
                                        <th>Account Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($p->entries as $entry)
                                    <tr>
                                        <td>{{ $entry->description }}</td>
                                        <td class="center">{{ $entry->quantity }}</td>
                                        @if($entry->list_sa_id != null)
                                            <td>{{ $entry->list_SA->secondary_accounts->name }}</td>
                                        @elseif($entry->list_ta_id != null)
                                            <td>{{ $entry->list_TA->tertiary_accounts->name }} for
                                                {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table><br>
                        </div>
                    </li>
                @endforeach
                </ul>
            @else
                <p class="center">There are currently no Pending MRFs for Approval</p>
            @endif
            <a class="waves-effect waves-light btn green darken-3 right" href="{{ route('addMRFView') }}">
                Create new MRF </a><br><br>
            <h4> Pending for Procurement </h4>
            @if($procure->isEmpty() == false)
                <ul class="collapsible" data-collapsible="accordion">
                    @foreach($procure as $p)
                        <li>
                            <div class="collapsible-header">
                                <p>
                                    <b>Form No: </b> {{$p->form_num}}<br>
                                    <b>Date: </b> {{$p->created_at}}<br>
                                    <b>Account Name: </b> {{$p->list_PA->primary_accounts->name}}
                                </p>
                            </div>
                            <div class="collapsible-body">
                                <table class="bordered highlight">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="center">Quantity</th>
                                            <th>Account Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($p->entries as $entry)
                                        <tr>
                                            <td>{{ $entry->description }}</td>
                                            <td class="center">{{ $entry->quantity }}</td>
                                        @if($entry->list_sa_id != null)
                                            <td>{{ $entry->list_SA->secondary_accounts->name }}</td>
                                        @elseif($entry->list_ta_id != null)
                                            <td>{{ $entry->list_TA->tertiary_accounts->name }} for
                                                {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }}</td>
                                        @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <div class="right-align">
                                    <form action="{{ route('printMRF') }}" method="POST" style="display: inline">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="{{ $p->id }}" name="id">
                                        <button type="submit" class="waves-effect waves-light btn green darken-3">
                                            <i class="material-icons left">print</i>Print Preview</button>
                                    </form>
                                    <form action="{{ route('receiveAmountsMRF') }}" method="POST"
                                          style="display: inline">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="{{ $p->id }}" name="id">
                                        <button type="submit" class="waves-effect waves-light btn green darken-3">
                                        Finalize Amounts</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="center">There are currently no Pending MRFs for Procurement</p>
            @endif
            <h4> Completed MRFs </h4>
            @if($complete->isEmpty() == false)
                <ul class="collapsible" data-collapsible="expandable">
                    @foreach($complete as $p)
                        <li>
                            <div class="collapsible-header">
                                <p>
                                    <b>Form No: </b>{{ $p->form_num }}<br>
                                    <b>Date: </b>{{ $p->created_at }}<br>
                                    <b>Account Name: </b> {{ $p->list_PA->primary_accounts->name }}
                                </p>
                            </div>
                            <div class="collapsible-body">
                                <table class="bordered highlight">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Account Name</th>
                                            <th class="center">Quantity</th>
                                            <th class="center">Unit Price</th>
                                            <th class="center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php($total = 0)
                                    @foreach($p->entries as $entry)
                                        <tr>
                                            <td>{{ $entry->description }}</td>
                                            @if($entry->list_sa_id != null)
                                                <td>{{ $entry->list_SA->secondary_accounts->name }}</td>
                                            @elseif($entry->list_ta_id != null)
                                                <td>
                                                    {{ $entry->list_TA->tertiary_accounts->name }} for
                                                    {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }}
                                                </td>
                                            @endif
                                            <td class="center">{{ $entry->quantity }}</td>
                                            <td class="right-align">P{{ number_format($entry->unit_price, 2) }}</td>
                                            <td class="right-align">P{{ number_format($entry->unit_price *
                                            $entry->quantity, 2) }}</td>
                                        </tr>
                                        @php($total += $entry->unit_price * $entry->quantity)
                                    @endforeach
                                    <tr>
                                        <td class="right-align" colspan="5"><b>Total Amount: </b>
                                            P{{number_format($total, 2)}}
                                        </td>
                                    </tbody>
                                </table><br>
                                <form action="{{ route('printMRF') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $p->id }}" name="id">
                                    <button type="submit" class="waves-effect waves-light btn green darken-3 right">
                                        <i class="material-icons left">print</i>Print Preview
                                    </button><br>
                                </form>
                            </div>
                        </li>

                    @endforeach
                </ul>
            @else
                <p class="center">There are currently no Completed MRFs</p>
            @endif
        </div>
    </div>
@endsection

<script>
    @section('script')
    @endsection
</script>