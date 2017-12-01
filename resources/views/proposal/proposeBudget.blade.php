<html>
<head>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/propose_budget.css')}}">
    <script>
        $(function(){
            $("#add-primary-account-btn").click(function(){
                //TOO validate
                $("#errors").text('');
                var budget = $("#primary-acc-budget-input").val();
                if(budget === 0 || budget === ""){
                    $("#errors").text('Budget cannot be empty');
                    return;
                }
                if(budget < 0){
                    $("#errors").text('Budget cannot be negative');
                    return;
                }
                var div = $("#primary-accounts-div").attr('id');
                var id = $("#primary-accounts-counter").val();
                id++;
                var name = $("#select-primary-accounts").val();
                console.log(div + ", " + id + ", " + name + ", " + budget);

                appendPrimaryAccount(div, id, name, budget);
            });

            function appendPrimaryAccount(div, id, name, budget){
                $("#primary-acc-budget-input").val('');
                console.log(div);
                jQuery('</div>', {
                    id: id
                }).appendTo("#"+div);

                jQuery('</div>', {
                    class: "account-name-div"
                }).appendTo("#"+id);

                var html = '<div class="account-name-div">' +
                                '<h3>' + name + '</h3>' +
                            '</div>'

                $("#"+id).append(html);

                jQuery('</div>', {
                    class: ""
                })
            }
        });
    </script>
</head>
<body>
    <div id="proposal-content">
        <div id="errors"></div>
        <div id="add-primary-account-div">
            <h2>Add Primary Account: </h2>
            <div id="primary-account-select">
                <h4>Choose Account: </h4>
                <select name="primary-accounts" id="select-primary-accounts">
                    @foreach($primary_accounts as $pa)
                        <option value="{{$pa->name}}">{{$pa->name}}</option>
                    @endforeach
                </select>
                <!-- TOO move these to modals
                <select name="" id="">
                    @foreach($secondary_accounts as $sa)
                        <option value="">{{$sa->name}}</option>
                    @endforeach
                </select>
                <select name="" id="">
                    @foreach($tertiary_accounts as $ta)
                    <option value="">{{$ta->name}}</option>
                    @endforeach
                </select>
                -->
                <h4>Input Budget: </h4>
                <input type="number" min="0" step="1" id="primary-acc-budget-input">
                <div id="add-primary-account-btn-div">
                    <button id="add-primary-account-btn">Add Account</button>
                </div>
            </div>
        </div>
        <form action="">
            <div id="academic-year">
                <h4>Start of Academic Year: </h4>
                <input type="date" name="start_date" id="start_date">
                <h4>End of Academic Year: </h4>
                <input type="date" name="end_date" id="end_date">
            </div>
            <div id="accounts-div">
                <h4>Accounts: </h4>
                <div id="primary-accounts-div">
                    <input type="hidden" value="0" id="primary-accounts-counter">
                </div>
            </div>
        </form>
    </div>
</body>
</html>