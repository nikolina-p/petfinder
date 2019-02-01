function deletePhoto(photoName) {
    let response = ajaxCall('/photo/delete/'+photoName);
    if (response.status == 204) {
        document.getElementById(photoName).style.display = "none";
    }
}

function deletePet(petId) {
    let response = ajaxCall('/pet/delete/'+petId);
    if (response.status == 204) {
        window.location.replace('/');
        return;
    }
}

function deleteSpecies(speciesId) {
    let response = ajaxCall('/species/delete/'+speciesId);
    if (response.status == 204) {
        window.location.replace('/species');
        return;
    }
}

function ajaxCall(route){
    let ajax = null;
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
