
function getXMLHttpRequest()
{
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    }

    return new ActiveXObject('Microsoft.XMLHTTP');
}

function getLink() {
    request = getXMLHttpRequest();
    request.onreadystatechange = function() {
        console.log(request.readyState);
        if (request.readyState == 4) {
            responseBody = request.responseText;
            console.log(responseBody);
            data = JSON.parse(responseBody);
            console.log(data);
            alert(data['message']);
        }
    }

    request.open('GET', '/pages/test1', true);
    request.send(null);
}

function get_link_data(id) {

    //alert(id);

    console.log(getXMLHttpRequest());

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

    $("#editModal").modal('show');

    return false;
}

function add_new_link() {

    link = $("#link").val();
    title = $('#title').val();
    //console.log(link+' '+title);

    var data = {'link' : link, 'title' : title};
    console.log(data);



    request = getXMLHttpRequest();
    request.onreadystatechange = function() {
        console.log(request.readyState);
        if (request.readyState == 4) {
            responseBody = request.responseText;
            console.log(responseBody);
        }
    }
    request.open('POST', 'script.php', true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(data);

    // jQuery
    //$.post('script.php', data, function (data) {
    $.post('/links/link_add/', data, function (data) {
        console.log(data);
    });

    $('#registerModal').modal('hide');

}