<html>
    <head>
        <title> General Journal </title>
    </head>
    <body>
        <h1> General Journal </h1>
        <table>
            <tr>
                <th> Date </th>
                <th> Account </th>
                <th> Description </th>
                <th> PRS Number </th>
                <th> Credit </th>
            </tr>
            @foreach( $entries as $entry )
                <tr>
                    <td> {{ $entry->created_at }} </td>
                    @if( $entry->mrf_entry_id != null )
                        <td>
                            {{ $entry->mrf_entry->mrf->list_PA->primary_accounts->name }}
                            @if( $entry->mrf_entry->list_sa_id != null)
                                ( {{ $entry->mrf_entry->list_SA->secondary_accounts->name }} )
                            @elseif( $entry->mrf_entry->list_ta_id != null)
                                ( {{ $entry->mrf_entry->list_TA->tertiary_accounts->secondary_accounts->name }} )
                                ( {{ $entry->mrf_entry->list_TA->tertiary_accounts->name }} )
                            @endif
                        </td>
                        <td>
                            Insert Link
                        </td>
                        <td>
                            @if( $entry->mrf_entry->prs_id != null )
                                {{ $entry->mrf_entry->prs->code }}
                            @else
                                <form action="" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="mrf-{{ $entry->mrf_entry->id }}" name="id">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td>
                            {{ $entry->mrf_entry->unit_price * $entry->mrf_entry->quantity }}
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
                        </td>
                        <td>
                            Insert Link
                        </td>
                        <td>
                            @if( $entry->brf->prs_id != null )
                                {{ $entry->brf->prs->code }}
                            @else
                                <form action="" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="brf-{{ $entry->brf->id }}">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td>
                            @php( $total = 0 )
                            @foreach($entry->brf->entries as $b)
                                @php( $total += $b->amount )
                            @endforeach
                            {{ $total }}
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
                        </td>
                        <td>
                            Insert Link
                        </td>
                        <td>
                            @if( $entry->pcv->prs_id != null)
                                {{ $entry->pcv->prs->code }}
                            @else
                                <form action="" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="pcv-{{ $entry->pcv->id }}">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td>
                            {{ $entry->pcv->amount }}
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
                        </td>
                        <td>
                            Insert Link
                        </td>
                        <td>
                            @if( $entry->otherTransactions->prs_id != null)
                                {{ $entry->otherTransactions->prs->code }}
                            @else
                                <form action="" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="transac-{{ $entry->otherTransactions->id }}">
                                    <input type="submit" value="Generate PRS">
                                </form>
                            @endif
                        </td>
                        <td>
                            {{ $entry->otherTransactions->amount }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    </body>
</html>