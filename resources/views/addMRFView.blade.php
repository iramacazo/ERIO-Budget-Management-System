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
                            @if($secondary != null)
                                @foreach($secondary as $s)
                                    <option value="s-{{ $s->id }}">{{ $s->sa_name
                                        . " for " . $s->pa_name}}</option>
                                @endforeach
                            @endif

                            @if($tertiary != null)
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


            <button id="chook"> Add Entry </button><p id="remove">Remove</p>
            <input type="submit" name="Request">
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
        var options = $('.accounts').html();
        $("#remove").append('<br><input type="text" name="desc[]" placeholder="Description">' +
            '<input type="number" name="qty[]" placeholder="Quantity"><br>' +
            '<select name="acc[]" class="accounts">' +
            options +
            '</select>' +
            '<p id="remove"> Remove </p>')
    });

    $('#pa').on('change', function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "/mrf/add/entry",
            data: {
                _token: CSRF_TOKEN,
                pa_id: this.value
            },
            dataType: 'JSON',
            success: function(data){
                var options = "";

                data.secondary.forEach(function(sec){
                    options += "<option value='s-" + sec["id"] +"'>" +
                        sec["sa_name"] + " for " + sec["pa_name"]
                    "</option>";
                });

                data.tertiary.forEach(function(ter){
                    options += "<option value='t-" + ter["id"] + "'>" +
                        ter["ta_name"] + " for " + ter["sa_name"] + " for " + ter["pa_name"]
                    "</option>";
                });

                $(".accounts").html(options);
            }
        });
        $('select').material_select();
    });
    @endsection
</script>