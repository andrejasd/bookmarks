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
/*
    $.post('/links/link_add/', data, function (data) {
        console.log(data);
    });
*/
    $('#registerModal').modal('hide');

    var last_link = $('#plus');
    console.log(last_link);

    load = '<div class="l linkk col-xs-6 col-sm-4 col-md-3 col-lg-2" style= "border-style: solid; border-width: 2px;">asd</div>';






    $(last_link).before(load);

}

function link_refresh(id) {
    var data = {'id' : id};
    console.log(data);
    $.post('/links/link_refresh/', data, function (data) {
        console.log(data);
        data = JSON.parse(data);
        console.log(data);
        url = data['url'];
        title = data['title'];
        fname = data['fname'];
        console.log(url + ' ' + title + ' ' + fname);

        var img_element = $('[data-index = ' + id + ']').find('img').first();
        var h3_element = $('[data-index = ' + id + ']').find('h3').first();
        console.log(img_element);
        $(img_element).removeAttr('src');
        $(img_element).attr('src', fname + '?' + Math.random());
        $(h3_element).remove();

        var title_element = $('[data-index = ' + id + ']').find('#link-title');
        console.log(title_element);
        $(title_element).text(title);

    });
    return false;
}

function link_delete(id) {
    if ( confirm("Удалить закладку?") ){
        var data = {'id' : id};
        console.log(data);
        $.post('/links/link_delete/', data, function(data){
            var element = $('[data-index = ' + id + ']');
            console.log(element);
            $(element).remove();
        });
    };

    return false;
}
