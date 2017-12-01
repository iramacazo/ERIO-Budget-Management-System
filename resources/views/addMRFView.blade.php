<html>
    <head>
        <title> Add MRF </title>
        {{-- JQuery --}}
        <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        <h1> Add MRF </h1>
        <button id="chook"> Add Entry </button><br>
        <form action="{{ route('saveMRF') }}" method="POST">
            {{ csrf_field() }}
            <input type="text" placeholder="Form Num" name="form_num"><br>
            <input type="date" placeholder="Date Needed" name="date_needed"><br>
            <input type="text" placeholder="Place of Delivery" name="place_of_delivery"><br>
            <input type="text" placeholder="Department Unit" name="dept"><br>
            <h2>Entries</h2><br>
            <input type="text" placeholder="Description" name="desc[]">
            <input type="number" placeholder="Quantity" name="qty[]">
            <select name="acc[]" id="accounts">
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
            </select>
            <p id="remove">Remove</p>
            <input type="submit" name="Request">
        </form>
    </body>
</html>
<script>
    $(document).ready(function(){
        $("#chook").click(function () {
            var options = $('#accounts').html();
            $("#remove").append('<br><input type="text" name="desc[]" placeholder="Description">' +
                '<input type="number" name="qty[]" placeholder="Quantity"><br>' +
                '<select name="acc[]" id="accounts">' +
                    options +
                '</select>' +
                '<p id="remove"> Remove </p>')
        })
    });
</script>