<html>
    <head>
        <title>Petty Cash</title>
    </head>
    <body>
        <h3>NOTE: pagandahin eventually yung UI :D</h3><br>
        <h1>Petty Cash</h1><br><br>
        <b><a href="{{ route('request_petty_cash') }}">Request Petty Cash</a></b> <br>
        <h2>Pending for Approval</h2><br>
        @if($pending != null)
        <table>
            <tr>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Account</th>
                <th>Cancel Request</th>
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
                        <form>
                            <input type="submit" value="Cancel Request">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        @else
            There are currently NO PETTY CASH VOUCHERS Pending for Approval
        @endif
    </body>
</html>