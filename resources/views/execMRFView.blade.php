<html>
    <head>
        <title> MRF </title>
    </head>
    <body>
        <h1> Material Requisition Forms for Approval</h1>

        @if($pending != null)
            @foreach($pending as $p)
                Form No: {{ $p->form_num }}<br>
                Date Needed: {{ $p->date_needed }}<br>
                Date Requested: {{ $p->created_at }}<br>
                Account: {{ $p->list_PA->primary_accounts->name }}
                <form action="{{ route('approveMRF') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $p->id }}" name="id">
                    <input type="submit" value="Approve">
                </form>
                <form action="{{ route('printMRF') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $p->id }}" name="id">
                    <input type="submit" value="PRINT">
                </form>

                @foreach($p->entries as $entry)
                    Description: {{ $entry->description }}<br>
                    Quantity: {{ $entry->quantity }}<br>
                    Account No:
                    @if($entry->list_sa_id != null)
                        {{ $entry->list_SA->secondary_accounts->name }} <br><br>
                    @elseif($entry->list_ta_id != null)
                        {{ $entry->list_TA->tertiary_accounts->name }} for
                        {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }} <br><br>
                    @endif
                @endforeach
            @endforeach
        @else
            There are currently no Pending MRFs for Approval
        @endif
    </body>
</html>