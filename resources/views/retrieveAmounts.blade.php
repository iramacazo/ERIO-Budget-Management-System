<html>
    <head>
        <title> Input Amounts </title>
    </head>
    <body>
        <h1> Retrieve Amounts </h1>
        <h2>Date: {{ $brf->created_at }}</h2>
        <form action="" method="POST">
            <table>
                <tr>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                <?php
                    $total = 0;
                ?>
                @foreach($brf->entries as $entry)
                    <tr>
                        <td>{{ $entry->quantity }}</td>
                        <td>{{ $entry->description }}</td>
                        <td>
                            <input type="number" name="e-{{ $entry->id }}-{{ $total }}" placeholder="amount">
                        </td>
                    </tr>
                    <?php
                        $total++;
                    ?>
                @endforeach
            </table>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>