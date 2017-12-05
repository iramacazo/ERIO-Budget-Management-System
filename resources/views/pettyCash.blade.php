@extends('layouts.general_layout')

@section('title', 'Petty Cash')

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li class="active"><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <h3>Petty Cash</h3>
        <h4>Pending for Approval</h4><br>
        @if($pending->isEmpty() == false)
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Purpose                              </th>
                        <th class="center">Amount</th>
                        <th>Account                                        </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pending as $p)
                    <tr>
                        <td>{{ $p->purpose }}</td>
                        <td class="right-align">P{{ number_format($p->amount, 2) }}</td>
                        <td>
                            @if($p->list_pa_id != null)
                                {{ $p->primary_account->primary_accounts->name }}
                            @elseif($p->list_sa_id != null)
                                {{ $p->secondary_account->secondary_accounts->name }} to
                                {{ $p->secondary_account->secondary_accounts->primary_accounts->name }}
                                (Secondary)
                            @else
                                {{ $p->tertiary_account->tertiary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->name }} to
                                {{ $p->tertiary_account->tertiary_accounts->secondary_accounts->primary_accounts->name }}
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('cancelPettyCash') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $p->id }}">
                                <button type="submit" class="waves-effect waves-light btn green darken-3 right">
                                    Cancel Request</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
           <p class="center">There are currently NO PETTY CASH VOUCHERS Pending for Approval</p>
        @endif
        <br>
        <a href="{{ route('request_petty_cash') }}" class="waves-effect waves-light btn green darken-3 right">
            Request Petty Cash</a>
    </div>
@endsection
