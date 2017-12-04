<html>
<head>
    <style>
        .account{
            border:1px solid black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <ul class="breadcrumb">
        <li><a href="{{ url('/propose/add')}}">Propose</a></li>
        @if(isset($account_1))
            <li>
                <?php $link = '/propose/add/'.$account_1 ?>
                <a href="{{ url($link) }}">{{$account_1}}</a>
            </li>
        @endif
        @if(isset($account_2)&&isset($account_1))
            <li>
                <?php $link = '/propose/add/'.$account_1.'/'.$account_2 ?>
                <a href="{{ url($link) }}">{{$account_2}}</a>
            </li>
        @endif
    </ul>
    <div id="erros">
        @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <form action="{{ url('add-account-proposal') }}" method="post">
        <h3>Add Account</h3>
        <input type="text" id="add-account" name="account">
        <h3>Budget: </h3>
        <input type="number" id="account-budget" name="budget">
        @if(isset($pa) && $pa)
            <h3>Code: </h3>
            <input type="number" id="account-code" name="code">
        @endif
        <br>
        @if(isset($account_1))
            <input type="hidden" value="{{$account_1}}" name="account_p">
        @endif
        @if(isset($account_2))
            <input type="hidden" value="{{$account_2}}" name="account_s">
        @endif
        <input type="submit" name="submit">
        {{ csrf_field() }}
    </form>
    <div id="accounts-div">
        <h2>List of Accounts: </h2>
        @if(isset($accounts)&&isset($account_1)&&isset($account_2))
            @foreach($accounts as $s)
                <div class="account">
                    {{$s->name}}
                    Budget: {{$s->amount}}
                    <form action="{{url('/propose/modify')}}" method="post">
                        <input type="text" placeholder="New Account Name..." name="account">
                        <input type="number" placeholder="New Budget..." name="budget">
                        <input type="submit" name="submit" value="Edit">
                        <input type="submit" name="submit" value="Delete">
                        <input type="hidden" name="tertiary_account" value="{{$s->name}}">
                        <input type="hidden" name="secondary_account" value="{{$account_2}}">
                        <input type="hidden" name="primary_account" value="{{$account_1}}">
                        {{csrf_field()}}
                    </form>
                </div>
            @endforeach
        @elseif(isset($accounts)&&isset($account_1))
            @foreach($accounts as $s)
                <div class="account">
                    <?php $link = '/propose/add/'.$account_1.'/'.$s->name ?>
                    <a href="{{ url($link) }}">{{$s->name}}</a>
                    Budget: {{$s->amount}}
                        <form action="{{url('/propose/modify')}}" method="post">
                            <input type="text" placeholder="New Account Name..." name="account">
                            @if($s->list_id == null)
                            <input type="number" placeholder="New Budget..." name="budget">
                            @endif
                            <input type="submit" name="submit" value="Edit">
                            <input type="submit" name="submit" value="Delete">
                            <input type="hidden" name="secondary_account" value="{{$s->name}}">
                            <input type="hidden" name="primary_account" value="{{$account_1}}">
                            {{csrf_field()}}
                        </form>
                </div>
            @endforeach
        @elseif(isset($accounts))
            @foreach($accounts as $s)
                <div class="account">
                    <?php $link = '/propose/add/'.$s->name ?>
                    <a href="{{ url($link) }}">{{$s->name}}</a>
                    Budget: {{$s->amount}}
                    Code: {{ $s->code }}
                        <form action="{{url('/propose/modify')}}" method="post">
                            <input type="text" placeholder="New Account Name..." name="account">
                            @if($s->list_id == null)
                            <input type="number" placeholder="New Budget..." name="budget">
                            @endif
                            <input type="number" placeholder="New Oracle Code..." name="code">
                            <input type="submit" name="submit" value="Edit">
                            <input type="submit" name="submit" value="Delete">
                            <input type="hidden" name="primary_account" value="{{$s->name}}">
                            {{csrf_field()}}
                        </form>
                </div>
            @endforeach
            {{ "Total Budget: ". $total_budget }}
        @else
            <!-- TODO sabihin walang laman -->
        @endif
    </div>
    <br>
    <a href="{{url('/propose/print')}}">Print Budget Proposal</a>
    <br>
    <br>
    @if(isset($pa) && $pa)
    <div>
        <form action="{{url('/propose/save')}}">
            <input type="checkbox" name="approved_vp" value="approved"> Approved By Executive
            <input type="checkbox" name="approved_ac" value="approved"> Approved By Accounting
            <input type="submit" value="Save Budget">
            Palagay are you sure
        </form>
    </div>
    @endif
</body>
</html>