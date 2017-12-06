@extends('layouts.general_layout')

@section('title', 'Accessed Accounts')

@section('sidebar')
    @parent
    <li class="active"><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('transacView') }}"> Transactions </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3> Accessed Accounts </h3>
        <div style="padding-left: 3%; padding-right: 3%">
            @if($primary->isEmpty() == false)
                <h4>Primary Accounts</h4>
                <ul class="collapsible" data-collapsible="expandable">
                    @foreach($primary as $p)
                        <li>
                            <div class="collapsible-header">{{ $p->primary_accounts->name }}</div>
                            <div class="collapsible-body">
                                <p><b>Secondary Accounts</b></p>
                                <ul class="collapsible" data-collapsible="expandable">
                                    @foreach($p->list_of_secondary_accounts as $s)
                                        <li style="{{ $s->accessedSecondaryAccount->secondary_accounts->account_id
                                            == $p->accessedPrimaryAccount->primary_accounts->id ?
                                            'display: none' : ''}}">
                                            <div class="collapsible-header">
                                                {{$s->secondary_accounts->name}}
                                            </div>
                                            <div class="collapsible-body">
                                                <p><b>Tertiary Accounts</b></p>
                                                <ul class="collapsible" data-collapsible="expandable">
                                                    @foreach($s->list_of_tertiary_accounts as $t)
                                                        <li style="{{ $t->accessedTertiaryAccount->tertiary_accounts->subaccount_id
                                                            == $s->accessedSecondaryAccount->secondary_accounts->id ?
                                                            'display: none' : ''}}">
                                                            <div class="collapsible-header">
                                                                {{$t->tertiary_accounts->name}}
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <h4>There are no Primary accounts</h4>
            @endif
            <a href="{{ route('requestAccessForm') }}" class="waves-effect waves-light btn green darken-3 right">
                Request Access</a><br>
            <h4> Pending Requests </h4>
            @if($pendingPA->isEmpty() == false || $pendingSA->isEmpty() == false || $pendingTA->isEmpty() == false)
                <ul class="collection z-depth-1">
                    @foreach($pendingPA as $p)
                        <li class="collection-item">{{ $p->accessedPrimaryAccount->primary_accounts->name }}</li>
                    @endforeach
                    @foreach($pendingSA as $s)
                        <li class="collection-item">{{ $s->accessedSecondaryAccount->secondary_accounts->name . ' for ' .
                        $s->accessedSecondaryAccount->secondary_accounts->primary_accounts->name}}</li>
                    @endforeach
                    @foreach($pendingTA as $t)
                        <li class="collection-item">{{ $t->accessedTertiaryAccount->tertiary_accounts->name . ' for ' .
                        $t->accessedTertiaryAccount->tertiary_accounts->secondary_accounts->name}}</li>
                    @endforeach
                </ul>
            @else
                There are currently no pending requests
            @endif
        </div>
    </div>
@endsection

<script>
    @section('script')
        $('.collapsible').collapsible();
    @endsection
</script>