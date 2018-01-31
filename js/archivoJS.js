$(document).ready(eventos);
$(document).load(cargarArchivos);
function eventos(){
    modal = new Modal("");
    $("#subirArchivos").click(comprobarArchivos);
    $("#descargarArchivos").click(download);
    //$("#myModal>div>span,#myModal>div>div>a").click(esconderModal);
    $(".eliminarButton").bind('click', { param: $(this) }, confirmModal);
    //$(".eliminarButton").click(confirmModal);
}

function comprobarArchivos(evt) {
    var files = document.getElementById('archivos').files;
    // comprobamos si hay archivos seleccionados
    if(files.length>0) {
        for(let x=0;x<files.length;x++){
            var upload=new Upload(files[x]);
            upload.doUpload();
            cargarArchivos();
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
function confirmModal(event){
    event.preventDefault();
    var ruta=event.target.parentElement.href;
    let text="El archivo se eliminara por completo,<br>¿Estas seguro?";
    modal.setText(text);
    modal.setPath(ruta);
    modal.getModalConfirm();
}

function cargarArchivos(){
    let text="cargarArchivos";
    let idProyecto= $("#idProyecto").val();
    let nombreProyecto= $("#nombreProyecto").val();
    $.ajax({
        type: "POST",
        url: "/../nonecollab/index.php?controller=archivo&action=cargarArchivos&idProyecto="+idProyecto,
        data: text,
        dataType:"json",
        timeout: 60000,
        success: function (data) {
            alert(data);
            //aqui se trata los datos
            var listaArchivos=data;
            $("#formListaArchivos>table>tbody").html("");
            for(let x=0;x>listaArchivos.length;x++){
                $("#formListaArchivos>table>tbody").append(""+
                    "<tr>"+
                        "<td>"+listaArchivos[x]["idArchivo"]+"</td>"+
                        "<td>"+listaArchivos[x]["nombreArchivo"]+"</td>"+
                        "<td>"+listaArchivos[x]["username"]+"</td>"+
                        "<td>"+
                            "<a href='index.php?controller=archivo&action=eliminar&idArchivo="+listaArchivos[x]["idArchivo"]+"&nombreArchivo="+listaArchivos[x]["nombreArchivo"]+"&proyecto="+idProyecto+"&nombreProyecto="+nombreProyecto+"'>"+
                                "<button type='button' class='eliminarButton btn btn-danger btn-sm'>Eliminar</button>"+
                            "</a>"+
                        "</td>"+
                    "</tr>");
            }

        },
        error: function (error) {
            let texto="Error, no se ha podido conectar con el servidor "+error;
            modal.setText(texto);
            modal.getModal();
        }
    });
}