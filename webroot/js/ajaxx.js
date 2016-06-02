
function getXMLHttpRequest()
{
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    }

    return new ActiveXObject('Microsoft.XMLHTTP');
}

// данные о ссылке для окна редактирования ссылки
function link_edit(id) {
/*
    request = getXMLHttpRequest();
    request.onreadystatechange = function() {
        console.log(request.readyState);
        if (request.readyState == 4) {
            responseBody = request.responseText;
            console.log(responseBody);
            data = JSON.parse(responseBody);
            console.log(data);
            //alert(data['url']);
            $("#new_link").attr('value',data['url']);
            $("#new_title").attr('value',data['title']);
            $("#link_id").attr('value',id);
        }
    }

    request.open('GET', '/links/getLinkData/'+id, true);
    request.send(null);
*/

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