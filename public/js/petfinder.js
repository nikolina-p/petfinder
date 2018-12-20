function deletePhoto(photoName) {
    let response = ajaxCall('/deletePhoto/'+photoName);
    if(response) {

    }

}

function ajaxCall(route){
    let ajax = null;
    let response = "";
    if(window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        ajax = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        ajax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    try{
        ajax.onreadystatechange = function(){
            if(ajax.readyState == 4 && ajax.status == 200){
                response = ajax.responseText;
            }
        }
    }catch(e){
        return false;
    }
    ajax.open("POST", route, false);
    ajax.send();
    return response;
}