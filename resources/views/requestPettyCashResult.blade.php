<html>
    <head>
        <!-- TODO pagandahin to or transfer sa ibang view  -->
        <title> Petty Cash Result </title>
    </head>
    <body>
        @if($result != null)
            PCV Results <br>
            Purpose: {{ $result["purpose"] }}<br>
            Account: {{ $result["list_ta_id"] }}<br>
            Amount: {{ $result["amount"] }}<br>
        @endif
    </body>
</html>