@extends('layouts.general_layout')

@section('title', 'Request Access to Account')

@section('sidebar')
    @parent
    <li class="active"><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>Request Access to Account</h3>
        <form action="{{ route('requestAccessSave') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s12">
                    <select id="account" name="account">
                        <option value="" disabled selected>Choose your option</option>
                        @if($primary != null)
                            @foreach($primary as $p)
                                <option value="p-{{ $p->id }}">{{ $p->primary_name }}</option>
                            @endforeach
                        @endif

                        @if($secondary != null)
                            @foreach($secondary as $s)
                                <option value="s-{{ $s->id }}">{{ $s->secondary_name . " for " .
                                $s->primary_name}}</option>
                            @endforeach
                        @endif

                        @if($tertiary != null)
                            @foreach($tertiary as $t)
                                <option value="t-{{ $t->id }}">{{ $t->tertiary_name . ' for ' . $t->secondary_name .
                                ' for ' .  $t->primary_name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <label for="account">Account</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="textarea" type="text" name="explanation"><br>
                    <label for="textarea">Reason </label>
                </div>
            </div>

            <button type="submit" class="waves-effect waves-light btn green darken-3 right">Send</button>
        </form>
    </div>
@endsection

<script>
    @section('script')
        $('select').material_select();
    @endsection
</script>