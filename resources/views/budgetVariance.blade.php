<html>
    <head>
        <title> Budget Variance </title>
    </head>
    <body>
        <table>
            <tr>
                <th> Oracle Code</th>
                <th> Account </th>
                <th> Budget </th>
                <th> Actual </th>
                <th> Variance </th>
                <th> Variance Percentage </th>
            </tr>
            @foreach($list as $l)
                <tr>
                    <td>{{ $l['code'] }}</td>
                    <td>{{ $l['name'] }}</td>
                    <td>{{ number_format($l['budget']) }}</td>
                    <td>{{ number_format($l['actual']) }}</td>
                    <td>
                        @if( $l['diff'] < 0)
                            ({{ number_format($l['diff'] * -1) }})
                        @else
                            {{ number_format($l['diff']) }}
                        @endif
                    </td>
                    <td>{{ number_format($l['variance'], 2) }}%</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>