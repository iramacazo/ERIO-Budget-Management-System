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
                        <option value="p-{{ $p->id }}">
                            {{ $p->primary_name }}
                        </option>
                    @endforeach
                @endif

                @if($secondary != null)
                    @foreach($secondary as $s)
                        <option value="s-{{ $s->id }}">
                            {{ $s->secondary_name }} for
                            {{ $s->primary_name }}
                        </option>
                    @endforeach
                @endif

                @if($tertiary != null)
                    @foreach($tertiary as $t)
                        <option value="t-{{ $t->id }}">
                            {{ $t->tertiary_name }} for
                            {{ $t->secondary_name }} for
                            {{ $t->primary_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <input type="text" name="explanation">
            <input type="submit" value="Send">
        </form>
    </body>
</html>