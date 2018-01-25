$(document).ready(eventos);

function eventos(){
    $("#subirArchivos").click(comprobarArchivos);
    $("span.close").click(ocultarModal);
}

function modalDefault()
{
    (function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);
}

function ocultarModal(){
    $("#myModal").css("display","none");
    $("#myModal").html('');
}

function comprobarArchivos(evt) {
    var files = document.getElementById('archivos').files;
    // comprobamos si hay archivos seleccionados
    if(files.length>0) {
        var upload=new Upload(files[0]);
        upload.doUpload();
        /*$("#formArchivos").attr("action","index.php?controller=archivo&action=nuevoArchivo");
        $("#formArchivos").submit();
        *//*
        var archivos=jQuery.parseJSON(files);
        $.ajax({
            type: 'POST',
            url: 'index.php?controller=archivo&action=nuevoArchivo',
            data: archivos,
            success: function (data)
            {
                resultado=data;
                if(resultado==true) {

                }
                else {
                    $("#myModal").html('' +
                        '    <div class="modal-content">\n' +
                        '        <span class="close glyphicon glyphicon-remove"></span>\n' +
                        '        <p>Error, no se ha podido completar la subida del archivo o ya existe un archivo con ese nombre en el servidor.</p>\n' +
                        '    </div>');
                    $("#myModal").css("display","block");
                }

            },
            error: function (error)
            {
                alert("Error del servidor, vuelve a intentarlo mas tarde o contacte con nuestro soporte: nonesoporte@viweb.corp");
                console.log('Error: '+error);
            }
        });*/
    }else{
        $("#myModal").html('' +
            '    <div class="modal-content">\n' +
            '        <span class="close glyphicon glyphicon-remove" onclick="ocultarModal()"></span>\n' +
            '        <p>No has seleccionado ningun archivo</p>\n' +
            '    </div>');
        $("#myModal").css("display","block");
    }
}