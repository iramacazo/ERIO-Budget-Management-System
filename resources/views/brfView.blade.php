<html>
    <head>
        <title> BRF </title>
    </head>
    <body>
        <h1>Bookstore Requisition Forms</h1><br>
        <a href="{{ route('brfAdd')}}">Add BRF</a><br>
        <h2> Pending BRF with Amount </h2>
        @if($brfB != null)
            @foreach($brfB as $b)
                Date:{{ $b->created_at }}<br>
                <form action="{{ route('brfAccess') }}" method="POST">
                    <input type="hidden" value="{{ $b->id }}" name="id">
                    <input type="submit" value="Retrieve Amounts">
                    <input type="print" value="Print">
                </form>
                <table>
                    <tr>
                        <th>quantity</th>
                        <th>description</th>
                    </tr>
                    @foreach($b->entries as $entry)
                        <tr>
                            <td>{{ $entry->quantity }}</td>
                            <td>{{ $entry->description }}</td>
                        </tr>
                    @endforeach
                </table><br><br>
            @endforeach
        @else
            <h2>There are no Pending BRF</h2>
        @endif
        <br>
        <h2> BRF </h2>
        @if($brfA != null)
            @foreach($brfA as $a)
                Date:{{ $a->created_at }}<br>
                <form action="{{ route('brfAccess') }}" method="POST">
                    <input type="hidden" value="{{ $b->id }}" name="id">
                    <input type="print" value="Print">
                </form>
                <table>
                    <tr>
                        <th>quantity</th>
                        <th>description</th>
                        <th>unit price</th>
                        <th>amount</th>
                    </tr>
                    <?php
                        $total = 0;
                    ?>
                    @foreach($a->entries as $entry)
                        <tr>
                            <td>{{ $entry->quantity }}</td>
                            <td>{{ $entry->description }}</td>
                            <td>{{ $entry->amount/$entry->quantity }}</td>
                            <td>{{ $entry->amount }}</td>
                            <?php
                                $total += $entry->amount;
                            ?>
                        </tr>
                    @endforeach
                </table><br>
                Total Amount: {{ $total }}
                <br>
            @endforeach
        @endif
    </body>
</html>