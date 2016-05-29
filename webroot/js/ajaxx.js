
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

    link = ($("#link").val()); //преобразовать!!!!!!!!! ??
    title = $('#title').val();
    console.log(link+' '+title);

    //Передавать в пост запросе???

    request = getXMLHttpRequest();

    request.onreadystatechange = function() {
        console.log(request.readyState);
        responseBody = request.responseText;
        console.log(responseBody);
    }

    request.open('GET', '/links/addNewLink/'+link+'/'+title, true);
    request.send(null);

}