<html>
    <head>
        <!-- TODO pagandahin to or transfer sa ibang view  -->
        <title> Petty Cash Result </title>
    </head>
    <body>
        @if($result != null)
            PCV Results <br>
            Purpose: {{ $result["purpose"] }}<br>
            @if($result["list_pa_id"] != null)
                Account: {{ $result["list_pa_id"] }} (Primary)<br>
            @elseif($result["list_sa_id"] != null)
                Account: {{ $result["list_sa_id"] }} (Secondary)<br>
            @else
                Account: {{ $result["list_ta_id"] }} (Tertiary)<br>
            @endif

            Amount: {{ $result["amount"] }}<br>
        @endif
    </body>
</html>