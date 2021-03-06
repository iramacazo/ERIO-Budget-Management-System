@extends('layouts.general_layout')

@section('title', 'General Journal')

@section('sidebar')
    @parent
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li class="active"><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h1> Disbursement Journal </h1>
        <table>
            <tr>
                <th> Date </th>
                <th> Details </th>
                <th> Adjust </th>
                <th> PRS Number </th>
                <th> Amount </th>
            </tr>
            @foreach( $entries as $entry )
                <tr>
                    <td valign="top"> {{ $entry->created_at->toFormattedDateString() }} </td>
                    @if( $entry->mrf_entry_id != null )
                        <td>
                            Account: {{ $entry->mrf_entry->mrf->list_PA->primary_accounts->name }}
                            <i>{{ $entry->mrf_entry->mrf->list_PA->primary_accounts->code }}</i> <br>
                            @if( $entry->mrf_entry->list_sa_id != null)
                                <i>( {{ $entry->mrf_entry->list_SA->secondary_accounts->name }} )</i>
                            @elseif( $entry->mrf_entry->list_ta_id != null)
                                <i>{{ $entry->mrf_entry->list_TA->tertiary_accounts->secondary_accounts->name }} ->
                                    {{ $entry->mrf_entry->list_TA->tertiary_accounts->name }}</i>
                            @endif
                            <br>
                            Material Requisition Form<br>
                            Description: {{ $entry->mrf_entry->description }}<br>
                        </td>
                        <td valign="top">
                            @if( $entry->adjust == false )
                                <form action="{{ route('adjustForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="mrf-{{ $entry->mrf_entry->id }}" name="id">
                                    <input type="submit" value="Adjust Entry">
                                </form>
                            @else
                                <i>Adjusting Entry</i>
                            @endif
                        </td>
                        <td valign="top">
                            @if( $entry->prs_id != null )
                                {{ $entry->prs->code }}
                            @else
                                <form action="{{ route('prsForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $entry->id }}" name="id">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td valign="top" align="right">
                            @if( $entry->adjust == false )
                                @php( $total = $entry->mrf_entry->unit_price * $entry->mrf_entry->quantity )
                                {{ number_format($total) }}
                            @else
                                @if( $entry->amount < 0 )
                                    {{ number_format($entry->amount  * -1) }}
                                @elseif( $entry->amount > 0)
                                    ({{ number_format($entry->amount) }})
                                @endif
                            @endif
                        </td>
                    @elseif( $entry->brf_id != null)
                        <td>
                            @if( $entry->brf->list_pa_id != null )
                                {{ $entry->brf->list_PA->primary_accounts->name }}
                            @elseif( $entry->brf->list_sa_id != null )
                                {{ $entry->brf->list_SA->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->brf->list_SA->secondary_accounts->name }} )
                            @elseif( $entry->brf->list_ta_id != null )
                                {{ $entry->brf->list_TA->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->brf->list_TA->tertiary_accounts->secondary_accounts->name }} )
                                ( {{ $entry->brf->list_TA->tertiary_accounts->name }} )
                            @endif
                            <br>
                            Bookstore Requisition Form <br>
                            Entries:<br>
                            @foreach( $entry->brf->entries as $b)
                                {{ $b->description }}<br>
                            @endforeach
                        </td>
                        <td valign="top">
                            @if( $entry->adjust == false )
                                <form action="{{ route('adjustForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="brf-{{ $entry->brf->id }}" name="id">
                                    <input type="submit" value="Adjust Entry">
                                </form>
                            @else
                                <i>Adjusting Entry</i>
                            @endif
                        </td>
                        <td valign="top">
                            @if( $entry->prs_id != null )
                                {{ $entry->prs->code }}
                            @else
                                <form action="{{ route('prsForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $entry->id }}" name="id">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td valign="top" align="right">
                            @if( $entry->adjust == false )
                                @php( $total = 0 )
                                @foreach($entry->brf->entries as $b)
                                    @php( $total += $b->amount )
                                @endforeach
                                {{ number_format($total) }}
                            @else
                                @if( $entry->amount < 0 )
                                    {{ number_format($entry->amount * -1) }}
                                @elseif( $entry->amount > 0)
                                    ({{ number_format($entry->amount) }})
                                @endif
                            @endif
                        </td>
                    @elseif( $entry->pcv_id != null)
                        <td>
                            @if( $entry->pcv->list_pa_id != null )
                                {{ $entry->pcv->primary_account->name }}
                            @elseif( $entry->pcv->list_sa_id != null )
                                {{ $entry->pcv->secondary_account->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->pcv->secondary_account->secondary_accounts->name }} )
                            @elseif( $entry->pcv->list_ta_id != null )
                                {{ $entry->pcv->tertiary_account->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->pcv->tertiary_account->tertiary_accounts->secondary_accounts->name }} )
                                ( {{ $entry->pcv->tertiary_account->tertiary_accounts->name }} )
                            @endif
                            <br>
                            Petty Cash<br>
                            Purpose: {{ $entry->pcv->purpose }}
                        </td>
                        <td valign="top">
                            @if( $entry->adjust == false)
                                <form action="{{ route('adjustForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="pcv-{{ $entry->pcv->id }}" name="id">
                                    <input type="submit" value="Adjust Entry">
                                </form>
                            @else
                                <i>Adjusting Entry</i>
                            @endif
                        </td>
                        <td valign="top">
                            @if( $entry->prs_id != null)
                                {{ $entry->prs->code }}
                            @else
                                <button><a href="{{ route('pettyCashView') }}"> Go to Petty Cash </a></button>
                            @endif
                        </td>
                        <td valign="top" align="right">
                            @if( $entry->adjust == false )
                                {{ number_format($entry->pcv->amount - $entry->pcv->amount_received) }}
                            @else
                                @if( $entry->amount < 0 )
                                    {{ number_format($entry->amount * -1) }}
                                @elseif( $entry->amount > 0)
                                    ({{ number_format($entry->amount) }})
                                @endif
                            @endif
                        </td>
                    @elseif( $entry->transaction_id != null)
                        <td>
                            @if( $entry->otherTransactions->list_pa_id != null )
                                {{ $entry->otherTransactions->list_PA->name }}
                            @elseif( $entry->otherTransactions->list_sa_id != null )
                                {{ $entry->otherTransactions->list_SA->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->otherTransactions->list_SA->secondary_accounts->name }} )
                            @elseif( $entry->otherTransactions->list_ta_id != null )
                                {{ $entry->otherTransactions->list_TA->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                                ( {{ $entry->otherTransactions->list_TA->tertiary_accounts->secondary_accounts->name }} )
                                ( {{ $entry->otherTransactions->list_TA->tertiary_accounts->name }} )
                            @endif
                            <br>
                            Other Transactions<br>
                            Description: {{ $entry->otherTransactions->description }}
                        </td>
                        <td valign="top">
                            @if( $entry->adjust == false)
                                <form action="{{ route('adjustForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="transac-{{ $entry->otherTransactions->id }}">
                                    <input type="submit" value="Adjust Entry">
                                </form>
                            @else
                                <i>Adjusting Entry</i>
                            @endif
                        </td>
                        <td valign="top">
                            @if( $entry->prs_id != null)
                                {{ $entry->prs->code }}
                            @else
                                <form action="{{ route('prsForm') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $entry->id }}" name="id">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td valign="top" align="right">
                            @if( $entry->adjust == false )
                                {{ number_format($entry->otherTransactions->amount) }}
                            @else
                                @if( $entry->amount < 0 )
                                    {{ number_format($entry->amount * -1) }}
                                @elseif( $entry->amount > 0)
                                    ({{ number_format($entry->amount) }})
                                @endif
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
@endsection