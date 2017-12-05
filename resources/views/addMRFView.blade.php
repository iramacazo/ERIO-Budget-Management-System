@extends('layouts.general_layout')

@section('title', 'Add Material Requisition Form')

@section('stylesheet')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li class="active"><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s6 offset-s3 white z-depth-2" style="padding: 25px">
        <h4> Add MRF </h4>
        <form action="{{ route('saveMRF') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s6">
                    <input type="text" name="form_num" id="form_num" required>
                    <label for="form_num">Form No.</label>
                </div>
                <div class="input-field col s6">
                    <input id="date_needed" type="text" name="date_needed" class="datepicker" required>
                    <label for="date_needed">Date Needed</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="contact" name="contact_person" type="text" required>
                    <label for="contact">Contact Person</label>
                </div>
                <div class="input-field col s6">
                    <input id="contact_email" name="contact_person_email" type="email" required>
                    <label for="contact_email">Contact Person's Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="delivery" name="place_of_delivery" type="text" required>
                    <label for="delivery">Place of Delivery</label>
                </div>
                <div class="input-field col s6">
                    <select name="primary_account" id="pa">
                        @if($primary != null)
                            @foreach($primary as $p)
                                <option value="p-{{ $p->id }}">{{ $p->pa_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <label for="pa">Primary Account</label>
                </div>
            </div>

            <h4>Entries</h4><br>
            <div class="entries">
                <div class="row">
                    <div class="input-field col s4">
                        <input type="text" id="description" name="desc[]">
                        <label for="description">Description</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="text" id="quantity" class="number" name="qty[]">
                        <label for="quantity">Quantity</label>
                    </div>
                    <div class="input-field col s4">
                        <select name="acc[]" class="accounts" id="accounts">
                            @if($secondary->isEmpty() == false)
                                @foreach($secondary as $s)
                                    <option value="s-{{ $s->id }}">{{ $s->sa_name
                                        . " for " . $s->pa_name}}</option>
                                @endforeach
                            @endif

                            @if($tertiary->isEmpty() == false)
                                @foreach($tertiary as $t)
                                    <option value="t-{{ $t->id }}">{{ $t->ta_name
                                        . " for " . $t->sa_name . " for " . $t->pa_name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="accounts">Accounts</label>
                    </div>
                </div>
            </div>


            <button id="chook" type="button" class="waves-effect waves-light btn green darken-3">
                <i class="material-icons left">add</i> Add Entry </button>
            <button class="waves-effect waves-light btn red darken-2" id="remove_field" type="button">
                <i class="material-icons left">close</i>Remove Entry</button>
            <button class="waves-effect waves-light btn green darken-3 right" type="submit">Submit</button>
        </form>
    </div>
@endsection

<script>
    @section('script')
    $('select').material_select();

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

    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        format: 'dd/mm/yyyy',
        min: 'Today',
        closeOnSelect: true // Close upon selecting a date,
    });

    $("#chook").click(function () {
        var options = $('select.accounts').html();
        console.log(options);
        $('.entries').append('<div class="row" id="succeeding_fields">' +
            '                    <div class="input-field col s4">' +
            '                        <input type="text" id="description" name="desc[]">' +
            '                        <label for="description">Description</label>' +
            '                    </div>' +
            '                    <div class="input-field col s4">' +
            '                        <input type="text" id="quantity" class="number" name="qty[]">' +
            '                        <label for="quantity">Quantity</label>' +
            '                    </div>' +
            '                    <div class="input-field col s4">' +
            '                        <select name="acc[]" class="accounts" id="accounts">' +
                                        options +
            '                        </select>' +
            '                        <label for="accounts">Accounts</label>' +
            '                    </div>' +
            '                </div>');
        $('select').material_select();
    });

    $('#remove_field').click(function () {
        $("#succeeding_fields").remove();
    });

    $('#pa').on('change', function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var accounts = $('select.accounts');
        accounts.empty();
        $.ajax({
            type: "POST",
            url: "/mrf/add/entry",
            data: {
                _token: CSRF_TOKEN,
                pa_id: this.value
            },
            dataType: 'JSON',
            success: function(data){
                data.secondary.forEach(function(sec){
                    accounts.append($("<option></option>")
                            .attr('value', "s-" + sec['id'])
                            .text(sec["sa_name"] + ' for ' + sec["pa_name"]));
                });

                data.tertiary.forEach(function(ter){
                    accounts.append($("<option></option>")
                            .attr('value', "t-" + ter["id"])
                            .text(ter["ta_name"] + ' for ' + ter["sa_name"] + ' for ' + ter["pa_name"]));
                });
                accounts.material_select();
            }
        });
    });
    @endsection
</script>