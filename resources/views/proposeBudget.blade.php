<html>
<head>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(function(){
            var acc_num = 1;
            var form = document.forms['accounts_form'];
            var div = document.getElementById("accounts_list_added");

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
                $("#account_list").append($('<option>', {
                    value: text,
                    text: text
                }));

                id = $(this).parent().attr("id");

                $(this).closest('div').remove();
                $("input[name='account_num_"+ id +"']").remove();
                $("input[name='budget_num_"+ id +"']").remove();

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
                var h = document.createElement('p');
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

                budget = $("#acc_budget").val();
                t4 = document.createTextNode("Budget: "+budget);
                ndiv.appendChild(t4);

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
        Input New Budget: <input type="text" id="acc_budget"> <div id="budget_error"></div><br> <br>
        <a href="" id="add">Add</a>
    </div>
    <br>
    <h3>Accounts:</h3>
    <div id="accounts_list_added">

    </div>
    <div id="hidden_form">
        <form action="" id="accounts_form">
        </form>
    </div>
    <div>
        <h1>Total Budget: </h1>
    </div>
</body>
</html>