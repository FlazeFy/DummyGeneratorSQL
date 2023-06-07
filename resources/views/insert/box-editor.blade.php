<form id="table_format_form">
    @csrf
    <input hidden id="column_format" name="column_format">
    <input hidden id="primary_key_format" name="primary_key_format">
    <input hidden id="table_name_format" name="table_name_format">
    <input hidden id="save_format" name="save_format">
    <input hidden id="save_dummy" name="save_dummy">
    <span id="generate_btn_holder"></span>
</form><br>

<div id="rich_box"></div>

<div id="loading">
    <div class="loading-screen">
        <lottie-player src="https://assets3.lottiefiles.com/private_files/lf30_mnZRTk.json" background="transparent" speed="0.5" 
            style="width: 620px; height: 620px;"  loop autoplay></lottie-player>
        <h4 class="position-absolute" style="top:55vh; left:75vh;">Generating dummy data...</h4>
    </div>
</div>

<input name="content_desc" id="content_desc" hidden>

<!-- Main Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    $('#loading').hide(); 
    var quill = new Quill('#rich_box', {
        theme: 'snow'
    });

    var table_whole_info = document.getElementById("table_whole_info");
    var table_pk_msg = document.getElementById("table_pk_msg");   
    var resbox = document.getElementById("rich_box");

    function generateDummy() {  
        window.scrollTo(0, 0);
        $('#loading').show();  

        document.getElementById("loading").setAttribute("class","d-normal");      
        var primary = [];
        var total = dummy_total.value;
        const dbopt_val = dbopt.value.split("_");
        document.getElementById("table_name_format").value = table_name.value;
        document.getElementById("save_format").value = save_format_check.checked;
        document.getElementById("save_dummy").value = save_dummy_check.checked;
        const objIndex = columns.findIndex((obj => obj.column_type == "5"));

        primary.push({
            "id" : columns[objIndex].id,
            "is_increment": false, // for now
            "column_name" : columns[objIndex].column_name,
            "column_type" : columns[objIndex].column_type,
            "column_length" : columns[objIndex].column_length,
            "factory" : columns[objIndex].factory
        });
        columns.splice(objIndex, 1);
        document.getElementById("primary_key_format").value = JSON.stringify(primary);
        document.getElementById("column_format").value = JSON.stringify(columns);

        $.ajax({
            url: "/api/v1/dml/"+dbopt_val[0]+"_"+dbopt_val[2]+"/insert/"+total,
            type: "post",
            data: $('#table_format_form').serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
            },
            success: function(response) {
                resbox.innerHTML = response.data;
                $('#loading').hide();  
                
            },  
            error: function(response, jqXHR, textStatus, errorThrown) {
                $('#loading').hide();  
  
                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    resbox.innerHTML += response.responseJSON.errors.result[0]
                } else {
                    resbox.innerHTML += errorMessage
                }
            }
        });
    }

    function validateGenerate(){
        var fail = 0;
        var pk = 0;
        columns.forEach(e => {
            if(e.id == '' || e.column_name == '' || e.column_type == '' || e.factory == ''){
                fail++;
            } 

            if(e.column_type == "5"){
                pk++;
            }
        });

        if(fail > 0){
            table_whole_info.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You have some unfinished column configuration";
            generate_btn_holder.innerHTML = "";
        } else {
            table_whole_info.innerHTML = "";
            generate_btn_holder.innerHTML = '<a class="btn btn-primary d-block mx-auto py-2 my-4" style="font-size:18px; max-width:300px;" id="generate"><i class="fa-solid fa-gears fa-lg"></i> Generate Now !</a>';
            var generate = document.getElementById("generate");
            generate.addEventListener("click", generateDummy);
        }
        if(pk == 0){
            table_pk_msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You haven't define the primary key column in this table";
            generate_btn_holder.innerHTML = "";
        } else {
            table_pk_msg.innerHTML = "";
        }
    }
</script>