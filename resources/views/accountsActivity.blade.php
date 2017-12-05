@extends('layouts.general_layout')

@section('title', $type . ' account activity')

@section('sidebar')
    @parent
    <li><a href="{{route('requestsForAccess')}}">Account Access Requests</a></li>
    <li><a href="{{ route('execMRF') }}">Material Requisition Forms</a></li>
    <li><a class="subheader">Reports</a></li>
    <li class="active"><a href="{{ route('accountsActivityPA') }}"> Accounts Activity </a></li>
    <li><a href="{{ route('transactionsToday') }}"> Transactions Today </a></li>
    <li><a href="{{ route('budgetVariance') }}"> Budget Variance </a></li>
@endsection

@section('stylesheet')
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px;">
        <h3>{{$type}} Account</h3>
        <ul class="pagination center">
            <li class="{{$type == "Primary" ? 'disabled': 'waves-effect'}}">
                <a href="{{$type == "Tertiary" ? route('accountsActivitySA'): route('accountsActivityPA')}}">
                    <i class="material-icons">chevron_left</i></a></li>
            <li class="{{$type == "Primary" ? 'active green darken-4': 'waves-effect'}}">
                <a href="{{route('accountsActivityPA')}}">Primary Accounts</a></li>
            <li class="{{$type == "Secondary" ? 'active green darken-4': 'waves-effect'}}">
                <a href="{{ route('accountsActivitySA') }}">Secondary Accounts</a></li>
            <li class="{{$type == "Tertiary" ? 'active green darken-4': 'waves-effect'}}">
                <a href="{{ route('accountsActivityTA') }}">Tertiary Accounts</a></li>
            <li class="{{$type == "Tertiary" ? 'disabled': 'waves-effect'}}">
                <a href="{{$type == "Primary" ? route('accountsActivitySA'): route('accountsActivityTA')}}">
                    <i class="material-icons">chevron_right</i></a></li>
        </ul>
        <br>
        <div class="input field col s4 offset-s8">
            <select id="accounts">
                <option value="overview">Overview</option>
                @foreach($accounts as $a)
                    <option value="{{$a['name']}}">{{ $a['name'] }}</option>
                @endforeach
            </select>
            <label for="accounts">Accounts</label>
        </div>
        <div id="data-area">
            <ul class="collection" id="accounts_overview">
                @foreach($accounts as $a)
                    <li class="collection-item">
                        <p>
                            <b>Account Name: </b>{{ $a['name'] }}<br>
                            <b>Expense: </b>P{{ number_format($a['expense'], 2) }}<br>
                            <b>Budget: </b>P{{ number_format($a['budget'], 2) }}
                        </p>
                    </li>
                @endforeach
            </ul>
            <br>
            <br>
            <div class="row">
                <div class="col s12" id="chartContainer" style="height: 370px; width: 100%; display: none"></div>
            </div>

        </div>
    </div>
@endsection

<script>
    @section('script')
        $('select').material_select();
        var array = @json($accounts);
        $('#accounts').on('change', function () {
            if(this.value === 'overview'){
                $('#accounts_overview').show();
                $('#chartContainer').hide();
            }else{
                var budget = 0;
                var expense = 0;
                var name = "";
                $.each(array, function (index) {
                    if(array[index]["name"] === $("#accounts").find(':selected').text()){
                        name = array[index]["name"];
                        expense = array[index]["expense"];
                        budget = array[index]['budget'];
                    }
                });
                $('#accounts_overview').hide();
                $('#chartContainer').show();
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title: {
                        text: name
                    },
                    data: [{
                        type: "pie",
                        startAngle: 240,
                        toolTipContent: "{label}: <strong>{w}</strong>",
                        yValueFormatString: "##0.00\"%\"",
                        indexLabel: "{label} {y}",
                        dataPoints: [
                            {y: (expense/budget) * 100,
                                w: ("P" + replaceNumberWithCommas(expense)), label: "Expense"},
                            {y: 100 - ((expense/budget) * 100),
                                w: ("P" + replaceNumberWithCommas(budget)), label: "Budget"}
                        ]
                    }]
                });
                chart.render();
            }
        });

    function replaceNumberWithCommas(yourNumber) {
        //Seperates the components of the number
        var n= yourNumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }
    @endsection
</script>