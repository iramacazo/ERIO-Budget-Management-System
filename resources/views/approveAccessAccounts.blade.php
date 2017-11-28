<html>
    <head>
        <title> Approve Access </title>
    </head>
    <body>
        <h1> Requests for Access </h1>
        <table>
            <tr>
                <th>Account Name</th>
                <th>Requestee</th>
                <th>Explanation</th>
                <th>Approve</th>
            </tr>
        @if($primary != null)
            @foreach($primary as $p)
                <tr>
                    <td>{{ $p->account_name }}</td>
                    <td>{{ $p->user_name }}</td>
                    <td>{{ $p->explanation }}</td>
                    <td>
                        <form action="{{ route('respondRequest') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" value="p-{{ $p->id }}" name="id">
                            <input type="submit" value="Approve">
                            <input type="submit" value="Deny">
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif

        @if($secondary != null)
            @foreach($secondary as $s)
                 <tr>
                     <td>{{ $s->account_name }}</td>
                     <td>{{ $s->user_name }}</td>
                     <td>{{ $s->explanation }}</td>
                     <td>
                         <form action="{{ route('respondRequest') }}" method="POST">
                             {{ csrf_field() }}
                             <input type="hidden" value="s-{{ $s->id }}" name="id">
                             <input type="submit" value="Approve">
                             <input type="submit" value="Deny">
                         </form>
                     </td>
                 </tr>
            @endforeach
        @endif

        @if($tertiary != null)
            @foreach($tertiary as $t)
                <tr>
                    <td>{{ $t->account_name }}</td>
                    <td>{{ $t->user_name }}</td>
                    <td>{{ $t->explanation }}</td>
                    <td>
                        <form action="{{ route('respondRequest') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" value="t-{{ $t->id }}" name="id">
                            <input type="submit" value="Approve">
                            <input type="submit" value="Deny">
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif

        </table>
    </body>
</html>