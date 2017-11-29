<html>
<head>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(function(){
            var acc_num = 1;
            var sec_acc_num = 1;
            var form = document.forms['accounts_form'];
            var div = document.getElementById("accounts_list_added");
            var mdiv = document.getElementById("modal-sec");
            var secmodal = document.getElementById("modal-sec");
            var primary_acc_count = 0;
            var secondary_max_acc_count = 0;
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

            $("#closeBtn").click(function(){
                secmodal.style.display = 'none';
                $("#secondary_acc_ref_div").remove();
                $("#secondary_acc_new_id").remove();
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

            $("#accounts_list_added").on('click', '#add_btn', function(){
                secmodal.style.display = 'block';

                var hiddenRefVal = document.createElement('input');
                hiddenRefVal.type = 'hidden';
                hiddenRefVal.id = 'secondary_acc_ref_div';

                $parent_id = $(this).parent('div').attr('id');
                $div = "secondary_accounts_list_added-"+$parent_id;

                hiddenRefVal.value = $div;

                $ctr = $(this).parent('div').find('#ctr').val();
                console.log($ctr);
                $ctr++;
                $(this).parent('div').find('#ctr').val($ctr);

                $new_id = $parent_id+'-'+$ctr;

                var hiddenNewIdVal = document.createElement('input');
                hiddenNewIdVal.type = 'hidden';
                hiddenNewIdVal.id = 'secondary_acc_new_id';
                hiddenNewIdVal.value = $new_id;

                var hiddenBudgetVal = document.createElement('input');
                hiddenBudgetVal.type = 'hidden';
                hiddenBudgetVal.id = 'secondary_acc_total_budget';
                hiddenBudgetVal.value = $(this).parent('div').find('#ctr').val();

                var hiddenCtrVal = document.createElemet('input');
                hiddenCtrVal.type = 'hidden';
                hiddenCtrVal.id = 'secondary_acc_ctr';
                hiddenCtrVal.value = $ctr;

                secondary_max_acc_count++;
                mdiv.appendChild(hiddenNewIdVal);
                mdiv.appendChild(hiddenRefVal);
                mdiv.appendChild(hiddenBudgetVal);
                mdiv.appendChild(hiddenCtrVal); //di ata kailangan
                console.log(hiddenNewIdVal);
                //todo move all below this to modal

                var pdiv = document.getElementById($div);
                var ndiv = document.createElement('div');
                ndiv.id = $new_id;
                pdiv.appendChild(ndiv);

                jQuery('</div>', {
                    id: $new_id,
                }).appendTo("#"+$div);

            });

            $("#modal-sec-add").click(function(){
                //TODO validate values
                $("#sec-modal-errors").text('');
                if($("#sec_account_budget").val() == '' || $("#sec_account_budget").val()){
                    $("#sec-modal-errors").text('Budget cannot be empty');
                    return;
                }
                if($("#sec_account_budget").val() <= 0){
                    $("#sec-modal-errors").text("Budget cannot be negative");
                    return;
                }
                if(!$.isNumeric($("#sec_account_budget").val())){
                    $("#sec-modal-errors").text("Budget should be numerical");
                    return;
                }
                //if($("#secondary_acc_total_budget").val() < ) TODO Summation
                $div = $("#hiddenRefVal").val();
                var pdiv = document.getElementById($div);
                //create new secondary account div
                var ndiv = document.createElement('div');
                ndiv.id = $("#hiddenNewIdVal").val();
                //append new secondary account div to primary account div
                pdiv.appendChild(ndiv);
                //create hidden elements with new values
                var input = document.createElement('input');
                input.type = 'hidden';
                input.id = "account_num_"+$("#hiddenNewIdVal").val();
                input.value = $("#sec_account_list option:selected").text()

                var bInput = document.createElement('input');
                bInput.type = 'hidden';
                bInput.id = 'budget_num_'+$("#hiddenNewIdVal").val();
                bInput.value = $("#sec_account_budget").val();
                //TODO append these to form
                
                //TODO append list of tertiary accounts div to newly created secondary account div
                var tdiv = document.createElement('div');
                tdiv.id = "tertiary_accounts_list_added-";
                //TODO remove newly added account from secondary accounts select option
                //hide modal
                secmodal.style.display = 'none';
                //clear modal
                $("#sec_account_budget").val('');
                //delete all hidden values inside sec modal
                $("#secondary_acc_ref_div").remove();
                $("#secondary_acc_new_id").remove();
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

                var hiddenCtr = document.createElement('input');
                hiddenCtr.type = 'hidden';
                hiddenCtr.id = 'ctr';
                hiddenCtr.value = 0;
                ndiv.appendChild(hiddenCtr);

                t4 = document.createTextNode("Budget: "+budget);
                total_budget = total_budget+parseInt(budget);
                ndiv.appendChild(t4);  /// APPEND THE BUDGET TEXT NODE WALA PA SA DIV
                $("#tb").text(total_budget);

                var secaccdiv = document.createElement('div');
                secaccdiv.class = "secondary_acc";
                secaccdiv.id = "secondary_accounts_list_added-"+acc_num;
                ndiv.appendChild(secaccdiv);
            }

            function clickOutside(e){
                if(e.target == secmodal){
                    secmodal.style.display = 'none';
                    $("#secondary_acc_ref_div").remove();
                    $("#secondary_acc_new_id").remove();
                }
            }

            window.addEventListener('click', clickOutside);
        });
    </script>
    <link rel="stylesheet" href="{{asset('css/propose_budget.css')}}">
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
    <div id="modal-sec" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span id="closeBtn" class="closeBtn">&times;</span>
                <h3>Add Secondary Account</h3>
            </div>
            <div class="modal-body">
                <div id="sec-modal-errors" class="modal-errors">

                </div>
                <div class="modal-select">
                    <h5>Choose Secondary Account: </h5>
                    <select name="sec_account_list" id="sec_account_list">
                        <option value="Supplies & Stationary">Supplies & Stationary</option>
                        <option value="Publication">Publication</option>
                        <!-- TODO load all possible secondary accounts, sample pa lang to -->
                    </select>
                </div>
                <div class="modal-budget">
                    <h5>Budget Amount: </h5>
                    <input type="text" id="sec_account_budget" class="button">
                </div>
            </div>
            <div class="modal-add">
                <button id="modal-sec-add">Add Secondary Account</button>
            </div>
        </div>
    </div>
</body>
</html>