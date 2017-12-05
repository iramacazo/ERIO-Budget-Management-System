<html>
    <head>
        <title> Transactions Today </title>
    </head>
    <body>
        <h6> Bookstore Requisition Forms </h6>
        @foreach($brf as $b)
            User: {{ $b->user->name }}<br>
            Date Time: {{ $b->updated_at }}<br>
        @endforeach
        @empty($brf)
            No BRF Transactions today
        @endempty
        <h6> Material Requisition Forms </h6>
        @foreach($mrf as $m)
            Form No: {{ $m->form_num }}<br>
            User: {{ $m->requester->name }}<br>
            Date Time: {{ $m->updated_at }}<br>
            Status: {{ $m->status }}<br><br>
        @endforeach
        @empty($mrf)
            No MRF Transactions today
        @endempty
        <h6> Petty Cash Transactions </h6>
        @foreach($pcv as $p)
            Requested By: {{ $p->requester->name }}<br>
            Purpose: {{ $p->purpose }}<br>
            Date Time: {{ $p->updated_at }}<br><br>
        @endforeach
        @empty($pcv)
            No Petty Cash Transactions today
        @endempty
        <h6> Other Transactions </h6>
        @foreach($transac as $t)
            User: {{ $t->user->name }}
            Description: {{ $t->description }}
            Date Time: {{ $t->updated_at }}
        @endforeach
        @empty($transac)
            No Other Transactions today
        @endempty
    </body>
</html>