<html>
    <head>
        <title> Petty Cash </title>
    </head>
    <body>
    <h3>NOTE: pagandahin eventually yung UI :D</h3><br>
    <h1>Petty Cash</h1><br><br>
    <h2>Pending for Approval</h2><br>
    @if($pending != null)
        <table>
            <tr>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Account</th>
                <th>Approve Request</th>
                <th>Cancel Request</th>
                <th>Deny Request</th>
            </tr>
            @foreach($pending as $p)
                <tr>
                    <td>{{ $p->purpose }}</td>
                    <td>{{ $p->amount }}</td>
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
                    <td>
                        <form action="{{ route('approvePettyCash') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $p->id }}">
                            <input type="submit" value="Approve">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('cancelPettyCash') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $p->id }}">
                            <input type="submit" value="Cancel">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('denyPettyCash') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $p->id }}">
                            <input type="submit" value="Deny">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        There are currently NO PETTY CASH VOUCHERS Pending for Approval <br>
    @endif

    <h2>Receive Petty Cash</h2>
    @if($receiving != null)
        <table>
            <tr>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Account</th>
                <th>Receive PCV</th>
            </tr>
            @foreach($receiving as $p)
                <tr>
                    <td>{{ $p->purpose }}</td>
                    <td>{{ $p->amount }}</td>
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
                    <td>
                        <form action="{{ route('receivePettyCashForm') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $p->id }}">
                            <input type="submit" value="Receive">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        There are currently NO PETTY CASH VOUCHERS to be returned <br>
    @endif

    <h2> Petty Cash Vouchers for Refill </h2>
    @if($refill != null)
        @php( $total = 0 )
        @foreach($refill as $r)
            @php( $total += $r->amount )
        @endforeach
        <form action="{{ route('pcrf') }}" method="POST">
            {{ csrf_field() }}
            <label> PRS Number: </label>
            <input type="text" name="code"><br>
            <input type="submit" value="Refill Petty Cash"><br>
        </form>

        @if( $total >= 5000 * .7 )
            You must refill now!
        @endif

        @foreach($refill as $r)
            Requested By: {{ $r->requested_by }}<br>
            Approved By: {{ $r->approved_by }}<br>
            Account:
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
            Purpose: {{ $r->purpose }}<br>
            Amount: {{ $r->amount }}<br>
            <br>
        @endforeach
    @else
        There are no Petty Cash for refill
    @endif
    </body>
</html>