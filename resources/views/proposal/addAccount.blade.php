@extends('layouts.general_layout')

@section('title', 'Add Accounts')

@section('sidebar')
    @parent
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li class="active"><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
    <li><a href="{{ route('primaryLedger') }}"> Ledger Accounts </a></li>
@endsection


@section('content')
    <div class="col s6 offset-s3 white z-depth-2" style="padding: 0">
        <nav>
            <div class="nav-wrapper green darken-3" style="width: 100%;">
                <div class="col s12">
                    <a href="{{ url('/propose/add')}}" class="breadcrumb">Propose</a>
                    @if(isset($account_1))
                        <?php $link = '/propose/add/'.$account_1 ?>
                        <a href="{{ url($link) }}" class="breadcrumb">{{$account_1}}</a>
                    @endif
                    @if(isset($account_2)&&isset($account_1))
                        <?php $link = '/propose/add/'.$account_1.'/'.$account_2 ?>
                        <a href="{{ url($link) }}" class="breadcrumb">{{$account_2}}</a>
                    @endif
                </div>
            </div>
        </nav>

        <div style="padding: 15px">
            <div id="errors">
                @if($errors->any())
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <h3>Add Account</h3>
            <form action="{{ url('add-account-proposal') }}" method="post">
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="add-account" name="account" required>
                        <label for="add-account">Account Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="account-budget" class="number" name="budget" required>
                        <label for="account-budget">Account Budget</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input class="number" type="text" id="account-code" name="code" required>
                        <label for="account-code">Account Code (Oracle Code)</label>
                    </div>
                </div>
                <br>
                @if(isset($account_1))
                    <input type="hidden" value="{{$account_1}}" name="account_p">
                @endif
                @if(isset($account_2))
                    <input type="hidden" value="{{$account_2}}" name="account_s">
                @endif
                <button class="waves-effect waves-light btn green darken-3" type="submit" name="submit">Submit</button>
                {{ csrf_field() }}
            </form>
            <div id="accounts-div">
                <h3>List of Accounts</h3>
                @if(isset($accounts) && isset($account_1)&&isset($account_2))
                    <ul class="collapsible" data-collapsible="accordion">
                    @foreach($accounts as $s)
                        <li>
                            <div class="collapsible-header">
                                <p>
                                    <b>{{$s->name}}</b><br>
                                    <b>Budget: </b> {{number_format($s->amount)}}
                                </p>
                            </div>
                            <div class="collapsible-body">
                                <form action="{{url('/propose/modify')}}" method="post">
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" id="new_name" name="account">
                                            <label for="new_name">New Name</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" id="new_budget" class="number" name="budget">
                                            <label for="new_budget">New Budget</label>
                                        </div>
                                    </div>
                                    <button class="waves-effect waves-light btn green darken-3" type="submit"
                                            name="submit">Save Changes</button>
                                    <button class="waves-effect waves-light btn red darken-2" type="submit"
                                            name="submit">Delete</button>
                                    <input type="hidden" name="tertiary_account" value="{{$s->name}}">
                                    <input type="hidden" name="secondary_account" value="{{$account_2}}">
                                    <input type="hidden" name="primary_account" value="{{$account_1}}">
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                @elseif(isset($accounts)&&isset($account_1))
                    <ul class="collapsible" data-collapsible="accordion">
                    @foreach($accounts as $s)
                        <li>
                            <div class="collapsible-header">
                                <p>
                                    @php($link = '/propose/add/'.$account_1.'/'.$s->name)
                                    <a href="{{ url($link) }}" style="font-weight: bold">{{$s->name}}</a><br>
                                    <b>Budget: </b>{{number_format($s->amount)}}
                                </p>
                            </div>
                            <div class="collapsible-body">
                                <form action="{{url('/propose/modify')}}" method="post">
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" id="new_name" name="account">
                                            <label for="new_name">New Name</label>
                                        </div>
                                    </div>
                                    @if($s->list_id == null)
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" id="new_budget" class="number" name="budget">
                                                <label for="new_budget">New Budget</label>
                                            </div>
                                        </div>
                                    @endif
                                    <button class="waves-effect waves-light btn green darken-3" type="submit"
                                            name="submit">Save Changes</button>
                                    <button class="waves-effect waves-light btn red darken-2" type="submit"
                                            name="submit">Delete</button>
                                    <input type="hidden" name="secondary_account" value="{{$s->name}}">
                                    <input type="hidden" name="primary_account" value="{{$account_1}}">
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                @elseif(isset($accounts))
                    <ul class="collapsible" data-collapsible="accordion">
                    @foreach($accounts as $s)
                        <li>
                            <div class="collapsible-header">
                                @php($link = '/propose/add/'.$s->name)
                                <p>
                                    <a href="{{ url($link) }}" style="font-weight: bold">
                                        {{$s->name}}</a><br>
                                    <b>Budget: </b>P{{number_format($s->amount, 2)}}<br>
                                    <b>Code: </b>{{ $s->code }}
                                </p>
                            </div>
                            <div class="collapsible-body">
                                <form action="{{url('/propose/modify')}}" method="post">
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="new_name" type="text" name="account" required>
                                            <label for="new_name">New Account Name</label>
                                        </div>
                                    </div>
                                    @if($s->list_id == null)
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" class="number" id="budget" name="budget">
                                                <label for="budget">New Budget</label>
                                            </div>
                                        </div>

                                    @endif
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" class="number" id="new_oracle" name="code">
                                            <label for="new_oracle">New Oracle Code</label>
                                        </div>
                                    </div>

                                    <button class="waves-effect waves-light btn green darken-3" type="submit"
                                            name="submit">Save Changes</button>
                                    <button class="waves-effect waves-light btn red darken-2" type="submit"
                                            name="submit">Delete</button>
                                    <input type="hidden" name="primary_account" value="{{$s->name}}">
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                    {{ "Total Budget: P". number_format($total_budget, 2) }}
                @else
                    <p class="center">There are no accounts yet</p>
                @endif
            </div>
            <br>
            <a class="waves-effect waves-light btn green darken-3" href="{{url('/propose/print')}}">
                <i class="material-icons left">print</i>Budget Proposal Preview</a>
            <br>
            <br>
            @if(isset($pa) && $pa)
                <div>
                    <form action="{{url('/propose/save')}}">
                        <p>
                            <input id="check1" type="checkbox" name="approved_vp" value="approved">
                            <label for="check1">Approved By Executive</label>
                        </p>
                        <p>
                            <input id="check2" type="checkbox" name="approved_ac" value="approved">
                            <label for="check2">Approved By Accounting</label>
                        </p>

                        <button class="waves-effect waves-light btn green darken-3" type="submit">Save Budget</button>
                    </form>
                </div>
            @endif
        </div>
        </div>

@endsection

<script>
@section('script')
$(".number").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl/cmd+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: Ctrl/cmd+C
        (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: Ctrl/cmd+X
        (e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

$('.collapsible').collapsible();
@endsection
</script>