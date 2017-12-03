<html>
    <head>
        <title> Pick Account </title>
    </head>
    <body>
        <h2> Pick Account </h2>
        <form action="{{ route('journalPrimary') }}" method="GET">
            <select name="account">
                @foreach($primary as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->primary_accounts->name }}
                    </option>
                @endforeach
            </select>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>