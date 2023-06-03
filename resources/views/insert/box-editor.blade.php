<form id="table_format_form">
    @csrf
    <input hidden id="column_format" name="column_format">
    <input hidden id="primary_key_format" name="primary_key_format">
    <input hidden id="table_name_format" name="table_name_format">
    <span id="generate_btn_holder"></span>
</form>

<div id="rich_box"></div>
<input name="content_desc" id="content_desc" hidden>

<!-- Main Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#rich_box', {
        theme: 'snow'
    });

    var generate = document.getElementById("generate");
    var table_whole_info = document.getElementById("table_whole_info");
    var table_pk_msg = document.getElementById("table_pk_msg");
    generate.addEventListener("click", generateDummy);
    var resbox = document.getElementById("rich_box");

    function generateDummy() {        
        var primary = [];
        var total = dummy_total.value;
        document.getElementById("table_name_format").value = table_name.value;
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
            url: "/api/v1/dml/oracle/insert/"+total,
            type: "post",
            data: $('#table_format_form').serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
            },
            success: function(response) {
                resbox.innerHTML = response.data;
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
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
        }
        if(pk == 0){
            table_pk_msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You haven't define the primary key column in this table";
            generate_btn_holder.innerHTML = "";
        } else {
            table_pk_msg.innerHTML = "";
        }
    }
</script>