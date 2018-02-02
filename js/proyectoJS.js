
$(document).ready(eventos);

function eventos(){
    modal = new Modal("");
    $("#myModal>div>span").click(esconderModal,modal.ocultarModal);
    $(".eliminarButton").bind('click', { param: $(this) }, confirmModal);
}

function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}

function confirmModal(event){
    event.preventDefault();
    var ruta=event.target.parentElement.href;
    var text="Confirmar la acción";
    /*var tipo=event.target.value;
    if(tipo == 'eliminar') {
        var text="El proyecto se eliminara por completo,<br>¿Estás seguro?";
    }
    else {
        var text="Dejarás de participar en el proyecto,<br>¿Estás seguro?";
    }*/
    /*let textEliminar="El proyecto se eliminara por completo,<br>¿Estás seguro?";
    let textDejarProyecto="Dejarás de participar en el proyecto,<br>¿Estás seguro?";*/
    modal.setText(text);
    modal.setPath(ruta);
    modal.getModalConfirm();
}


