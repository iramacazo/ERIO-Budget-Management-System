<html>
    <head>
        <title> Request Access Form </title>
    </head>
    <body>
        <form action="{{ route('requestAccessSave') }}" method="POST">
            {{ csrf_field() }}
            <select name="account">
                @if($primary != null)
                    @foreach($primary as $p)
                        <option value="p-{{ $p->account_id }}">
                            {{ $p->primary_accounts->name }}
                        </option>
                    @endforeach
                @endif

                @if($secondary != null)
                    @foreach($secondary as $s)
                        <option value="s-{{ $s->account_id }}">
                            {{ $s->secondary_accounts->name }} for
                            {{ $s->secondary_accounts->primary_accounts->name }}
                        </option>
                    @endforeach
                @endif

                @if($tertiary != null)
                    @foreach($tertiary as $t)
                        <option value="t-{{ $t->account_id }}">
                            {{ $t->tertiary_accounts->name }} for
                            {{ $t->tertiary_accounts->secondary_accounts->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <input type="text" name="explanation">
            <input type="submit" value="Send">
        </form>
    </body>
</html>