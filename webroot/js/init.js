$(document).ready(function(){
    <!-- установка фокуса на поле добавления новой закладки -->
    $('#bookmark').focus();

    <!-- активация последней активной категории закладок -->
    if ($.cookie('last_category_id')){
        var element$ = $("#add_bookmark select [value=" + $.cookie('last_category_id') + "]");
        if (element$.length > 0){
            element$.attr("selected", "selected");
        }else{
            $("#add_bookmark select :first").attr("selected", "selected");
        }
    } else{ // или первой
        $("#add_bookmark select :first").attr("selected", "selected");
    }

    <!-- активация последней активной вкладки -->
    if ($.cookie('last_tab_id')){
        tab_activate($.cookie('last_tab_id'))
    } else{ // или первой
        first_tab_activate();
    }

    <!-- скрипт откритя модального окна добавления новой категории ссылок -->
    var value_Opt= "new_category";
    $("select").change(function () {
        if($(this).val()!=value_Opt) return false;
        $("#new_Category").modal('show');
        return false;
    });

    // при открытии модального окна добавления новой ссылки
    $('#newLink').on('shown.bs.modal', function (event) {
        // установка фокуса и выделение текста в поле ввода новой ссылки
        $('#link').select();
        <!-- считываем с какого tab data-content пришел запрос -->
        var tab = $(event.relatedTarget);
        var tab_id = tab.data('content');
        console.log(tab_id);
        $("#link_tab [value='"+tab_id+"']").prop("selected", "selected");
    });

    <!-- установка фокуса на форме добавления новой категории закладок -->
    $('#new_Category').on('shown.bs.modal', function () {
        $('#category').focus();
    });

    <!-- установка фокуса на форме добавления новой вкладки -->
    $('#newTab').on('shown.bs.modal', function () {
        $('#tab').select();
    });

    <!-- установка куки при ???? для категории -->
    $("#add_bookmark select").change(function (e) {
        var activeCategoryId = $(e.target).val();
        $.cookie('last_category_id', activeCategoryId, {expires: 365});
        console.log('смена категории ' + activeCategoryId);
    });

    <!-- установка куки при смене вкладки -->
    $('#tab_panel a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var activeTab = $(e.target).attr('href');
        var activeTabId = activeTab.replace('#tab', '');
        $.cookie('last_tab_id', activeTabId, {expires: 365});
        console.log('смена вкладки на ' + activeTab);
    });

    //удаление куки при логауте
    $('#logout_buttton').click(function () {
        //delete cookies
        $.cookie('last_tab_id', null, {expires: -1});
        $.cookie('last_category_id', null, {expires: -1});
    });

    //клик по кнопке добавления новой ссылки
    $('#add_new_link_button').click(function () {
        add_new_link();
    });

    // отображение и скрытие кнопок рефреш/едит/делете
    var thumbnail = $('.my_thumbnail');
    thumbnail.mouseover(function () {
        $(this).children('.my_close').removeClass('invisible');
    });
    thumbnail.mouseout(function () {
        $(this).children('.my_close').addClass('invisible');
    });

    //работа с куками
    //$.cookie('cookie_name', 'cookie_value', { expires: 365}); //add cookie
    //$.cookie('username', null, {expires: -1}); //delete cookie
    console.log( document.cookie );
    //console.log($.cookie('cookie_name'));  //get cookie

});
