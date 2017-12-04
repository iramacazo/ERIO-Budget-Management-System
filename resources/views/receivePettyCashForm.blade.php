<html>
    <head>
        <title> Receive Petty Cash </title>
    </head>
    <body>
        <h3> Tempo View (Pwede maginig modal)</h3>
        <h1> Receive Petty Cash </h1>
        <form action="{{ route('receivePettyCash') }}" method="POST">
            <label>Amount Received: </label>
            {{ csrf_field() }}
            <input type="number" name="amount_received"><br>
            <input type="hidden" value="{{ $id }}" name="id">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>