function confirmDelete(){
    if ( confirm("Delete this item?") ){
        return true;
    } else {
        return false;
    }
}

// ----------------- AJAX ----------------------

// данные о ссылке для окна редактирования ссылки
function link_edit(id) {
    var data = {'id' : id};
    console.log(data);
    $.post('/links/getLinkData/', data, function (data) {
        data = JSON.parse(data);
        console.log(data);
        //alert(data['url']);
        $("#new_link").attr('value',data['url']);
        $("#new_title").attr('value',data['title']);
        $("#link_id").attr('value',id);
    });

    $("#editModal").modal('show');

    return false;
}

function add_new_link() {

    link = $("#link").val();
    title = $('#title').val();
    //console.log(link+' '+title);

    var data = {'link' : link, 'title' : title};
    console.log(data);

    $.post('/links/link_add/', data, function (data) {
        console.log(data);
    });

    $('#registerModal').modal('hide');

}

function link_refresh(id) {
    var data = {'id' : id};
    console.log(data);
    $.post('/links/link_refresh/', data, function (data) {
        data = JSON.parse(data);
        console.log(data);
        /*alert(data['url']);
        $("#new_link").attr('value',data['url']);
        $("#new_title").attr('value',data['title']);
        $("#link_id").attr('value',id);
        */
    });
    return false;
}

function link_delete(id) {
    if ( confirm("Удалить закладку?") ){
        var data = {'id' : id};
        console.log(data);
        $.post('/links/link_delete/', data, function(data){
            console.log(data);
        });
    };

    return false;
}
