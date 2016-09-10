
// tab activation by ID
function tab_activate(id) {
    var element$ = $('#tab_panel a[href="#tab' + id + '"]');
    if  ( element$.length>0 ){
        element$.tab('show');
    } else{
        first_tab_activate()
    }
}

//first tab activation
function first_tab_activate() {
    $('#tab_panel li:first a').tab('show');
}

// ----------------- AJAX ----------------------

// данные о ссылке для окна редактирования ссылки
function link_edit(id) {
    var data = {'id' : id};
    console.log('data');
    console.log(data);
    $.post('/links/getLinkData/', data, function (data) {
        data = JSON.parse(data);
        console.log(data);
        $("#new_link").val(data['url']);
        $("#new_title").val(data['title']);
        $("#link_id").val(id);
    });
    $("#editLink").modal('show');
    return false;
}

// редактирование ссылки после нажатия кнопки ИЗМЕНИТЬ
function link_finish_edit() {
    var link = $("#new_link").val();
    link = encodeURI(link); //для кирилиці
    var title = $("#new_title").val();
    var link_id = $("#link_id").val();
    var data = {'link' : link, 'title' : title, 'link_id' : link_id};
    console.log(data);
    $.post('/links/link_edit/', data, function (data) {
        if (data)
            console.log('OK');
        else
            console.log('ERROR');
        // изменение DOM
        var div_link = $('[data-index = ' + link_id + ']');
        var title_element = div_link.find('p');
        title_element.html(title);
        var a_element = div_link.find('a');
        a_element.attr('href', link);
    });
}

function add_new_link() {
    var link = $("#link").val();
    link = encodeURI(link); //для кирилиці
    var title = $('#title').val();
    var tab_id = $("#link_tab option:selected").val();
    var data = {'link': link, 'title': title, 'tab_id': tab_id};
    console.log(data);
    $('#newLink').modal('hide');

    // запрос на добавление в базу
    $.post('/links/link_add/', data, function (data) {
        if (data == false) {
            console.log("ОШИБКА!!!!!");
            alert("ОШИБКА!!!!!");
            return false;
        }

        console.log("ДОБАВЛЕНО В БАЗУ!!!!!!!!!! " + data);
        data = JSON.parse(data);
        link = data['url'];
        title = data['title'];
        var id = data['id'];

        var last_link$ = $('#plus' + tab_id);
        var load = '<div class=\"l linkk col-xs-6 col-sm-4 col-md-3 col-lg-2\" data-index=\"' + id + '\">\
                    <a href=\"' + link + '\" class=\"thumbnail my_thumbnail\" target="_blank">\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_delete(' + id + ');\"><span class=\"glyphicon glyphicon-remove\"></span></button>\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_edit(' + id + ');\"><span class=\"glyphicon glyphicon-cog\"></span></button>\
                    <button type=\"button\" class=\"close my_close\" onclick=\"return link_refresh(' + id + ');\"><span class=\"glyphicon glyphicon glyphicon-refresh\"></span></button>\
                    <div class="loader-wrapper">\
                        <div class="loader"></div>\
                    </div>\
                    <hr>\
                    <p class="text-primary size">' + title + '</p>\
                    </a>\
                    </div>\
        ';
        $(last_link$).before(load);

        // сделать отдельной функцией. сначала имя файла рандом а потом ренейм на ид.
        // запрос на создание превьхи
        // ??
        console.log(data);

        $.post('/links/create_image/', data, function (data) {
            console.log('Есть КАРТИНКА!!!!!!!!!!! ' + data);
            var id = data;
            var load = '<img src=\"uploads\/preview\/' + id + '.jpg\">';
            console.log(load);
            var new_link = $('[data-index=' + id + ']');
            $(new_link).find(".loader-wrapper").remove();
            $(new_link).find("hr").before('<img src="">');
            $(new_link).find("img").attr('src', 'uploads\/preview\/' + id + '.jpg');
            console.log(new_link);
        });

    });
}

function link_refresh(id) {
    var data = {'id' : id};
    console.log(data);

    var load = '<div class="loader"></div>';
    var div_link = $('[data-index = ' + id + ']');
    var img_element = div_link.find('img').first();
    var h4_element = div_link.find('h4').first();

    if (h4_element.length > 0)
        $(h4_element).remove();

    $(img_element).removeAttr('src');

    var loader = div_link.find('.loader');
    if (loader.length <= 0){
        $(img_element).after(load);
        loader = div_link.find('.loader');
    }

    console.log(loader);

    $.post('/links/link_refresh/', data, function (data) {
        console.log(data);
        data = JSON.parse(data);
        console.log(data);
        var url = data['url'];
        var title = data['title'];
        var file_name = data['file_name'];
        console.log(url + ' ' + title + ' ' + file_name);
        loader.remove();
        $(img_element).attr('src', file_name + '?' + Math.random());
        var title_element = div_link.find('p');
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
    }

    return false;
}

function add_new_tab() {
    var title = $('#tab').val();
    var data = {'title' : title};
    $('#newTab').modal('hide');

    $.post('/links/tab_add/', data, function (data) {
        console.log("ДОБАВЛЕНО "+data);
        data = JSON.parse(data);
        var id = data['id'];
        title = data['title'];

        // добавляем содержимое вкладки
        var tab_content = $('.tab-content');
        var load = '<div class="tab-pane" id="tab' + id + '">\
                    <div class="row">\
                    <div id="plus' + id + '" class="linkk col-xs-6 col-sm-4 col-md-3 col-lg-2">\
                    <a class="thumbnail my_thumbnail" data-content="' + id + '" data-toggle="modal" data-target="#newLink" tabindex="-1"><p class="center-block glyphicon glyphicon-plus large_icon"></p></a>\
                    </div></div></div>\
                    ';
        console.log(load);
        $(tab_content).append(load);

        // сделать редирект на новую вкладку??? или добавить куку с новой вкладкой

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
        tab_activate(id);
    });
}

// функция при нажатии кнопки редактировать вкладку
function edit_tab() {
    var active_tab = $('#tab_panel').find('.active').children('a');
    var tab_title = active_tab.html();
    var str = active_tab.attr('href');
    var tab_id = str.replace('#tab', '');
    console.log(tab_id);
    console.log(tab_title);
    var editTab = $("#editTab");
    editTab.find('#tab_id').val(tab_id); // заполняем скрытое поле #tab_id
    editTab.find('.modal-header').find('h4').html('Редактировать/удалить вкладку ' + tab_title); // добавляем в шапку имя вкладки
    editTab.find('.modal-body').find('#new_tab_title').val(tab_title); // заполняем поле #new_tab_title именем вкладки
    $('#editTab option:disabled').each(function(){ // разблокирум ранее заблокированный select
        this.disabled=false;
    });
    editTab.find('option[value="'+tab_id+'"]').prop("disabled",true); // блокирум выбор активной вкладки
    // отобразить модальное окно
    editTab.modal('show');
}

// переименование вкладки
function rename_tab() {
    var editTab = $("#editTab");
    var id = editTab.find('#tab_id').val();
    var title = editTab.find('.modal-body').find('#new_tab_title').val();
    console.log(id + ' ' + title);
    var data = {'id' : id,
        'title' : title};
    $.post('/links/tab_rename/', data, function (data) {
        // if (data) {...}
        var active_tab = $('#tab_panel').find('.active').children('a');
        active_tab.html(title);
        $('option[value="'+id+'"]').html(title);
    });
}

// удаление вкладки
function delete_tab() {
    var editTab = $("#editTab");
    var tab_id = editTab.find('#tab_id').val();
    console.log(tab_id);
    var option = editTab.find('input[type="radio"]:checked').val();
    console.log(option);
    var full_delete = (option == 'option1');
    console.log(full_delete);
    var move_link_tab = editTab.find("#move_link_tab option:selected").val();
    console.log(move_link_tab);
    var data = {'tab_id' : tab_id,
        'full_delete' : full_delete,
        'move_link_tab' : move_link_tab};
    $.post('/links/tab_delete/', data, function (data) {
        console.log(data);
        if (option == 'option2'){
            // активируем вкладку
            var tab = $( 'a[href="#tab' + move_link_tab + '"]' );
            tab.tab('show');
        }

        // обновляем страницу
        window.location.href = "/";
    });
}