<html>
    <head>
        <title> Input Amounts </title>
    </head>
    <body>
        <h1> Retrieve Amounts </h1>
        <h2>Date: {{ $brf->created_at }}</h2>
        <form action="{{ route('saveAmountBRF') }}" method="POST">
            <table>
                <tr>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                @foreach($brf->entries as $entry)
                    <tr>
                        <td>{{ $entry->quantity }}</td>
                        <td>{{ $entry->description }}</td>
                        <td>
                            <input type="hidden" name="id[]" value="{{ $entry->id }}">
                            <input type="number" name="amount[]" placeholder="amount">
                        </td>
                    </tr>
                @endforeach
            </table>
            <input type="submit" value="Submit">
            {{ csrf_field() }}
        </form>
    </body>
</html>