<html>
    <head>
        <title> Request Petty Cash </title>
        <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        <form action="" method="POST">
            <label>Purpose: </label>
            <input type="text" name="purpose"><br>
            <label>Amount: </label>
            <input type="number" name="amount"><br>
            <select name="account" id="account">
                <!-- TODO check for secondary accounts then check for tertiary AJAX -->
                @foreach($accounts as $a)
                    <option value="{{ $a->primary_accounts->name }}" class="option">{{ $a->primary_accounts->name }}</option>
                @endforeach
            </select><br>
            <input type="submit" name="submit" id="submit"><br>
        </form>
    </body>
</html>
<script>
    $(document).ready(function(){
        $('.option').click(function(){
            var name = $('.option').val();

            //TODO Magaral ng AJAX :D
            /**$.post("{{ route('getSubAccounts') }}", name, function(data, status){
                if(data != null){
                    var selectStart = "<select name='subaccount' id='subaccount'>";
                    var selectEnd = "</select><br>";
                    for()
                    $('#submit').prepend();
                }
            });
            */
            //TODO append input fields (Num of participants and Duration) for Meeting Expenses
            //TODO append input fields (Distance and Destination) for Transportation

            /**if($('.option').text() == "Transportation"){
                $('#account').append(
                    '<label>Destination: </label><input type="text" name="destination"><br>' +
                    '<label>Distance: </label><input type="number" name="distance"><br>'
                );
            }*/
        });
    });
</script>
