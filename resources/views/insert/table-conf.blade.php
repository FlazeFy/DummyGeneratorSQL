<div id="query_section" class="d-none">
    <h2 class="mt-3">What's your table format</h2>
    <div class="table-format">
        <div class="row">
            <div class="col-6">
                <div class="mb-3" style="max-width:320px;">
                    <h6>Table Name</h6>
                    <input type="text" class="form-control-inner" id="table_name" style="width:100%;" placeholder="ex: user" required>
                    <div id="table_whole_info" class="msg-warning"></div>
                    <div id="table_pk_msg" class="msg-warning"></div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check float-end">
                    <input class="form-check-input" type="checkbox" value="" id="save_format_check" checked>
                    <label class="form-check-label" for="flexCheckChecked">&nbspSave Format</label>
                </div>
                <div class="form-check float-end" style="margin-right:20px;">
                    <input class="form-check-input" type="checkbox" value="" id="save_dummy_check" checked>
                    <label class="form-check-label" for="flexCheckChecked">&nbspSave Dummy</label>
                </div>
            </div>
        </div><hr>
        <div class="column-holder" id="column-holder"></div>
        <button class="btn btn-add" id="addcol" disabled><i class="fa-solid fa-plus"></i> Add Column</button>
    </div>
</div>

<script>
    var query_section = document.getElementById("query_section");
    var table_name = document.getElementById("table_name");
    var save_format_check = document.getElementById("save_format_check");
    var save_dummy_check = document.getElementById("save_dummy_check");
    var addcol =  document.getElementById("addcol");
    var columns = [];
    var slct_list = [];

    table_name.addEventListener("change", cleanTableName);
    table_name.addEventListener("input", validateTable);

    function cleanTableName(){
        table_name.value = cleanTableColumnName(table_name.value);
    }
    function validateTable(){
        if(table_name.value.length > 0){
            addcol.removeAttribute("disabled");
        } else {
            addcol.setAttribute("disabled", true);
        }
    }

    addcol.addEventListener("click", addColumn);

    function addColumn(){
        columns.push({
            "id" : getAttCode(),
            "column_name" : "",
            "column_type" : "",
            "column_length" : 36,
            "factory" : "",
            "is_null" : false,
            "sample" : null
        });
        validateGenerate();
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
                '<button class="btn btn-primary position-absolute mt-2" style="left:-50px; top:45px; padding-inline:10px;" data-bs-toggle="collapse" data-bs-target="#collapse_column_' + e.id + '"><i class="fa-solid fa-eye fa-sm"></i></button> ' +
                '<span id="extras_key_' + e.id + '">' + getPrimaryKeyIcon(e.column_type) + '</span> ' +
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
                        '<input type="number" class="form-control-inner" id="column_length_' + e.id + '" style="width:100%;" min="0" max="1000" oninput="updateColumn(this.value, '+"'"+e.id+"'"+', '+"'length'"+')" value="' + e.column_length + '"> ' +
                    '</div> ' +
                '</div> ' + 
                '<div class="collapse show" id="collapse_column_' + e.id + '"> ' +
                    '<div class="row my-3"> ' +
                        '<div class="col-lg-4"> ' +
                            '<h6>Factory</h6> ' +
                            '<select class="form-select" style="max-width:320px; margin-top:0;" id="factoryopt_' + e.id + '" oninput="updateColumn(this.value, '+"'"+e.id+"'"+', '+"'factory'"+')" aria-label="Default select example"></select> ' +
                        '</div> ' +
                        '<div class="col-lg-4"> ' +
                            '<h6>Samples</h6> ' +
                            '<span id="samples_opt_holder_' + e.id + '"><a class="mt-4 pt-2 fst-italic text-secondary text-decoration-none"> - No Sample Available - </a></span> ' +
                        '</div> ' +
                        '<div class="col-lg-4"> ' +
                            '<h6>Extras</h6> ' +
                            '<span id="is_null_check_holder_' + e.id + '"> ' +
                                '<input class="form-check-input" type="checkbox" '+getChecked(e.is_null)+' onchange="updateColumn(this.checked, '+"'"+e.id+"'"+', '+"'is_null'"+')" id="is_null_check_' + e.id + '"> ' +
                                '<label class="form-check-label" for="flexCheckChecked">&nbspNullable</label> ' +
                            '</span> ' +
                            '<span id="is_unique_check_holder_' + e.id + '" '+getHidden(e.column_type, '5')+'> ' +
                                '<input class="form-check-input" type="checkbox" value="" id="is_unique_check_' + e.id + '"> ' +
                                '<label class="form-check-label" for="flexCheckChecked">&nbspUnique</label> ' +
                            '</span> ' +
                            '<span id="is_hash_check_holder_' + e.id + '" '+getHidden(e.column_type, '5')+'> ' +
                                '<input class="form-check-input" type="checkbox" value="" id="is_hash_check_' + e.id + '"> ' +
                                '<label class="form-check-label" for="flexCheckChecked">&nbspHash</label> ' +
                            '</span> ' +
                        '</div> ' +
                    '</div> ' +
                    '<h6>Available Samples</h6> ' +
                    '<div id="sample_template_load_' + e.id + '" class="pt-1 row"></div> ' +
                '</div> ' +
            '</div> ';

            $("#column-holder").append(elmt);   
            loadFactory(e.id);

        });   
    }

    function updateColumn(val, id, type){
        const objIndex = columns.findIndex((obj => obj.id == id));
        if (typeof val === "string") {
            val = cleanTableColumnName(val);
        }

        if(type == "column"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : val,
                "column_type" : columns[objIndex].column_type,
                "column_length" : parseInt(columns[objIndex].column_length),
                "factory" : columns[objIndex].factory,
                "is_null" : columns[objIndex].is_null,
                "sample" : null
            };
        } else if(type == "type"){
            if(val == "5"){
                var nullable = false;
                document.getElementById("is_unique_check_"+id).checked = true;
                document.getElementById("is_unique_check_"+id).disabled = true;
                document.getElementById("is_null_check_holder_"+id).hidden = true;
                document.getElementById("is_hash_check_holder_"+id).hidden = true;
                document.getElementById("extras_key_" + id).innerHTML = '<button class="btn btn-primary-outlined position-absolute mt-2" style="left:-50px; top:90px; padding-inline:9px;"><i class="fa-solid fa-key text-primary"></i></button>';
            } else {
                document.getElementById("is_unique_check_"+id).checked = false;
                document.getElementById("is_unique_check_"+id).disabled = false;
                document.getElementById("is_unique_check_"+id).hidden = false;
                document.getElementById("is_null_check_holder_"+id).hidden = false;
                document.getElementById("is_hash_check_holder_"+id).hidden = false;
                document.getElementById("extras_key_" + id).innerHTML = "";
                var nullable = columns[objIndex].is_null;
            }

            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : val,
                "column_length" : parseInt(columns[objIndex].column_length),
                "factory" : columns[objIndex].factory,
                "is_null" : nullable,
                "sample" : null
            };
        } else if(type == "factory"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : columns[objIndex].column_type,
                "column_length" : parseInt(columns[objIndex].column_length),
                "factory" : val,
                "is_null" : columns[objIndex].is_null,
                "sample" : null
            };

            //Generate samples
            if(val == "ctryr"){
                var session_slct_list = sessionStorage.getItem('slct_samples_'+id);

                var elmt = ' ' +
                    '<button class="btn btn-open-sample" onclick="loadSample(' + "'" + id + "'" + ')" data-bs-toggle="modal" data-bs-target="#manageSample_' + id + '"><i class="fa-solid fa-gear"></i> Manage Sample</button> ' +
                    '<div class="position-relative"> ' +
                        '<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="manageSample_' + id + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog modal-lg"> ' +
                                '<div class="modal-content">  ' +
                                    '<div class="modal-body pt-4" style="height:75vh;"> ' +
                                        '<button type="button" class="custom-close-modal" onclick="cleanListSample('+"'"+id+"'"+')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<h5>Sample Factory <span class="text-primary fw-bolder">Country</span></h5> ' +
                                        '<h6>Available Samples</h6> ' +
                                        '<div id="sample_item_holder_' + id + '"></div> ' +
                                        '<div id="sample_loading_holder_' + id + '"> ' +
                                            '<lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> ' +
                                        '</div> ' +
                                        '<h6>Selected Samples</h6> ' +
                                        '<div id="sample_slct_item_holder_' + id + '"></div> ' +
                                        '<span id="btn-submit-holder_' + id + '"><button disabled class="btn custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ' +
                    '</div> ';
                document.getElementById("samples_opt_holder_" + id ).innerHTML = elmt;
            } else {
                document.getElementById("samples_opt_holder_" + id ).innerHTML = '<a class="mt-4 pt-2 fst-italic text-secondary text-decoration-none"> - No Sample Available - </a>';
            }
        } else if(type == "length"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : columns[objIndex].column_type,
                "column_length" : parseInt(val),
                "factory" : columns[objIndex].factory,
                "is_null" : columns[objIndex].is_null,
                "sample" : null
            };
        } else if(type == "is_null"){
            columns[objIndex] = {
                "id" : columns[objIndex].id,
                "column_name" : columns[objIndex].column_name,
                "column_type" : columns[objIndex].column_type,
                "column_length" : parseInt(columns[objIndex].column_length),
                "factory" : columns[objIndex].factory,
                "is_null" : val,
                "sample" : null
            };
        }
        validateGenerate();
        getAvailableSelectedSample(id, type);
        console.log(columns);
    }

    function getAvailableSelectedSample(id, type){
        var matchingValues = [];

        $("#sample_template_load_"+id).empty();
        for (var key in sessionStorage) {
            if (key.startsWith("selected_sample_value_")) {
                var value = JSON.parse(sessionStorage.getItem(key));
                var type = key.startsWith("selected_sample_type_");
                var spt = key.split("_");
                
                $("#sample_template_load_"+id).append("<div class='col-lg-6 col-md-6 col-sm-12'><div class='template-value-holder' title='Use this sample template'><h6 class='text-primary'>"+ucFirst(sessionStorage.getItem("selected_sample_type_"+spt[3]))+"</h6><div id='template_value_"+id+"_"+key+"'></div></div></div>");
                value.map((val, index) => {
                    if(val['sample_slug'] !== undefined){
                        $('#template_value_'+id+'_'+key).append("<div class='d-inline' id='sample_slct_col_"+val['sample_slug']+"'><input hidden name='content_sample[]' value='{"+'"'+"sample_slug"+'"'+":"+'"'+val['sample_slug']+'"'+", "+'"'+"sample_name"+'"'+":"+'"'+val['sample_name']+'"'+"}'><button class='btn btn-sample off' onclick=''>" + ucFirst(val['sample_name']) + "</button></div>");
                    }
                });
            }
        }
    }

    function deleteColumn(id){
        const objIndex = columns.findIndex((obj => obj.id == id));
        columns.splice(objIndex, 1);

        validateGenerate();

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

            var elmt = "<option selected "+getSelected("---", columns[objIndex].factory)+">---</option>";
            $("#factoryopt_"+id).append(elmt);  

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

    function cleanListSample(id){
        $("#sample_item_holder_"+id).empty();
    }

    function addSelectedSample(id, sample_slug, sample_name, is_deleted){
        var found = false;

        //Remove selected sample from sample collection
        if(is_deleted){
            var sample = document.getElementById('sample_col_'+sample_slug);
            sample.parentNode.removeChild(sample);
        }

        if(slct_list.length > 0){
            slct_list.map((val, index) => {
                if(val["sample_slug"] == sample_slug){
                    found = true;
                }
            });
            console.log(found)

            if(found == false){
                slct_list.push({
                    "sample_name":sample_name, 
                    "sample_slug":sample_slug, 
                });
                $("#sample_slct_item_holder_"+id).append("<div class='d-inline' id='sample_col_"+sample_slug+"'><input hidden name='content_sample[]' value='{"+'"'+"sample_slug"+'"'+":"+'"'+sample_slug+'"'+", "+'"'+"sample_name"+'"'+":"+'"'+sample_name+'"'+"}'><button class='btn btn-sample off' onclick='removeSelectedSample("+'"'+ id +'"'+", "+'"' + sample_slug + '"'+", "+'"' + sample_name + '"'+")'>" + ucFirst(sample_name) + "</button></div>");
            }
        } else { 
            slct_list.push({
                "sample_name":sample_name, 
                "sample_slug":sample_slug, 
            });
            $("#sample_slct_item_holder_"+id).append("<div class='d-inline' id='sample_col_"+sample_slug+"'><input hidden name='content_sample[]' value='{"+'"'+"sample_slug"+'"'+":"+'"'+sample_slug+'"'+", "+'"'+"sample_name"+'"'+":"+'"'+sample_name+'"'+"}'><button class='btn btn-sample off' onclick='removeSelectedSample("+'"'+ id +'"'+", "+'"' + sample_slug + '"'+", "+'"' + sample_name + '"'+")'>" + ucFirst(sample_name) + "</button></div>");
        }

        getButtonSubmitSample(id);
    }

    function removeSelectedSample(id, sample_slug, sample_name){
        //Remove selected sample
        var sample = document.getElementById('sample_col_'+sample_slug);
        slct_list = slct_list.filter(function(e) { return e['sample_slug'] !== sample_slug })
        sample.parentNode.removeChild(sample);

        //Return selected sample to sample collection
        $("#sample_item_holder_"+id).append('<button class="btn btn-sample off" id="sample_col_' + sample_slug +'" onclick="addSelectedSample('+"'"+ id +"'"+', '+"'" + sample_slug + "'"+', '+"'" + sample_name + "'"+',true)">' + ucFirst(sample_name) + '</button>');

        getButtonSubmitSample(id);
    }

    function loadSample(id) {        
        var page = 1;
        $("#sample_loading_holder_"+id).show();

        $.ajax({
            url: "/api/v2/column/factory/gen/country/50?page="+1,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
            }
        })
        .done(function (response) {
            var data =  response.data.data;
            $("#sample_loading_holder_"+id).hide();
    
            for(var i = 0; i < data.length; i++){
                //Attribute
                var sampleName = data[i].country_name;

                var elmt = '<button class="btn btn-sample off" id="sample_col_' + removeSpaces(sampleName) +'" onclick="addSelectedSample('+"'"+ id +"'"+', '+"'" + removeSpaces(sampleName) + "'"+', '+"'" + sampleName + "'"+',true)">' + ucFirst(sampleName) + '</button>';
                
                $("#sample_item_holder_"+id).append(elmt);   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                // not found error
            } else {
                // handle other errors
            }
        });
    }

    function getButtonSubmitSample(id){
        if(slct_list.length == 0){
            document.getElementById("btn-submit-holder_" + id).innerHTML = '<button disabled class="btn custom-submit-modal" onclick=""><i class="fa-solid fa-lock"></i> Locked</button>';
        } else {
            document.getElementById("btn-submit-holder_" + id).innerHTML = '<button class="btn custom-submit-modal" onclick="saveSelectedSample(' + "'" + id + "'" +',' + "'country'" +')" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#successModal"><i class="fa-solid fa-floppy-disk"></i> Save</button>';
        }
    }

    function saveSelectedSample(id,type){
        sessionStorage.setItem('selected_sample_value_'+id, JSON.stringify(slct_list));
        sessionStorage.setItem('selected_sample_type_'+id, type);
    }
</script>