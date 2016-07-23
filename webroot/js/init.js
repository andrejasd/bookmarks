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

    // запрос на добавление в базу
    $.post('/links/link_add/', data, function (data) {
        if (data == false){
            console.log("ОШИБКА!!!!!");
            alert("ОШИБКА!!!!!");
        }
        else{
            console.log("ДОБАВЛЕНО В БАЗУ!!!!!!!!!! " + data);
            data = JSON.parse(data);
            id = data['id'];
            title = data['title'];

            var last_link = $('#plus'+tab_id);
            load = '<div class=\"l linkk col-xs-6 col-sm-4 col-md-3 col-lg-2\" data-index=\"' + id + '\">\
                    <a href=\"' + link + '\" class=\"thumbnail my_thumbnail\">\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_delete(' + id + ');\"><span class=\"glyphicon glyphicon-remove\"></span></button>\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_edit(' + id + ');\"><span class=\"glyphicon glyphicon-cog\"></span></button>\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_refresh(' + id + ');\"><span class=\"glyphicon glyphicon glyphicon-refresh\"></span></button>\
                    <img src=\"uploads\/ajax-loader_1.gif\">\
                    <hr>\
                    <p id="link-title" class="text-primary size">' + title + '</p>\
                    </a>\
                    </div>\
            ';
            $(last_link).before(load);

            // запрос на создание превьхи
            $.post('/links/create_image/', data, function (data) {
                console.log('Есть КАРТИНКА!!!!!!!!!!!');
                load =  '<img src=\"uploads\/preview\/' + id + '.jpg\">';
                console.log(load);
                var new_link = $('[data-index=' + id + ']');
                $(new_link).find("img").attr('src','uploads\/preview\/' + id + '.jpg');
                console.log(new_link);
            });
        }
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

        // добавляем содержимое вкладки
        var tab_content = $('.tab-content');
        load = '<div class="tab-pane" id="tab' + id + '">\
                <div class="row">\
                <div id="plus' + id + '" class="linkk col-xs-6 col-sm-4 col-md-3 col-lg-2">\
                <a class="thumbnail my_thumbnail" data-content="' + id + '" data-toggle="modal" data-target="#newLink" tabindex="-1"><p class="center-block glyphicon glyphicon-plus large_icon"></p></a>\
                </div></div></div>\
                ';
        console.log(load);
        $(tab_content).append(load);

        // добавляем вкладку в список выбора вкладок
        var link_tab = $('#link_tab');
        load = '<option value=' + id + '>' + title + '</option>';
        $(link_tab).append(load);

        // добавляем вкладку
        var last_tab = $('#plus_tab');
        load = '<li class=""> <a href="#tab' + id + '" role="tab" data-toggle="tab">' + title + '</a> </li>';
        console.log(load);
        $(last_tab).before(load);

        // активируем вкладку
        var tab = $('#tab' + id);
        console.log('ТТААББ '+tab);
        tab.tab('show');

        tab_id_str = '#tab' + id;
        $('#tab_panel a[href="' + tab_id_str + '"]').tab('show');

    });
}

// функция при нажатии кнопки редактировать вкладку
function edit_tab() {
    var active_tab = $('#tab_panel').find('.active').children('a');
    tab_title = active_tab.html();
    str = active_tab.attr('href');
    tab_id = str.replace('#tab', '');
    console.log(tab_id);
    console.log(tab_title);
    editTab = $("#editTab");
    editTab.find('#tab_id').val(tab_id);
    editTab.find('.modal-header').find('h4').html('Редактировать/удалить вкладку ' + tab_title);
    editTab.find('.modal-body').find('#new_tab_title').val(tab_title);
    editTab.modal('show');
}

// переименование вкладки
function rename_tab() {
    editTab = $("#editTab");
    id = editTab.find('#tab_id').val();
    title = editTab.find('.modal-body').find('#new_tab_title').val();
    console.log(id + ' ' + title);
    var data = {'id' : id,
                'title' : title};
    $.post('/links/tab_rename/', data, function (data) {
        var active_tab = $('#tab_panel').find('.active').children('a');
        active_tab.html(title);
    });
}

// удаление вкладки
function delete_tab() {
    editTab = $("#editTab");
    id = editTab.find('#tab_id').val();
    option = editTab.find('input[type="radio"]:checked').val();
    console.log(option);
    tab_id = editTab.find("#move_link_tab option:selected").val();
    console.log(tab_id);
}