<html>
    <head>
        <title> MRF - Receive Amounts </title>
    </head>
    <body>
        <h1> Receive Amounts </h1>
        Form Num: {{ $mrf->form_num }} <br>
        Account: {{ $mrf->list_PA->primary_accounts->name }}<br>
        Date Needed: {{ $mrf->date_needed }}<br>

        <form action="{{ route('saveAmountsMRF') }}" method="POST">
            {{ csrf_field() }}

            <input type="hidden" value="{{ $mrf->id }}" name="mrf_id">
            @foreach($mrf->entries as $entry)
                Description: {{ $entry->description }}<br>
                Quantity: {{ $entry->quantity }}<br>
                <label>Amount</label>
                <input type="number" name="unit_price[]" placeholder="Unit Price"><br>
                <label>Supplier</label>
                <input type="text" name="supplier[]" placeholder="Supplier"><br>
            @endforeach

            <input type="submit" value="Save">
        </form>
    </body>
</html>