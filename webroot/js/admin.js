function confirmDelete(){
    if ( confirm("Delete this item?") ){
        return true;
    } else {
        return false;
    }
}

function edit_bookmark() {
    $("#editModal").modal('show');
    return false;
}