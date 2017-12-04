@extends('layouts.general_layout')

@section('title', 'Add Bookstore Requisition Form')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li class="active"><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2">
        <h1> Add Bookstore Requisition Form</h1>
        <div class="row">
            <form action="{{ route('saveBRF') }}" method="POST" id="form" class="col s12">
                {{ csrf_field() }}
                <div class="row">
                    <div class="input-field col s12">
                        <select name="account" id="select_account">
                            @if($primary->isEmpty() == false)
                                <optgroup label="Primary">
                                    @foreach($primary as $p)
                                        <option value="p-{{ $p->id }}">{{ $p->pa_name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($secondary->isEmpty() == false)
                                <optgroup label="Secondary">
                                    @foreach($secondary as $s)
                                        <option value="s-{{ $s->id }}">{{ $s->sa_name . ' for ' . $s->pa_name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($tertiary->isEmpty() == false)
                                <optgroup label="Tertiary">
                                    @foreach($tertiary as $t)
                                        <option value="t-{{ $t->id }}">{{ $t->ta_name . ' for ' . $t->sa_name . ' for ' .
                            $t->pa_name}}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <label for="select_account">Accounts</label>
                    </div>
                </div>
                <div id="input_area">
                    <div class="row" id="first-input">
                        <div class="input-field col s6">
                            <input id="quantity" type="text" name="qty[]" class="number" required>
                            <label for="quantity">Quantity</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="description" type="text" name="desc[]" required>
                            <label for="description">Description</label>
                        </div>
                    </div>
                </div>

                <button class="waves-effect waves-light btn green darken-3" id="add_field" type="button">
                    <i class="material-icons left">add</i>Add Field</button>
                <button class="waves-effect waves-light btn red darken-2" id="remove_field" type="button">
                    <i class="material-icons left">close</i>Remove Field</button>
                <button class="waves-effect waves-light btn green darken-3 right" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endsection

<script>
    @section('script')
        $("#add_field").click(function () {
            $("#input_area").append('<div class="row" id="succeeding_fields">' +
                '<div class="input-field col s6">' +
                    '<input id="quantity" type="text" name="qty[]" class="number" required>' +
                    '<label for="quantity">Quantity</label>' +
                '</div>' +
                '<div class="input-field col s6">' +
                    '<input id="description" type="text" name="desc[]" required>' +
                    '<label for="description">Description</label>' +
                    '</div>' +
                '</div>');
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
        });

        $('#remove_field').click(function () {
            $("#succeeding_fields").remove();
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
    @endsection
</script>
