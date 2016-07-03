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

    $("#editLink").modal('show');

    return false;
}

function add_new_link() {
    link = $("#link").val();
    title = $('#title').val();
    tab_id = $("#link_tab option:selected").val();
    var data = {'link' : link, 'title' : title, 'tab_id' : tab_id};
    console.log(data);

    $('#newLink').modal('hide');

    var jqxhr = $.post('/links/link_add/', data, function (data) {});

    jqxhr.function (){
        console.log('Пошел ЗЗЗААПППРОССССССС!!!');
        /*
        console.log("ДОБАВЛЕНО "+data);
        data = JSON.parse(data);
        id = data['id'];
        title = data['title'];
        console.log(id);

        var last_link = $('#plus'+tab_id);

        load = '<div class=\"l linkk col-xs-6 col-sm-4 col-md-3 col-lg-2\" data-index=\"' + id + '\">\
            <a href=\"' + link + '\" class=\"thumbnail my_thumbnail\">\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_delete(' + id + ');\"><span class=\"glyphicon glyphicon-remove\"></span></button>\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_edit(' + id + ');\"><span class=\"glyphicon glyphicon-cog\"></span></button>\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_refresh(' + id + ');\"><span class=\"glyphicon glyphicon glyphicon-refresh\"></span></button>\
            <img src=\"uploads\/ajax-loader.gif\">\
            <hr>\
            <p id="link-title" class="text-primary size">' + title + '</p>\
        </a>\
        </div>\
        ';

        $(last_link).before(load);
        */
    });

    jqxhr.complete(function() {
        console.log('Есть КАРТИНКА!!!!!!!!!!!');
        /*
        load =  '<img src=\"uploads\/preview\/'+id+'.jpg\">';
        console.log(load);
        var new_link = $('[data-index=' + id + ']');
  //      $(new_link).find("img").attr('src','uploads/preview/'+id+'.jpg');
//        console.log(new_link);*/
    });


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

function add_new_tab() {
    title = $('#tab').val();
    var data = {'title' : title};
    $('#newTab').modal('hide');

    $.post('/links/tab_add/', data, function (data) {
        console.log("ДОБАВЛЕНО "+data);
        data = JSON.parse(data);
        id = data['id'];
        title = data['title'];
/*
        var last_tab = $('#plus');
        load = '<div class=\"l linkk col-xs-6 col-sm-4 col-md-3 col-lg-2\" data-index=\"' + id + '\">\
            <a href=\"' + link + '\" class=\"thumbnail my_thumbnail\">\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_delete(' + id + ');\"><span class=\"glyphicon glyphicon-remove\"></span></button>\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_edit(' + id + ');\"><span class=\"glyphicon glyphicon-cog\"></span></button>\
            <button type=\"button\" class=\"close my_close\" onclick=\"return link_refresh(' + id + ');\"><span class=\"glyphicon glyphicon glyphicon-refresh\"></span></button>\
            <img src=\"\">\
            <h3>' + title + '</h3>\
            <hr>\
            <p id="link-title" class="text-primary size">' + title + '</p>\
        </a>\
        </div>\
        ';
        $(last_link).before(load);
  */
    });
}