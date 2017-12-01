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
        <h2> Completed MRFs </h2><br>
    </body>
</html>