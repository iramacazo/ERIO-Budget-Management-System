<html>
<head>
    <style>
        table,td,th,tr{
            border: 1px solid darkgreen;
        }
        td.amount{
            text-align: right;
        }
        td.center{
            text-align: center;
        }
    </style>
</head>
<body>
@php
$prev_grand_total = 0;
$grand_total = 0;
    foreach($prev_primary_accounts_list as $prev)
        $prev_grand_total+=$prev->amount;
@endphp
<table>
    <th>BUDGET: ERI {{ $proposed_ay }}</th> <!-- TODO Academic Year -->
    <th>ORACLE CODE</th>
    <th>APPROVED BUDGET {{ $previous_ay }}</th>
    <th>PROPOSED BUDGET {{ $proposed_ay }}</th>
    <th>REMARKS</th>
    <tr>
        <td>-</td>
    </tr>
    <tr>
        <td>Account Number</td>
        <td class="center"><b>01220</b></td>
    </tr>
    <tr>
        <td>-</td>
    </tr>
    <tr>
        <td>OPERATING ACCOUNTS</td>
    </tr>
    @if($primary_accounts_list != null)
    @foreach($primary_accounts_list as $pal)

            <tr>
                <td>{{ $pal->name }}</td>
                <td class="center"><b>{{ $pal->code }}</b></td>
                @foreach($prev_primary_accounts_list as $prev)
                    @if($prev->code == $pal->code)
                    @php($previous = $prev->amount)
                    @endif
                @endforeach
                @php($grand_total+=$pal->amount)
                @if(isset($previous) && $previous != null)
                    <td class="amount">{{ $previous }}</td>
                    @php($previous = null)
                @else
                    <td class="amount">N/A</td>
                @endif
                <td class="amount">{{ number_format($pal->amount) }}</td>
                <td><textarea></textarea></td>
            </tr>
    @endforeach
        <tr>
            <td>-</td>
        </tr>
        <tr>
            <td>GRAND TOTAL: Operating Accounts Budget</td>
            <td></td>
            <td class="amount"><b>{{ number_format($prev_grand_total) }}</b></td>
            <td class="amount"><b>{{ number_format($grand_total) }}</b></td>
            <td><textarea></textarea></td>
        </tr>
    @endif
</table>
</body>
</html>