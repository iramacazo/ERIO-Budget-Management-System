@extends('layouts.general_layout')

@section('title', 'Accessed Accounts')

@section('sidebar')
    @parent
    <li class="active"><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h1> Accessed Accounts </h1><br>
        <a href="{{ route('requestAccessForm') }}">Request Access</a><br>
        <br>
        @if($primary->isEmpty() == false)
            <h4>Primary Accounts</h4>
        <ul class="collapsible" data-collapsible="expandable">
            @foreach($primary as $p)
                <li>
                    <div class="collapsible-header">{{ $p->accessedPrimaryAccount->primary_accounts->name }}</div>
                    <div class="collapsible-body">
                        <p><b>Secondary Accounts</b></p>
                        <ul class="collapsible" data-collapsible="expandable">
                            @foreach($secondary as $s)
                                <li style="{{ $s->accessedSecondaryAccount->secondary_accounts->account_id
                                            == $p->accessedPrimaryAccount->primary_accounts->id ?
                                            'display: none' : ''}}">
                                    <div class="collapsible-header">
                                            {{$s->accessedSecondaryAccount->secondary_accounts->name}}
                                    </div>
                                    <div class="collapsible-body">
                                        <p><b>Tertiary Accounts</b></p>
                                        <ul class="collapsible" data-collapsible="expandable">
                                            @foreach($tertiary as $t)
                                                <li style="{{ $t->accessedTertiaryAccount->tertiary_accounts->subaccount_id
                                                            == $s->accessedSecondaryAccount->secondary_accounts->id ?
                                                            'display: none' : ''}}">
                                                    <div class="collapsible-header">
                                                        {{$t->accessedTertiaryAccount->tertiary_accounts->name}}
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
            <h4>There are no accounts</h4>
        @endif
        <h2> Pending Requests </h2><br>
        @if($pendingPA->isEmpty() == false)
            @foreach($pendingPA as $p)
                {{ $p->accessedPrimaryAccount->primary_accounts->name }}<br>
            @endforeach

            @if($pendingSA != null)
                @foreach($pendingSA as $s)
                    {{ $s->accessedSecondaryAccount->secondary_accounts->name }} for
                    {{ $s->accessedSecondaryAccount->secondary_accounts->primary_accounts->name }}<br>
                @endforeach

                @if($pendingTA != null)
                    @foreach($pendingTA as $t)
                        {{ $t->accessedTertiaryAccount->tertiary_accounts->name }} for
                        {{ $t->accessedTertiaryAccount->tertiary_accounts->secondary_accounts->name }}<br>
                    @endforeach
                @endif
            @endif
        @else
            There are currently no pending requests
        @endif
    </div>
@endsection

<script>
    @section('script')
        $('.collapsible').collapsible();
    @endsection
</script>