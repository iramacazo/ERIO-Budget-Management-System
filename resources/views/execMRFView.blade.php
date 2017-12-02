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
            @endforeach
        @else
            There are currently no Pending MRFs for Approval
        @endif
    </body>
</html>