<html>
    <head>
        <title> MRF - Receive Amounts </title>
    </head>
    <body>
        <h1> Receive Amounts </h1>
        <form action="{{ route('saveAmountsMRF') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="{{ $mrf->id }}" name="mrf_id">
            @foreach($mrf->entries as $entry)
                Description: {{ $entry->description }}<br>
                Quantity: {{ $entry->quantity }}<br>
                <label>Amount</label>
                <input type="number" name="unit_price[]"><br>
                <label>Supplier</label>
                <input type="text" name="supplier[]"><br>
            @endforeach
            <input type="submit" value="Submit">
        </form>
    </body>
</html>