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
    Note: User cannot access this page if naka create na ng budget proposal <br>
    Note: User is redirected to this page if they click edit budget proposal tas wala pa
</form>
</body>
</html>