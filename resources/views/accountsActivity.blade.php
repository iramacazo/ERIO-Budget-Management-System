<html>
    <head>
        <title> Accounts Activity </title>
    </head>
    <body>
        <h1>
            @if($type == 'Primary')
                Primary Accounts
            @endif
            @if($type == 'Secondary')
                Secondary Accounts
            @endif
            @if($type == 'Tertiary')
                Tertiary Accounts
            @endif
        </h1><br>
        @foreach($accounts as $a)
            Account Name: {{ $a['name'] }}<br>
            Expense: {{ $a['expense'] }}<br>
            Budget: {{ $a['budget'] }}<br><br>
        @endforeach
    </body>
</html>