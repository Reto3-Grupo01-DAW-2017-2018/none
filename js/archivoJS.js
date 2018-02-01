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
        }
        cargarArchivos();
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
        dataType:"text",
        timeout: 60000,
        success: function (data) {
            var jsonData=JSON.parse(data);
            //alert(jsonData);
            //aqui se trata los datos
            if(Object.keys(jsonData).length<=0){
                $("#archivosSection").html(''+
                    '<div class="mensajeProyecto">'+
                        '<h5>El proyecto '+nombreProyecto+' no tiene ningun archivo subido</h5>'+
                    '</div>');
            }else{
                $("#archivosSection").html(''+
                    '<a class="btn btn-info btn-sm" id="descargarArchivos" >Descargar archivos <span class="glyphicon glyphicon-download"></span></a>'+
                    '<form id="formListaArchivos" method="post">'+
                        '<table class="table col-lg-12">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th scope="col">ID</th>'+
                                    '<th scope="col">Nombre</th>'+
                                    '<th scope="col">Subido por</th>'+
                                    '<th scope="col"></th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>'+
                            '</tbody>'+
                        '</table>'+
                    '</form>');
                for(let x=0;x<Object.keys(jsonData).length;x++){
                    $("#formListaArchivos>table>tbody").append(""+
                        "<tr>"+
                            "<td>"+jsonData[x]["idArchivo"]+"</td>"+
                            "<td>"+jsonData[x]["nombreArchivo"]+"</td>"+
                            "<td>"+jsonData[x]["username"]+"</td>"+
                            "<td>"+
                            if(){}
                                "<a href='index.php?controller=archivo&action=eliminar&idArchivo="+jsonData[x]["idArchivo"]+"&nombreArchivo="+jsonData[x]["nombreArchivo"]+"&proyecto="+idProyecto+"&nombreProyecto="+nombreProyecto+"'>"+
                                    "<button type='button' class='eliminarButton btn btn-danger btn-sm'>Eliminar</button>"+
                                "</a>"+
                            }
                            "</td>"+
                        "</tr>");
                }
            }

        },
        error: function (error) {
            let texto="Error, no se ha podido conectar con el servidor "+error;
            modal.setText(texto);
            modal.getModal();
        }
    });
}