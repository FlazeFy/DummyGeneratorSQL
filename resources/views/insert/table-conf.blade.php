<div id="query_section" class="d-none">
    <h2 class="mt-3">What's your table format</h2>
    <div class="table-format">
        <div class="row">
            <div class="col-6">
                <div class="mb-3" style="max-width:320px;">
                    <h6>Table Name</h6>
                    <input type="text" class="form-control-inner" id="table_name" style="width:100%;" placeholder="ex: user" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check float-end">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">&nbspSave Format</label>
                </div>
                <div class="form-check float-end" style="margin-right:20px;">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">&nbspSave Dummy</label>
                </div>
            </div>
        </div>
        <div class="column-holder" id="column-holder"></div>
        <button class="btn btn-add" id="addcol"><i class="fa-solid fa-plus"></i> Add Column</button>
    </div>
</div>

<script>
    var query_section = document.getElementById("query_section");
    var table_name = document.getElementById("table_name");
    var addcol =  document.getElementById("addcol");
    var columns = [];

    table_name.addEventListener("change", cleanTableName);

    function cleanTableName(){
        table_name.value = cleanTableColumnName(table_name.value);
    }
    //typeopt.addEventListener("change", loadFactory);

    addcol.addEventListener("click", addColumn);

    function addColumn(){
        columns.push({
            "id" : getAttCode(),
            "column_name" : "",
            "column_type" : "",
            "column_length" : 36,
            "factory" : ""
        });
        loadColumns();
    }

    function getSelected(val, slct){
        if(val == slct){
            return "selected"; 
        }
        return "";
    }

    function getSelectedOpt(slct){
        if(slct == ""){
            var elmt = ' ' +
                '<option selected>---</option> ' +
                '@foreach($typeopt as $opt) ' +
                    '<option value="{{$opt->id}}">{{ucFirst($opt->type_name)}}</option> ' +
                '@endforeach ';
            return elmt;
        } else {
            var elmt = ' ' +
                '@foreach($typeopt as $opt) ' +
                    '<option value="{{$opt->id}}" '+getSelected("{{$opt->id}}", slct)+' >{{ucFirst($opt->type_name)}}</option> ' +
                '@endforeach ';
                
            return elmt;
        }
    }
    
    function loadColumns(){
        $("#column-holder").empty(); 

        columns.forEach(e => {
            var elmt = '<div class="column-box" id="column-box_' + e.id + '"> ' +
                '<button class="btn btn-cancel position-absolute mt-2" style="left:-50px; top:0;" onclick="deleteColumn('+"'"+e.id+"'"+')"><i class="fa-solid fa-xmark"></i></button> ' +
                '<button class="btn btn-primary position-absolute mt-2" style="left:-50px; top:45px; padding-inline:10px;"><i class="fa-solid fa-eye fa-sm"></i></button> ' +
                '<div class="row"> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Column Name</h6> ' +
                        '<input type="text" class="form-control-inner" id="column_name_' + e.id + '" oninput="updateColumn(this.value, '+"'"+e.id+"'"+', '+"'column'"+')" style="width:100%;" placeholder="ex: first_name" value="' + e.column_name + '" required> ' +
                    '</div> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Data Type</h6> ' +
                        '<select class="form-select" style="max-width:320px; margin-top:0;" id="typeopt_' + e.id + '" onchange="loadFactory('+"'"+e.id+"'"+'); updateColumn(this.value, '+"'"+e.id+"'"+', '+"'type'"+')" aria-label="Default select example"> ' +
                            getSelectedOpt(e.column_type) +
                        '</select> ' +
                    '</div> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Length</h6> ' +
                        '<input type="number" class="form-control-inner" id="column_length" style="width:100%;" min="0" max="1000"> ' +
                    '</div> ' +
                '</div> ' + 
                '<div class="row mt-3"> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Factory</h6> ' +
                        '<select class="form-select" style="max-width:320px; margin-top:0;" id="factoryopt_' + e.id + '" oninput="updateColumn(this.value, '+"'"+e.id+"'"+', '+"'factory'"+')" aria-label="Default select example"></select> ' +
                    '</div> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Samples</h6> ' +
                    '</div> ' +
                    '<div class="col-lg-4"> ' +
                        '<h6>Extras</h6> ' +
                        '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"> ' +
                        '<label class="form-check-label" for="flexCheckChecked">&nbspNullable</label> ' +
                        '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"> ' +
                        '<label class="form-check-label" for="flexCheckChecked">&nbspUnique</label> ' +
                        '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"> ' +
                        '<label class="form-check-label" for="flexCheckChecked">&nbspHash</label> ' +
                    '</div> ' +
                '</div> ' +
            '</div> ';

            $("#column-holder").append(elmt);   
            loadFactory(e.id);

        });   
    }

    function updateColumn(val, id, type){
        const objIndex = columns.findIndex((obj => obj.id == id));
        val = cleanTableColumnName(val);

        if(type == "column"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : val,
                "column_type" : columns[objIndex].column_type,
                "column_length" : columns[objIndex].column_length,
                "factory" : columns[objIndex].factory
            };
        } else if(type == "type"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : val,
                "column_length" : columns[objIndex].column_length,
                "factory" : columns[objIndex].factory
            };
        } else if(type == "factory"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : columns[objIndex].column_type,
                "column_length" : columns[objIndex].column_length,
                "factory" : val
            };
        }
        console.log(columns);
    }

    function deleteColumn(id){
        const objIndex = columns.findIndex((obj => obj.id == id));
        columns.splice(objIndex, 1);

        $("#column-box_"+id).remove();
    }

    
    function loadFactory(id) {        
        var idx = document.getElementById("typeopt_"+id).value;
        const objIndex = columns.findIndex((obj => obj.id == id));

        $.ajax({
            url: "/api/v2/column/factory/"+idx,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
            }
        })
        .done(function (response) {
            var data =  response.data;
            $("#factoryopt_"+id).empty();

            for(var i = 0; i < data.length; i++){
                //Attribute
                var appCode = data[i].app_code;
                var factoryName = data[i].factory_name;

                var elmt = "<option value='" + appCode + "' "+getSelected(appCode, columns[objIndex].factory)+">" + ucFirst(factoryName) + "</option>";
                
                $("#factoryopt_"+id).append(elmt);   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#empty_item_holder").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>Data not found</h6></div>");
            } else {
                // handle other errors
            }
        });
    }
</script>