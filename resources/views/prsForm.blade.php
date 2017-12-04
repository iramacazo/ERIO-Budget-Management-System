<html>
    <head>
        <title> PRS Form </title>
    </head>
    <body>
        <h1> Generate PRS </h1>
        <form action="{{ route('generatePRS') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="text" name="code">
            <input type="submit" value="Generate PRS">
        </form>
    </body>
</html>