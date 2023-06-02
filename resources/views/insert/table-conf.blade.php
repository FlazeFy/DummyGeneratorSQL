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
        <div class="column-holder">
            <div class="column-box">
                <button class="btn btn-cancel position-absolute mt-2" style="left:-50px; bottom:30%;"><i class="fa-solid fa-xmark"></i></button>
                <div class="row">
                    <div class="col-lg-4">
                        <h6>Column Name</h6>
                        <input type="text" class="form-control-inner" id="column_name" style="width:100%;" placeholder="ex: first_name" required>
                    </div>
                    <div class="col-lg-4">
                        <h6>Data Type</h6>
                        <select class="form-select" style="max-width:320px; margin-top:0;" id="dbopt" aria-label="Default select example">
                            <option selected>---</option>
                            @foreach($typeopt as $opt)
                                <option value="{{$opt->app_code}}">{{ucFirst($opt->type_name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <h6>Length</h6>
                        <input type="number" class="form-control-inner" id="column_length" style="width:100%;" min="0" max="1000">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-4">
                        <h6>Factory</h6>
                        <div id="factory_selector_holder"></div>
                    </div>
                    <div class="col-lg-4">
                        <h6>Samples</h6>
                    </div>
                    <div class="col-lg-4">
                        <h6>Extras</h6>
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">&nbspNullable</label>
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">&nbspUnique</label>
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">&nbspHash</label>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-add"><i class="fa-solid fa-plus"></i> Add Column</button>
    </div>
</div>

<script>
    var query_section = document.getElementById("query_section");
</script>