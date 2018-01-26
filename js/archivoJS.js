$(document).ready(eventos);

function eventos(){
    modal = new Modal("");
    $("#subirArchivos").click(comprobarArchivos);
    $("#myModal>div>span").click(esconderModal,modal.ocultarModal);
    //$("#myModal>div>span").bind('click', modal.ocultarModal);
}
/*
function ocultarModal(){
    $("#myModal").css("display","none");
    $("#myModal").html('');
}*/

function comprobarArchivos(evt) {
    var files = document.getElementById('archivos').files;
    // comprobamos si hay archivos seleccionados
    if(files.length>0) {
        for(let x=0;x<files.length;x++){
            var upload=new Upload(files[x]);
            upload.doUpload();
        }
        /*$("#formArchivos").attr("action","index.php?controller=archivo&action=nuevoArchivo");
        $("#formArchivos").submit();
        *//*
        var archivos=jQuery.parseJSON(files);
        $.ajax({
            type: 'POST',
            url: 'index.php?controller=archivo&action=nuevoArchivo',
            data: archivos,
            success: function (data){
                resultado=data;
                if(resultado==true){

                }
                else{
                    $("#myModal").html('' +
                        '    <div class="modal-content">\n' +
                        '        <span class="close glyphicon glyphicon-remove"></span>\n' +
                        '        <p>Error, no se ha podido completar la subida del archivo o ya existe un archivo con ese nombre en el servidor.</p>\n' +
                        '    </div>');
                    $("#myModal").css("display","block");
                }

            },
            error: function (error){
                alert("Error del servidor, vuelve a intentarlo mas tarde o contacte con nuestro soporte: nonesoporte@viweb.corp");
                console.log('Error: '+error);
            }
        });*/
    }else{
        let text="No has seleccionado ningún archivo!";
        modal.setText(text);
        modal.getModal();
    }
}
function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}
