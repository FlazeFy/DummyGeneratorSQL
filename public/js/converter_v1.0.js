function cleanTableColumnName(val){
    var res = val.trim().replace(/\s+/g, '_');
    return res;
}

function strToBool(val){
    if(val == "true"){
        return true;
    } else {
        return false;
    }
}

function getChecked(val){
    if(val == true){
        return "checked";   
    }
}