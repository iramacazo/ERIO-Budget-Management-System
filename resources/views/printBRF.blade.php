@extends('layouts.general_layout')

@section('title', 'Print Material Requisition Form')

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
@endsection

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li class="active"><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
        <div class="print_stuff">
            <p class="center"><b>DE LA SALLE UNIVERSITY</b></p>
            <p class="center"><b>BOOKSTORE REQUISITION SLIP</b></p>
            <p class="center"><b>Date: </b>{{\Carbon\Carbon::parse($brf->created_at)->toFormattedDateString()}}</>
            <br><br>
            <table class="bordered">
                <thead>
                    <tr>
                        <th class="center" style="padding-bottom: 2px; padding-top: 2px" colspan="4">
                                 Quantity      </th>
                        <th class="center" style="padding-bottom: 2px; padding-top: 2px" colspan="12">
                                                                               
                            Description
                                                                                  </th>
                        <th class="center" style="padding-bottom: 2px; padding-top: 2px" colspan="2">
                             R </th>
                        <th class="center" style="padding-bottom: 2px; padding-top: 2px" colspan="4">
                                 Amount     </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brf->entries as $entry)
                        <tr>
                            <td class="center" colspan="4" style="padding-bottom: 4px; padding-top: 4px">
                                {{$entry->quantity}}</td>
                            <td colspan="12" style="padding-bottom: 4px; padding-top: 4px">
                                {{$entry->description}}</td>
                            <td class="center" colspan="2" style="padding-bottom: 4px; padding-top: 4px"> </td>
                            <td class="center" colspan="4" style="padding-bottom: 4px; padding-top: 4px">
                                {{ $entry->amount > 0 ? 'P' . number_format($entry->amount,2) : " "}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <table>
                <tr>
                    <td style="visibility: hidden"> </td>
                    <td style="visibility: hidden"> </td>
                    <td style="padding-top: 0"><small><b><i>Requested by:</i></b></small><br> </td>
                    <td style="padding-top: 0"><small><b><i>Approved by:</i></b></small><br> </td>
                    <td style="visibility: hidden"> </td>
                    <td style="visibility: hidden"> </td>
                </tr>
            </table>
        </div>

        <br>
        <button class="waves-effect waves-light btn green darken-3 right" id="print_button">
            <i class="material-icons left">print</i>Print</button>
    </div>
@endsection

<script>
    @section('script')
        $('#print_button').click(function () {
        window.print();
        });
    @endsection
</script>