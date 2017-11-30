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
        <form action="{{ route('testResults') }}" method="POST" id="form">

            <!-- APPEND THIS -->
            <input type="number" name="qty[]" placeholder="Quantity">
            <input type="description" name="desc[]" placeholder="Description">
            <p>Remove</p><br>

            <input type="submit" value="submit">
        </form>
    </body>
</html>
<script>
    $(document).ready(function(){
        $("#chook").click(function () {
            $("#form").append('<input type="description" name="desc[]" placeholder="Description">');
        })
    });
</script>