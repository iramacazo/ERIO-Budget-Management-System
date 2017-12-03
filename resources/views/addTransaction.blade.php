<html>
    <head>
        <title> Add Transaction </title>
        {{-- JQuery --}}
        <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        <form action="{{ route('saveTransaction') }}" method="POST">
            {{ csrf_field() }}
            <label>Description: </label>
            <input type="text" name="description"><br>
            <label>Amount: </label>
            <input type="number" name="amount"><br>
            <label>Account Name: </label>
            <select name="account">
                @foreach(Auth::user()->accessedPA as $p)
                    <option value="p-{{ $p->accessedPrimaryAccount->id }}">
                        {{ $p->accessedPrimaryAccount->primary_accounts->name }}
                    </option>
                @endforeach
                @foreach(Auth::user()->accessedSA as $s)
                    <option value="s-{{ $s->accessedSecondaryAccount->id }}">
                        {{ $s->accessedSecondaryAccount->secondary_accounts->name }}
                        ({{ $s->accessedSecondaryAccount->secondary_accounts->primary_accounts->name }})
                    </option>
                @endforeach
                @foreach(Auth::user()->accessedTA as $t)
                    <option value="t-{{ $t->accessedTertiaryAccount->id }}">
                        {{ $t->accessedTertiaryAccount->tertiary_accounts->name }}
                        ({{ $t->accessedTertiaryAccount->tertiary_accounts->secondary_accounts->name }})
                        ({{ $t->accessedTertiaryAccount->tertiary_accounts->secondary_accounts->primary_accounts->name }})
                    </option>
                @endforeach
            </select><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>