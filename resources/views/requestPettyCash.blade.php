<html>
    <head>
        <title> Request Petty Cash </title>
        <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        <form action='{{ route("recordRequestPCV") }}' method="POST">
            {{ csrf_field() }}
            <label>Purpose: </label>
            <input type="text" name="purpose"><br>
            <label>Amount: </label>
            <input type="number" name="amount"><br>
            <select name="account" id="account">
                <!-- TODO REVISE FOR BETTER UX and Create Fields when Account is Transportation and Meeting Expense -->
                @foreach($accounts as $a)
                    <option value="p-{{ $a->primary_accounts->id }}">
                        {{ $a->primary_accounts->name }}
                    </option>

                    @foreach($a->list_of_secondary_accounts as $s)
                        <option value="s-{{ $s->secondary_accounts->id }}">
                            {{ $a->primary_accounts->name }}:
                            {{ $s->secondary_accounts->name }}
                        </option>

                        @foreach($s->list_of_tertiary_accounts as $t)
                            <option value="t-{{ $t->tertiary_accounts->id }}">
                                {{ $a->primary_accounts->name }}:
                                {{ $s->secondary_accounts->name }}:
                                {{ $t->tertiary_accounts->name }}
                            </option>
                        @endforeach

                    @endforeach

                @endforeach
            </select><br>
            <input type="submit" name="submit" id="submit"><br>
        </form>
    </body>
</html>
<script>
</script>
