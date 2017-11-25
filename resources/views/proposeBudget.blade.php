<html>
<head>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(function(){
            var acc_num = 1;
            var form = document.forms['accounts_form'];
            var div = document.getElementById("accounts_list_added");
            var primary_acc_count = 0;
            var total_budget = 0;

            $("#add").click(function(){
                $("#budget_error").text('');
                if($("#acc_budget").val() == '' || $("#acc_budget").val() == 0){
                    $("#budget_error").text("Budget cannot be empty");
                    return false;
                }
                if($("#acc_budget").val() <= 0){
                    $("#budget_error").text("Budget cannot be negative");
                    return false;
                }
                if(!$.isNumeric($("#acc_budget").val())){
                    $("#budget_error").text("Bawal characters");
                    return false;
                }
                var account = $("#account_list option:selected").text();
                primary_acc_count++
                $("#counter").val(primary_acc_count);
                addHidden(form, acc_num, account);
                addAccList(account);
                acc_num++;
                $("#acc_budget").val("");
                $("#account_list option:selected").remove();
                return false;
            });
            $("#accounts_list_added").on('click', '#rem_btn', function(){
                console.log("removed and added to options");
                $div = $(this).parent('div');
                text = $div[0].childNodes[0].nodeValue;
                budget = $(this).parent().find('#budget').val();
                console.log(budget);
                $("#account_list").append($('<option>', {
                    value: text,
                    text: text
                }));

                id = $(this).parent().attr("id");

                $(this).closest('div').remove();
                $("input[name='account_num_"+ id +"']").remove();
                $("input[name='budget_num_"+ id +"']").remove();
                //primary_acc_count--;
                //$("#counter").val(primary_acc_count);
                total_budget = total_budget-parseInt(budget);
                $("#tb").text(total_budget);
            });
            //
            function addHidden(form, key, value){
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'account_num_'+key;
                input.value = value;
                form.appendChild(input);
                var bInput = document.createElement('input');
                bInput.type = 'hidden';
                bInput.name = 'budget_num_'+key;
                bInput.value = $("#acc_budget").val();
                form.appendChild(bInput);
            }
            function addAccList(name){
                console.log("added to accounts");
                var ndiv = document.createElement('div');
                ndiv.id = acc_num;
                var t = document.createTextNode(name);
                var addbtn = document.createElement('button');
                addbtn.id = 'add_btn';
                var rembtn = document.createElement('button');
                rembtn.id = 'rem_btn';
                //
                var t2 = document.createTextNode("Add Sub Account");
                var t3 = document.createTextNode("Remove");
                //
                div.appendChild(ndiv);
                ndiv.appendChild(t);
                //
                addbtn.appendChild(t2);
                rembtn.appendChild(t3);
                ndiv.appendChild(addbtn);
                ndiv.appendChild(rembtn);

                var bInput = document.createElement('input');
                bInput.type = 'hidden';
                bInput.id = 'budget';

                budget = $("#acc_budget").val();
                bInput.value = budget;
                ndiv.appendChild(bInput);
                t4 = document.createTextNode("Budget: "+budget);
                total_budget = total_budget+parseInt(budget);
                ndiv.appendChild(t4);  /// APPEND THE BUDGET TEXT NODE WALA PA SA DIV
                $("#tb").text(total_budget);
            }
        });
    </script>
</head>
<body>
    <h1>Hello World</h1>
    <br>
    <div>
        Choose Account: <select name="account" id="account_list">
            <!-- todo previous accounts sample palang to-->
            <option value="Supplies & Stationary">Supplies & Stationary</option>
            <option value="Publication">Publication</option>
        </select> <br> <br>
        Previous Year Budget: <!-- todo get budget of this acc from prev year --><br> <br>
        Input New Budget: <input type="text" id="acc_budget"> <div id="budget_error"></div> <br> <br>
        <a href="" id="add">Add</a>
    </div>
    <br>
    <h3>Accounts:</h3>
    <div id="accounts_list_added">

    </div>
    <br>
    <div id="hidden_form">
        <form action="{{ route('submit_budget') }}" method="post" id="accounts_form">
            <input type="hidden" name="counter" id="counter" value="0">
            Start of AY: <input type="date" name="start_date" id="start_date">
            End of AY:(not sure dito pero nasa db) <input type="date" name="end_date" id="end_date">
            <br>
            {{ csrf_field() }}
            <input type="submit">
        </form>
    </div>
    <div>
        <h1>Total Budget: <span id="tb"></span></h1>
    </div>
</body>
</html>