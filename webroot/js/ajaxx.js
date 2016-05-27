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