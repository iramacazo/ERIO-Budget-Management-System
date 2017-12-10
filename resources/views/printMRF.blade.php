@extends('layouts.general_layout')

@section('title', 'Print Material Requisition Form')

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/printMRF.css')}}">
@endsection

@section('sidebar')
    @parent
    <li><a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a></li>
    <li><a href="{{ route('brfView') }}">Bookstore Requisition Form</a></li>
    <li class="active"><a href="{{ route('viewMRF') }}"> Material Requisition Form </a></li>
    <li><a href="{{ route('pettyCashView') }}">Petty Cash</a></li>
@endsection

@section('content')
    <div class="col s10 offset-s1 white z-depth-2" style="padding: 25px">
        <div class="print_stuff">
            @if($mrf->status != 'Procure')
                <table class="bordered">
                    <tr>
                        <td rowspan="2" colspan="6" style="border-right: none">
                            <p>
                                <b>PROCUREMENT OFFICE</b><br>
                                <small>FORM 01320-011 Revised 06-25-08</small><br>
                                <small>Tel. No. 524-4611 loc. 630</small>
                                <small>Fax. No. 465-8929</small>
                            </p>
                        </td>
                        <td rowspan="2" colspan="12" style="border-left: none">
                            <div>
                                <img src="{{asset('images/dlsu_logo.png')}}" height="75px"
                                     style="filter: saturate(0%) brightness(70%) contrast(1000%);
                                display: inline">
                                <p style="display: inline-block" class="center">
                                    De La Salle Univerisity-Manila<br>
                                    <small>2401 Taft Avenue, Manila, Philippines 0922</small><br>
                                    <b>MATERIALS REQUISITION FORM</b></p>
                            </div>
                        </td>
                        <td colspan="2" style="padding-top: 0; padding-bottom: 15px">
                            <p><small><b>No.</b></small><br>
                                {{$mrf->form_num}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 0; padding-bottom: 15px"><small><b>Date:</b></small><br>
                            {{\Carbon\Carbon::now()->toFormattedDateString()}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="padding-top: 0;"><small>
                                <b>COLLEGE OR COLLEGE LEVEL UNIT:</b></small><br>
                            <p style="visibility: hidden">Space</p></td>
                        <td colspan="4" style="padding-top: 0;"><small>
                                <b>DEPT. OR DEPT. LEVEL UNIT:</b></small><br>
                            <p>{{$mrf->dept}}</p></td>
                        <td colspan="5" style="padding-top: 0;"><small>
                                <b>BUDGET ITEM/ACCOUNT ALLOCATION:</b></small><br>
                            <p>01 - 01220 - {{$mrf->list_PA->primary_accounts->code}}</p></td>
                        <td colspan="4" style="padding-top: 0;"><small>
                                <b>DATE NEEDED:</b></small><br>
                            <p>{{\Carbon\Carbon::parse($mrf->date_needed)->toFormattedDateString()}}</p></td>
                        <td colspan="2" style="padding-top: 0;"><small>
                                <b>PLACE OF DELIVERY:</b></small><br>
                            <p>{{$mrf->place_of_delivery}}</p></td>
                    </tr>
                    <tr>
                        <td class="center" colspan="10"><p><b>TO BE FILLED OUT BY REQUISITIONER</b></p></td>
                        <td class="center" colspan="10"><p><b>TO BE FILLED OUT BY THE PROCUREMENT & ACCOUNTING OFFICES
                                    ONLY</b></p></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Item No.</small></td>
                        <td colspan="8" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Description</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Qty Required</small></td>
                        <td colspan="5" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Supplier</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Unit Price</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Amount</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Budget Certification</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>PRS No.</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>P.O. No.</small></td>
                    </tr>
                    @foreach($mrf->entries as $entry)
                        <tr>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small>{{$loop->iteration}}</small></td>
                            <td colspan="8" style="padding-top: 5px; padding-bottom: 5px"><small>{{$entry->description}}</small></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small>{{$entry->quantity}}</small></td>
                            <td colspan="5" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small>{{$entry->supplies}}</small></td>
                            <td colspan="1" class="right-align" style="padding-top: 5px; padding-bottom: 5px">
                                <small>P{{ number_format($entry->unit_price, 2) }}</small></td>
                            <td colspan="1" class="right-align" style="padding-top: 5px; padding-bottom: 5px">
                                <small>P{{ number_format($entry->unit_price * $entry->quantity, 2) }}</small></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" style="padding-top: 0;"><small>
                                <b>PURPOSE/REASON FOR URGENCY:</b></small><br>
                            <b><small>(Continue on back page if necessary)</small></b><br>
                            <p style="visibility: hidden">Space</p></td>
                        <td colspan="14" style="padding-top: 0;"><small>
                                <b>JUSTIFICATION IF CHOICE IS NOT THE LOWEST BIDDER:</b></small><br>
                            <b><small>(Continue on back page if necessary)</small></b><br>
                            <p style="visibility: hidden">Space</p></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding-top: 0;"><small>
                                <b>REQUESTED BY:</b></small><br>
                            <p>{{\App\User::where('id', $mrf->requested_by)->first()->name}}</p></td>
                        <td colspan="7" style="padding-top: 0;"><small>
                                <b>MRF RECIEVED/DATE:</b></small><br>
                            <p style="visibility: hidden">Space</p></td>
                        <td colspan="7" style="padding-top: 0;" class="center"><small>
                                POST CANVASS APPROVAL</small>
                            <small>(To be signed only when supplier and price are already indicated)</small><br>
                            <p style="visibility: hidden">Space</p></td>
                    </tr>
                </table>
            @else
                <table class="borderd">
                    <tr style="border-bottom: none;">
                        <td colspan="17" style="border-right: none; border-bottom: none">
                            <div>
                                <img src="{{asset('images/dlsu_logo.png')}}" height="75px"
                                     style="filter: saturate(0%) brightness(70%) contrast(1000%);
                                display: inline">
                                <p style="display: inline-block">
                                    De La Salle Univerisity<br>
                                    2401 Taft Avenue, Manila, Philippines 0922<br>
                                     </p>
                            </div>
                        </td>
                        <td colspan="3" style="border-left: none; border-bottom: none">
                            <p>
                                RPSM No.:<br>
                                RPSM Date: {{\Carbon\Carbon::parse($mrf->created_at)->toFormattedDateString()}}<br>
                                PROC RECEIVED DATE:
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="center" colspan="20" style="border-top: none">
                            <b>REQUEST FOR PROCUREMENT OF SERVICES AND MATERIALS</b></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Contact Information</b></td>
                        <td colspan="6" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Delivery Information</b></td>
                        <td colspan="7" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Funding Source</b></td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Department</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">{{$mrf->dept}}</td>
                        <td colspan="3" rowspan="2" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Building</b></td>
                        <td colspan="3" rowspan="2" style="padding-top: 2px; padding-bottom: 2px">
                            <b>{{$mrf->place_of_delivery}}</b></td>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Account No.</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">
                            01 - 01220 - {{$mrf->list_PA->primary_accounts->code}}</td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Name of Unit Head/Fund Owner</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">                               </td>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Account Name</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">
                            {{$mrf->list_PA->primary_accounts->name}}</td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Contact Person</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">{{\App\User::where('id', $mrf->requested_by)
                        ->first()->name}}</td>
                        <td colspan="3" rowspan="2" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Floor/Room No.</b></td>
                        <td colspan="3" rowspan="2" style="padding-top: 2px; padding-bottom: 2px">
                            <b>                         </b></td>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Charged to</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">AY 
                            {{\Carbon\Carbon::parse(\App\Budget::where('id', $mrf->list_PA->primary_accounts->
                            list_of_primary_accounts->first()->budget_id)->first()->start_range)->year}} to
                            {{\Carbon\Carbon::parse(\App\Budget::where('id', $mrf->list_PA->primary_accounts->
                            list_of_primary_accounts->first()->budget_id)->first()->end_range)->year}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Email</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px">{{\App\User::where('id', $mrf->requested_by)
                        ->first()->email}}</td>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><p><b>Budget Encumbrance Amount<br>
                                    (For Accounting Use Only)</b></p></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"> </td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Local</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"> </td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Contact Person</b></td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px">
                            {{$mrf->contact_person}}</td>
                        <td colspan="4" rowspan="2"
                            style="padding-top: 2px; padding-bottom: 2px"><p><b>Budget Certified by:</b></p></td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px"> </td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding-top: 2px; padding-bottom: 2px"><b>Signature of Unit Head/Fund Owner</b></td>
                        <td colspan="3" class="center"
                            style="padding-top: 2px; padding-bottom: 2px"> </td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Date Needed</b></td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px">
                            {{\Carbon\Carbon::parse($mrf->date_needed)->toFormattedDateString()}}</td>
                        <td colspan="3" style="padding-top: 2px; padding-bottom: 2px" class="center">
                            <b>Signature Over Printed Name     Date</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20"></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td colspan="10" class="center"
                            style="padding-top: 0; padding-bottom: 0"><b>TO BE FILLED OUT BY THE REQUISITIONER</b></td>
                        <td colspan="10" class="center"
                            style="padding-top: 0; padding-bottom: 0"><b>TO BE FILLED OUT BY THE
                                PROCUREMENT OFFICE ONLY</b></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Item No.</small></td>
                        <td colspan="8" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Description</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Qty Required</small></td>
                        <td colspan="5" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Supplier</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Unit Price</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Amount</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>Budget Certification</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>PRS No.</small></td>
                        <td colspan="1" class="center" style="padding-top: 7px; padding-bottom: 7px">
                            <small>P.O. No.</small></td>
                    </tr>
                    @foreach($mrf->entries as $entry)
                        <tr>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small>{{$loop->iteration}}</small></td>
                            <td colspan="8" style="padding-top: 5px; padding-bottom: 5px"><small>{{$entry->description}}</small></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small>{{$entry->quantity}}</small></td>
                            <td colspan="5" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <small> </small></td>
                            <td colspan="1" class="right-align" style="padding-top: 5px; padding-bottom: 5px">
                                <small> </small></td>
                            <td colspan="1" class="right-align" style="padding-top: 5px; padding-bottom: 5px">
                                <small> </small></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                            <td colspan="1" class="center" style="padding-top: 5px; padding-bottom: 5px">
                                <p style="visibility: hidden">Space</p></td>
                        </tr>
                    @endforeach
                </table>
                <table>
                    <tr>
                        <td colspan="20" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                RPSM APPROVAL BASED ON THE SIGNATORY REQUIREMENTS OF PRS</b></td>
                    </tr>
                    <tr>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                APPROVAL for P10,000 & below (Chair/Director/Associate Principal)</b></td>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                APPROVAL for P500,001.00 to P1,000,000.00 (Chancellor)</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2" style="border-right: none"> </td>
                        <td colspan="6" style="border-right: none; border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-right: none"> </td>
                        <td colspan="6" style="border-right: none; border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-left: none"> </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="center" style="border-left: none; border-right: none">
                            <b>Signature Over Printed Name          Date:</b></td>
                        <td colspan="6" class="center" style="border-left: none; border-right: none">
                            <b>Signature Over Printed Name          Date:</b></td>
                    </tr>
                    <tr>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                APPROVAL for P10,001.00 to P100,000.00 (AVC/AVP/Dean/Principal)</b></td>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                APPROVAL for PP1,000,001 to Infinity (President)</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2" style="border-right: none"> </td>
                        <td colspan="6" style="border-right: none; border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-right: none"> </td>
                        <td colspan="6" style="border-right: none; border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-left: none"> </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="center" style="border-left: none; border-right: none">
                            <b>Signature Over Printed Name          Date:</b></td>
                        <td colspan="6" class="center" style="border-left: none; border-right: none">
                            <b>Signature Over Printed Name          Date:</b></td>
                    </tr>
                    <tr>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                APPROVAL for P100,001.00 to P500,000.00 (Vice President/Vice Chancellor)</b></td>
                        <td colspan="10" class="center" style="padding-top: 0; padding-bottom: 0"><b>
                                REMARKS</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2" style="border-right: none"> </td>
                        <td colspan="6" style="border-right: none; border-left: none"> </td>
                        <td colspan="2" rowspan="2" style="border-left: none"> </td>
                        <td colspan="10" rowspan="2"> </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="center" style="border-left: none; border-right: none">
                            <b>Signature Over Printed Name          Date:</b></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td colspan="20" class="center" style="padding-top: 0; padding-bottom: 0">
                            <b>TO BE FILLED OUT BY THE PROCUREMENT OFFICE ONLY</b></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>CANVASSER / DATE ASSIGNED</b>
                        </td>
                        <td colspan="6" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>CANVASSER REPORT REVIEWED BY</b>
                        </td>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>ENDORSED FOR PRS PREPARATION BY</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b> </b>
                        </td>
                        <td colspan="6" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b> </b>
                        </td>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b> </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Signature Over Printed Name       Date:</b>
                        </td>
                        <td colspan="6" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Signature Over Printed Name       Date:</b>
                        </td>
                        <td colspan="7" class="center" style="padding-top: 2px; padding-bottom: 2px">
                            <b>Signature Over Printed Name       Date:</b>
                        </td>
                    </tr>
                </table>
            @endif
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