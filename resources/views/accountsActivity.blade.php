<html>
    <head>
        <title> Accounts Activity </title>
    </head>
    <body>
        @if($type == 'Primary')
            <h1>Primary Accounts</h1><br>
            <a href="{{ route('accountsActivitySA') }}">Secondary Accounts</a><br>
            <a href="{{ route('accountsActivityTA') }}">Tertiary Accounts</a><br>
        @endif
        @if($type == 'Secondary')
            <h1>Secondary Accounts</h1>
            <a href="{{ route('accountsActivityPA') }}">Primary Accounts</a><br>
            <a href="{{ route('accountsActivityTA') }}">Tertiary Accounts</a><br>
        @endif
        @if($type == 'Tertiary')
            <h1>Tertiary Accounts</h1>
            <a href="{{ route('accountsActivityPA') }}">Primary Accounts</a><br>
            <a href="{{ route('accountsActivitySA') }}">Secondary Accounts</a><br>
        @endif
        <br>
        @foreach($accounts as $a)
            Account Name: {{ $a['name'] }}<br>
            Expense: {{ $a['expense'] }}<br>
            Budget: {{ $a['budget'] }}<br><br>
        @endforeach
    </body>
</html>