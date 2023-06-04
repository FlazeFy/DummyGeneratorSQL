<h2>Select your database</h2>
<select class="form-select" style="max-width:320px;" id="dbopt" aria-label="Default select example">
    <option selected>---</option>
    @foreach($dbopt as $opt)
        <option value="{{$opt->app_code}}_{{$opt->database_desc}}_{{$opt->database_name}}">{{$opt->database_name}}</option>
    @endforeach
</select>
<div id="dbopt_msg" class="msg-info"></div>

<div id="dummy_total_section" class="d-none">
    <h2 class="mt-3">How many data will be generated</h2>
    <input class="form-control" style="max-width:320px;" id="dummy_total" type="number" min="1" max="250">
    <div id="dummy_total_msg" class="msg-info"><i class='fa-solid fa-circle-info'></i> Up to 250 data</div>
</div>

@include('insert.table-conf')

<script>
    var dbopt = document.getElementById("dbopt");
    var dummy_total = document.getElementById("dummy_total");

    var dbopt_msg = document.getElementById("dbopt_msg");
    var dummy_total_section = document.getElementById("dummy_total_section");
    var query_section = document.getElementById("query_section");

    dbopt.addEventListener("change", controlDBOpt);
    dummy_total.addEventListener("change", controlDummyTotal);
    
    startSection();
    function startSection(){
        if(sessionStorage.getItem('selected_dbopt') != null){
            dbopt.value = sessionStorage.getItem('selected_dbopt');
            controlDBOpt();
        } 
        if(sessionStorage.getItem('dummy_total') != null){
            dummy_total.value = sessionStorage.getItem('dummy_total');
            controlDummyTotal();
        } 
    }

    function controlDBOpt(){
        const dbopt_val = dbopt.value.split("_");
        sessionStorage.setItem('selected_dbopt', dbopt.value);

        setDesc(dbopt_val[1]);

        dummy_total_section.setAttribute('class', 'd-inline');
    }

    function controlDummyTotal(){
        sessionStorage.setItem('dummy_total', dummy_total.value);

        query_section.setAttribute('class', 'd-inline');
    }

    function setDesc(desc){
        dbopt_msg.innerHTML = "<i class='fa-solid fa-circle-info'></i> " + ucFirst(desc) + ".";
    }
</script>