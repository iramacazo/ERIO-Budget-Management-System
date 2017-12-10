@extends('layouts.general_layout')

@section('title', 'Budget')

@section('sidebar')
    @parent
    <li class="active"><a href="{{route('budget_dash')}}">Budget</a></li>
    <li><a href="{{ route('primaryLedger') }}">Ledger Accounts </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
    <li><a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a></li>
    <li><a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a></li>
    <li><a href="{{ route('disbursementJournal') }}"> Disbursement Journal </a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <div class="section">
            @if($current_budget == null)
                <h4 class="center">There is no active budget</h4>
            @else
                <h4> Budget for A.Y. {{\Carbon\Carbon::parse($current_budget->start_range)->year . " - " .
                            \Carbon\Carbon::parse($current_budget->end_range)->year}}</h4>
                <div class="section">
                    <h5>Accounts</h5>
                    <div class="divider"></div>
                    <div class="row" style="padding-top: 10px">
                        <div class="col s4">
                            <h5>Primary</h5>
                            <ul class="collection">
                                @foreach($primary_accounts as $pa)
                                    <li class="collection-item primary-account" value="{{$pa->id}}">
                                        <p>
                                            <b>{{$pa->primary_accounts->name}}</b><br>
                                            P{{number_format($pa->amount, 2)}}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col s4" >
                            <h5>Secondary</h5>
                            <p class="center" id="sec_label">Select a primary account to view secondary accounts</p>
                            @foreach($primary_accounts as $pa)
                                <ul class="collection secondary-list" id="sa{{$pa->id}}" hidden>
                                    @foreach($secondary_accounts as $sa)
                                        @if($sa->list_id == $pa->id)
                                        <li class="collection-item secondary-account" value="{{$sa->id}}">
                                            <p>
                                                <b>{{$sa->secondary_accounts->name}}</b><br>
                                                P{{number_format($sa->amount, 2)}}
                                            </p>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                        <div class="col s4">
                            <h5>Tertiary</h5>
                            <p class="center" id="tert_label">Select a secondary account to view tertiary accounts</p>
                            @foreach($secondary_accounts as $sa)
                                <ul class="collection tertiary_list" id="ta{{$sa->id}}" hidden>
                                    @foreach($tertiary_accounts as $ta)
                                        @if($ta->list_id == $sa->id)
                                            <li class="collection-item tertiary-account" value="{{$ta->id}}">
                                                <p>
                                                    <b>{{$ta->tertiary_accounts->name}}</b><br>
                                                    P{{number_format($ta->amount, 2)}}
                                                </p>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="waves-effect waves-light btn green darken-3 right modal-trigger"
                        data-target="add_account_modal">Add Accounts</button>
                <div id="add_account_modal" class="modal col s4 offset-s1">
                    <div class="modal-content">
                        <h4>Add Account</h4>
                        <form method="POST" action="{{route('add-account-to-current', $current_budget->id)}}">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="input-field col s8">
                                    <select id="parent_account" name="parent_account">
                                        <option value="parent">Set as primary account</option>
                                        @foreach($primary_accounts as $pa)
                                            <option value="primary {{$pa->id}}">{{$pa->primary_accounts->name}}
                                            </option>
                                            @foreach($secondary_accounts as $sa)
                                                @if($sa->list_id == $pa->id)
                                                    <option value="secondary {{$sa->id}}">{{$pa->primary_accounts->name
                                                    . ": " . $sa->secondary_accounts->name}}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <label for="parent_account">Parent Account</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s8">
                                    <input id="name" name="name" type="text" required>
                                    <label for="name">Name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s8">
                                    <input id="budget" name="budget" type="number" min="1" class="number" required>
                                    <label for="budget">Budget</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s8">
                                    <input id="code" name="code" type="text" class="number"
                                           placeholder="Required on Primary Accounts" required>
                                    <label for="code">Oracle Code</label>
                                </div>
                            </div>
                            <button class="waves-effect waves-light btn green darken-3 right" type="submit">
                                Submit</button>
                            <br>
                        </form>

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

<script>
    @section('script')
        @if(session()->has('message'))
        Materialize.toast("{{session('message')}}", 4000);
        @endif

        $("#parent_account").on('change', function () {
            if(this.value === "parent"){
                $('#code').attr('required', true);
            }else{
                $('#code').removeAttr('required');
            }
        });

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

            $('select').material_select();

            $('.modal').modal();

            $('.primary-account').click(function(){
                var paindicator = this.value;
                $('.primary-account').removeClass('active');
                $('.secondary-account').removeClass('active');
                $('#sec_label').hide();
                $('.secondary-list').hide();
                $('.tertiary_list').hide();
                $('#tert_label').show();
                $(this).addClass('active');
                $('#sa' + paindicator).show();
            });

            $('.secondary-account').click(function () {
                var saindicator = this.value;
                $('.secondary-account').removeClass('active');
                $('#tert_label').hide();
                $('.tertiary_list').hide();
                $(this).addClass('active');
                $('#ta' + saindicator).show();
            });
    @endsection
</script>