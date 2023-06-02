function cleanTableColumnName(val){
    var res = val.trim().replace(/\s+/g, '_');
    return res;
}