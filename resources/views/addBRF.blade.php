<html>
    <head>
        <title> Add BRF </title>

        {{-- JQuery --}}
        <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        <h1> Add Bookstore Requisition Form</h1>
        <p> New Entry </p> <!-- Append INPUT -->
        <button id="chook">
            ADD
        </button>
        <form action="{{ route('saveBRF') }}" method="POST" id="form">
            {{ csrf_field() }}
            <select name="account">
                @if($primary != null)
                    @foreach($primary as $p)
                        <option value="p-{{ $p->id }}">
                            {{ $p->pa_name }}
                        </option>
                    @endforeach
                @endif

                @if($secondary != null)
                    @foreach($secondary as $s)
                        <option value="s-{{ $s->id }}">
                            {{ $s->sa_name }} for
                            {{ $s->pa_name }}
                        </option>
                    @endforeach
                @endif

                @if($tertiary != null)
                    @foreach($tertiary as $t)
                        <option value="t-{{ $t->id }}">
                            {{ $t->ta_name }} for
                            {{ $t->sa_name }} for
                            {{ $t->pa_name }}
                        </option>
                    @endforeach
                @endif
            </select><br>
            <!-- APPEND THIS -->
            <input type="number" name="qty[]" placeholder="Quantity">
            <input type="text" name="desc[]" placeholder="Description">
            <p id="remove"> Remove </p>

            <input type="submit" value="submit">
        </form>
    </body>
</html>
<script>
    $(document).ready(function(){
        $("#chook").click(function () {
            $("#remove").append('<br><input type="number" name="qty[]" placeholder="Quantity">' +
                '<input type="description" name="desc[]" placeholder="Description"><br>' +
                '<p id="remove"> Remove </p>')
        })
    });
</script>