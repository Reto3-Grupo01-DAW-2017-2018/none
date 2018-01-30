$(document).ready(eventos);

function eventos(){
    modal = new Modal("");
    $("#subirArchivos").click(comprobarArchivos);
    $("#descargarArchivos").click(download);
    //$("#myModal>div>span,#myModal>div>div>a").click(esconderModal);
    $(".eliminarButton").click(confirmModal);
}

function comprobarArchivos(evt) {
    var files = document.getElementById('archivos').files;
    // comprobamos si hay archivos seleccionados
    if(files.length>0) {
        for(let x=0;x<files.length;x++){
            var upload=new Upload(files[x]);
            upload.doUpload();
        }
    }else{
        let text="No has seleccionado ningún archivo!";
        modal.setText(text);
        modal.getModal();
    }
}

function download(){
    let idProyecto= $("#idProyecto").val();
    let nombreProyecto= $("#nombreProyecto").val();
    let form = document.getElementById('formListaArchivos');
    if($('#formListaArchivos input').is(':checked'))
    {
        form.setAttribute("action", "index.php?controller=archivo&action=descargarArchivosSelect&proyecto="+idProyecto+"&nombreProyecto="+nombreProyecto);
    }else {
        form.setAttribute("action", "index.php?controller=archivo&action=descargarArchivos&proyecto="+idProyecto+"&nombreProyecto="+nombreProyecto);
    }
    $('#formListaArchivos').submit();
}

function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}
function confirmModal(){
    event.preventDefault();
    let text="El archivo se eliminara por completo,<br>¿Estas seguro?";
    modal.setText(text);
    let ruta=$("#enlaceDelete").attr('href');
    modal.setPath(ruta);
    modal.getModalConfirm();
}