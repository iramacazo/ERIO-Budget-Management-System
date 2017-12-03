@extends('layouts.general_layout')

@section('title', 'Approve Material Requisition Forms')

@section('sidebar')
    @parent
    <li><a href="{{route('requestsForAccess')}}">Account Access Requests</a></li>
    <li class="active"><a href="{{ route('execMRF') }}">Material Requisition Forms</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3> Material Requisition Forms for Approval</h3>

        @if($pending->isEmpty())
            <br>
            <h4 class="center">There are currently no Pending MRFs for Approval</h4>
        @else
            @foreach($pending as $p)
                <ul class="collapsible">
                    <li>
                        <div class="collapsible-header">
                            <p>
                                <b>Form No: </b> {{$p->form_num}}<br>
                                <b>Date Needed: </b> {{$p->date_needed}}<br>
                                <b>Date Requested: </b> {{$p->created_at}}<br>
                                <b>Account: </b> {{$p->list_PA->primary_accounts->name }}<br>
                            </p>
                        </div>
                        <div class="collapsible-body" style="padding: 0 2rem 0 2rem">
                            <ul class="collection">
                                @foreach($p->entries as $entry)
                                    <li class="collection-item">
                                        <p style="margin: 0">
                                            <b>Description: </b>{{ $entry->description }}<br>
                                            <b>Quantity: </b>{{ $entry->quantity }} <br>
                                            <b>Account Name: </b>@if($entry->list_sa_id != null)
                                                {{ $entry->list_SA->secondary_accounts->name }}
                                            @elseif($entry->list_ta_id != null)
                                                {{ $entry->list_TA->tertiary_accounts->name }} for
                                                {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }}
                                    @endif
                                @endforeach
                                <li class="collection-item">
                                    <div>
                                        <form action="{{ route('approveMRF') }}" style="width: 0; display: inline" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $p->id }}" name="id">
                                            <button type="submit" class="waves-effect waves-light btn green darken-3">Approve</button>
                                        </form>
                                        <form action="{{ route('printMRF') }}" style="width: 0; display: inline" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $p->id }}" name="id">
                                            <button type="submit" class="waves-effect waves-light btn green darken-3">Print</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            @endforeach
        @endif
    </div>
@endsection
<script>
    @section('script')
        $('.collapsible').collapsible();
    @endsection
</script>
