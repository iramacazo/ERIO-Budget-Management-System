<html>
<head>
    
</head>
<body>
<form action="{{url('/propose/create')}}">
    {{ csrf_field() }}
    Start Range:
    <input type="date" name="start_date">
    End Range:
    <input type="date" name="end_date">
    <input type="submit" name="submit" value="Create Budget">
</form>
</body>
</html>