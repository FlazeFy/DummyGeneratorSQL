function getAttCode() {
    let col = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        let index = Math.floor(Math.random() * col.length);
        code += col[index];
    }
    return code;
}