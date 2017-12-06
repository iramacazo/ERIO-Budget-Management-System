@extends('layouts.general_layout')

@section('title', 'Petty Cash')

@section('sidebar')
    @parent
    <li class="active"><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>Petty Cash</h3>
        <h4>Pending for Approval</h4>
        @if($pending->isEmpty() == false)
            <table class="bordered highlight">
                <thead>
                    <tr>
                        <th class="center">Purpose</th>
                        <th class="center">Amount</th>
                        <th class="center">Account</th>
                        <th class="center">Approve Request</th>
                        <th class="center">Deny Request</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pending as $p)
                    <tr>
                        <td>{{ $p->purpose }}</td>
                        <td class="right-align">P{{ number_format($p->amount, 2) }}</td>
                        <td>
                            @if($p->list_pa_id != null)
                                {{ $p->primary_account->primary_accounts->name }} (Primary)
                            @elseif($p->list_sa_id != null)
                                {{ $p->secondary_account->secondary_accounts->name }} to
                                {{ $p->secondary_account->secondary_accounts->primary_accounts->name }}
                                (Secondary)
                            @else
                                {{ $p->tertiary_account->tertiary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                            @endif
                        </td>
                        <td class="center">
                            <form action="{{ route('approvePettyCash') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $p->id }}">
                                <button class="waves-effect waves-light btn green darken-3" type="submit">
                                    <i class="material-icons">check</i>
                                </button>
                            </form>
                        </td>
                        <td class="center">
                            <form action="{{ route('denyPettyCash') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $p->id }}">
                                <button class="waves-effect waves-light btn red darken-2" type="submit">
                                    <i class="material-icons">close</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="center">There are no petty cash vouchers pending for approval</p>
        @endif
        <br>
        <h4>Receive Petty Cash</h4>
        @if($receiving->isEmpty() == false)
            <table class="bordered highlight">
                <thead>
                    <tr>
                        <th class="center">Purpose</th>
                        <th class="center">Amount</th>
                        <th class="center">Account</th>
                        <th class="center">Receive PCV</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($receiving as $p)
                    <tr>
                        <td>{{ $p->purpose }}</td>
                        <td class="right-align">P{{ number_format($p->amount, 2) }}</td>
                        <td>
                            @if($p->list_pa_id != null)
                                {{ $p->primary_account->primary_accounts->name }} (Primary)
                            @elseif($p->list_sa_id != null)
                                {{ $p->secondary_account->secondary_accounts->name }} to
                                {{ $p->secondary_account->secondary_accounts->primary_accounts->name }}
                                (Secondary)
                            @else
                                {{ $p->tertiary_account->tertiary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                            @endif
                        </td>
                        <td class="center">
                            <form action="{{ route('receivePettyCashForm') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $p->id }}">
                                <button class="waves-effect waves-light btn green darken-3" type="submit"
                                        value="Receive">Receive</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="center">There are no petty cash vouchers to be returned</p>
        @endif

        <h4> Petty Cash Vouchers for Refill </h4>
        @if($refill->isEmpty() == false)
            @php( $total = 0 )
            @foreach($refill as $r)
                @php( $total += $r->amount )
            @endforeach
            @if( $total >= 5000 * .7 )
                <h4 class="red-text text-darken-2 center">Petty Cash is Below 30%!</h4>
            @endif
            <form action="{{ route('pcrf') }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="input-field col s6">
                        <label for="code"> PRS Number: </label>
                        <input type="text" name="code" id="code">
                    </div>
                </div>
                <button type="submit" class="waves-effect waves-light btn green darken-3">Refill Petty Cash</button>
            </form>
            <ul class="collection">
                @foreach($refill as $r)
                    <li class="collection-item">
                        <p>
                            <b>Requested By: </b>{{ $r->requester->name }}<br>
                            <b>Approved By: </b>{{ \App\User::where('id', $r->approved_by)->first()->name }}<br>
                            <b>Account</b>
                            @if( $r->list_pa_id != null)
                                {{ $r->primary_account->primary_accounts->name }} <br>
                            @elseif( $r->list_sa_id != null )
                                {{ $r->secondary_account->secondary_accounts->primary_accounts->name }}
                                ( {{ $r->secondary_account->secondary_accounts->name }} ) <br>
                            @else
                                {{ $r->tertiary_account->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                                ( {{ $r->tertiary_account->tertiary_accounts->secondary_accounts->name }} )
                                ( {{ $r->tertiary_account->tertiary_accounts->name }} )<br>
                            @endif
                            <b>Purpose: </b>{{ $r->purpose }}<br>
                            <b>Amount: </b>P{{ number_format($r->amount, 2) }}<br>
                        </p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="center">There are no Petty Cash for refill</p>
        @endif
    </div>
@endsection