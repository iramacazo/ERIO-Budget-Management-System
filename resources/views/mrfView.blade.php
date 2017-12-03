<html>
    <head>
        <title> MRF </title>
    </head>
    <body>
        <h1> Material Requisition Forms </h1><br>
        <a href="{{ route('addMRFView') }}"> Add MRF </a><br>
        <h2> Pending MRFs for Approval </h2><br>
        @if($pending != null)
            @foreach($pending as $p)
                Form No: {{ $p->form_num }}<br>
                Date: {{ $p->created_at }}<br>
                <form action="" method="POST">
                    <input type="submit" value="PRINT">
                </form>
                @foreach($p->entries as $entry)
                    Description: {{ $entry->description }}<br>
                    Quantity: {{ $entry->quantity }}<br>
                    Account No:
                    @if($entry->list_sa_id != null)
                        {{ $entry->list_sa_id }}
                    @elseif($entry->list_ta_id != null)
                        {{ $entry->list_ta_id }}
                    @endif
                @endforeach
            @endforeach
        @else
            There are currently no Pending MRFs for Approval
        @endif
        <h2> Pending MRFs for Procurement </h2><br>
        @if($procure != null)
            @foreach($procure as $p)
                Form No: {{ $p->form_num }}<br>
                Date: {{ $p->created_at }}<br>
                Account Name: {{ $p->list_PA->primary_accounts->name }}
                <form action="{{ route('printMRF') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $p->id }}" name="id">
                    <input type="submit" value="PRINT">
                </form>
                <form action="{{ route('receiveAmountsMRF') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $p->id }}" name="id">
                    <input type="submit" value="Input Amounts"> {{-- Need better naming convention --}}
                </form>
                @foreach($p->entries as $entry)
                    Description: {{ $entry->description }}<br>
                    Quantity: {{ $entry->quantity }}<br>
                    Account No:
                    @if($entry->list_sa_id != null)
                        {{ $entry->list_SA->secondary_accounts->name }} <br>
                    @elseif($entry->list_ta_id != null)
                        {{ $entry->list_TA->tertiary_accounts->name }} for
                        {{ $entry->list_TA->tertiary_accounts->secondary_accounts->name }} <br>
                    @endif
                @endforeach
                <br>
            @endforeach
        @else
            There are currently no Pending MRFs for Approval
        @endif
        <h2> Completed MRFs </h2><br>
    </body>
</html>