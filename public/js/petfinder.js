function deletePhoto(photoName) {
    let response = ajaxCall('/deletePhoto/'+photoName);
    if(response.status == 204) {
        document.getElementById(photoName).style.display = "none";
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
            return ajax;
        }
    }catch(e){
        return false;
    }
    ajax.open("POST", route, false);
    ajax.send();
    return ajax;
}
