function getAttCode() {
    let col = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        let index = Math.floor(Math.random() * col.length);
        code += col[index];
    }
    return code;
}

function getPrimaryKeyIcon(type){
    if(type == "5"){
        return '<button class="btn btn-primary-outlined position-absolute mt-2" style="left:-50px; top:90px; padding-inline:9px;"><i class="fa-solid fa-key text-primary"></i></button>';
    }
}

function getHidden(val, con){
    if(val == con){
        return "hidden";
    }
}