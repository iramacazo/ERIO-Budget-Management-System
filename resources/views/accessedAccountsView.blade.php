<html>
    <head>
        <title> Accessed Accounts </title>
    </head>
    <body>
        <h1> Accessed Accounts </h1><br>
        <a href="">Request Access</a><br>
        <br>
        @if($primary != null)
            @foreach($primary as $p)
                {{ $p->accessedPrimaryAccount->primary_accounts->name }}<br>
            @endforeach
        @endif
        <br>
        @if($secondary != null)
            @foreach($secondary as $s)
                {{ $s->accessedSecondaryAccount->secondary_accounts->name }}<br>
            @endforeach
        @endif
        <br>
        @if($tertiary != null)
            @foreach($tertiary as $t)
                {{ $t->accessedTertiaryAccount->tertiary_accounts->name }}<br>
            @endforeach
        @endif
        <br>

        <h2> Pending Requests </h2><br>
        @if($pendingPA != null)
            @foreach($pendingPA as $p)
                {{ $p->accessedPrimaryAccount->primary_accounts->name }}<br>
            @endforeach

            @if($pendingSA != null)
                @foreach($pendingSA as $s)
                    {{ $s->accessedSecondaryAccount->secondary_accounts->name }} for
                    {{ $s->accessedSecondaryAccount->secondary_accounts->primary_accounts->name }}<br>
                @endforeach

                @if($pendingTA != null)
                    @foreach($pendingTA as $t)
                        {{ $t->accessedTertiaryAccount->tertiary_accounts->name }} for
                        {{ $t->accessedTertiaryAccount->tertiary_accounts->secondary_accounts->name }}<br>
                    @endforeach
                @endif
            @endif
        @else
            There are currently no pending requests
        @endif
    </body>
</html>