<html>
    <head>
        <title> Transactions </title>
    </head>
    <body>
        <h1> Transactions </h1> <br>
        <a href="{{ route('addTransaction') }}"> Add Transaction </a><br>
        <h2> Transactions </h2><br>
        @foreach(Auth::user()->otherTransactions as $transaction)
            Date: {{ $transaction->created_at }}<br>
            Description: {{ $transaction->description }}<br>
            Account Name:
            @if($transaction->list_pa_id != null)
                {{ $transaction->list_PA->primary_accounts->name }}<br>
            @elseif($transaction->list_sa_id != null)
                {{ $transaction->list_SA->secondary_accounts->name }}
                ({{ $transaction->list_SA->secondary_accounts->primary_accounts->name }})<br>
            @else
                {{ $transaction->list_TA->tertiary_accounts->name }}
                ({{ $transaction->list_TA->tertiary_accounts->secondary_accounts->name }})
                ({{ $transaction->list_TA->tertiary_accounts->secondary_accounts->primary_accounts->name }})<br>
            @endif
            Amount: {{ $transaction->amount }}<br>
            <br>
        @endforeach
    </body>
</html>