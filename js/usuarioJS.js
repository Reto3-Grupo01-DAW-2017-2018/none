/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(inicio);

function inicio()
{
    modalDefault();

    correo();
    alias();
    comprobarContraseña();
    enviar();
}
/**
 *
 * @returns {undefined}
 * Funcion para comprobar que el email este correcto, y no exista en la bd
 */
function correo ()
{

    var cAntiguo=$('[name=email]').val();
    $('[name=email]').on('blur',function()
    {

        var datos= $(this).serialize();
        //var reg = /^[a-zA-Z0-9]+[@]{1}[a-zA-Z]+[.]{1}[a-zA-Z]+$/;
        var reg=/^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(.[a-zA-Z0-9-]+)*.[a-z]{2,4}$/;
        var resultado;
        if( !$(this).val().match(reg) )
        {
            $('#emailOK').val(0);
            $('#feedEmilio').html('<div class="alert alert-danger" role="alert"> <strong>Warning!</strong>Correo no valido.</div>');
        }
        else
        {
            if(cAntiguo==$(this).val())
            {
                $('#emailOK').val(1);
                $('#feedEmilio').html('<div class="alert alert-info" role="alert"><strong>Tranquilo!</strong> Campo aún sin modificar.');
            }
            else
            {
                $.ajax
                ({
                    type: 'POST',
                    url: '/../none/index.php?controller=Usuario&action=correo',
                    data: datos,
                    success: function (data)
                    {
                        resultado=data;
                        if(resultado==true)
                        {
                            resultado=1;
                            $('#emailOK').val(resultado);
                            $('#feedEmilio').html('<div class="alert alert-success" role="alert"><strong>SUCCESS!</strong> Correo libre.</div>');
                        }
                        else
                        {
                            resultado=0;
                            $('#emailOK').val(resultado);
                            $('#feedEmilio').html('<div class="alert alert-danger" role="alert"> <strong>Warning!</strong>Correo en uso.</div>');
                        }

                    },
                    error: function (error)
                    {
                        $('#emailOK').val(0);
                        alert("error del servidor");
                        console.log('Llamada Oo--> '+error);
                    }
                });
            }
        }
    });
}
/**
 *
 * @returns {undefined}
 * Funcion para comprobar que el alias este correcto, y no exista en la bd
 */
function alias ()
{
    var aAntiguo=$('[name=username]').val();
    $('[name=username]').on('blur',function()
    {

        var datos= $(this).serialize();
        //var reg = /^[a-zA-Z0-9]+[@]{1}[a-zA-Z]+[.]{1}[a-zA-Z]+$/;
        var reg=/^[a-zA-Z]{3}[a-zA-Z]+/;
        var resultado;
        if( !$(this).val().match(reg) )
        {
            $('#usernameOK').val(0);
            $('#feedAlias').html('<div class="alert alert-danger" role="alert"> <strong>Warning!</strong>Alias no valido.</div>');
        }
        else
        {
            if(aAntiguo==$(this).val())
            {
                $('#usernameOK').val(1);
                $('#feedAlias').html('<div class="alert alert-info" role="alert"><strong>Tranquilo!</strong> Campo aún sin modificar.</div>');
            }
            else
            {
                $.ajax
                ({
                    type: 'POST',
                    url: '/../none/index.php?controller=Usuario&action=username',
                    data: datos,
                    success: function (data)
                    {
                        resultado=data;
                        if(resultado==true)
                        {
                            resultado=1;
                            $('#usernameOK').val(resultado);
                            $('#feedAlias').html('<div class="alert alert-success" role="alert"><strong>SUCCES!</strong> Alias libre.</div>');
                        }
                        else
                        {
                            resultado=0;
                            $('#usernameOK').val(resultado);
                            $('#feedAlias').html('<div class="alert alert-danger" role="alert"> <strong>Warning!</strong>Alias en uso.</div>');
                        }

                    },
                    error: function (error)
                    {
                        $('#usernameOK').val(0);
                        alert("error del servidor");
                        console.log('Llamada Oo--> '+error);
                    }
                });
            }
        }
    });

}

/**
 *
 * Funcion para comprobar las contraseñas
 */
function comprobarContraseña()
{

    var pass1 = $('[name=password]');
    var pAntigua = pass1.val();
    var pass2 = $('[name=password2]');
    function coincide()
    {
        if(pass1.val() != pass2.val() )
        {
            $('#passwordOK').val(0);
            $('#feedContras').html('<div class="alert alert-danger" role="alert"> <strong>Warning!</strong> Las contraseñas no coinciden.</div>');
        }
        else if(pass1.val().length<6 || pass1.val().length>10)
        {
            $('#passwordOK').val(0);
            $('#feedContras').html('<div class="alert alert-danger" role="alert"><strong>Warning!</strong> Longitud incorrecta, minimo 6, maximo 10.</div>');
        }
        else
        {
            if(pAntigua==pass1.val())
            {
                $('#passwordOK').val(1);
                $('#feedContras').html('<div class="alert alert-info" role="alert"><strong>Tranquilo!</strong> Campo aún sin modificar.</div>');
            }
            else if(pass1.val().length!=0 && pass1.val()==pass2.val())
            {
                $('#passwordOK').val(1);
                $('#feedContras').html('<div class="alert alert-success" role="alert"><strong>SUCCES!</strong> Las contraseñas coinciden.</div>');
            }
        }
    }
    pass1.keyup
    (
        function ()
        {
            coincide();
        }
    );
    pass2.keyup
    (
        function ()
        {
            coincide();
        }
    );

}
/**
 *
 * @returns {undefined}
 * Funcion para crear la ventana modal
 *
 */
function modalDefault()
{
    (function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);
}
/**
 * Enviar
 */
function enviar()
{
    $('#register').on('submit',function (e)
    {
        var correoOK=$('[name=emailOK]').val();
        var userOK=$('[name=usernameOK]').val();
        var passOK=$('[name=passwordOK]').val();
        if(correoOK==1 && userOK==1 && passOK==1)
        {
            $.createModal({
                title:'Aciertos',
                closeButton:true,
                message: 'Datos ok, ahora se te redireccionara a loggin.',
                scrollable: false
            });

            return true;
        }
        else
        {
            $.createModal({
                title:'Algo va mal',
                closeButton:true,
                message: 'Revisa datos',
                scrollable: false
            });
            /**
             *
             * @type Number
             * for los loles(var i=0;i<20;i++)
             {
                 $.createModal({
                 title:'Algo va mal',
                 closeButton:true,
                 message: 'Revisa datos',
                 scrollable: false
             });
             }
             */

            e.preventDefault();
        }

        //alert("elemento.name="+ elemento.name + ", elemento.value=" + elemento.value);







    });
}


/**
 * Variacion
 *
 *
 *
 *
 *
 * function enviar()
 {
     $('#register').on('submit',function (e)
     {
         $.createModal({
                     title:'Fallos',
                     closeButton:true,
                     message: 'mansa',
                     scrollable: false
                 });
        $(this).find('input[type=hidden]').each(function() {
          var elemento=this;
          alert("elemento.name="+ elemento.name + ", elemento.value=" + elemento.value);

             if(this.value!=1)
             {



                 $.createModal({
                     title:'Fallos',
                     closeButton:true,
                     message: 'Revisa datos',
                     scrollable: false
                 });
                 e.preventDefault();

             }

                 $.createModal({
                     title:'Aciertos',
                     closeButton:true,
                     message: 'Datos ok, ahora se te redireccionara a loggin.',
                     scrollable: false
                 });
             return true;


         });
     });
 }
 *
 *
 function enviar()
 {
     $('#register').on('submit',function ()
    {
        $(this).find(':input').each(function() {
          var elemento= this;
          //alert("elemento.name="+ elemento.name + ", elemento.value=" + elemento.value);
             if(elemento.name=='emailOK'.value==1 && elemento.value==1 && elemento.name=='usernameOK' && elemento.value==1 && elemento.name=='passwordOK' && elemento.value==1)
             {
                 alert('caca');

             }
             else
             {
                 alert('coco');
                 alert("elemento.name="+ elemento.name + ", elemento.value=" + elemento.value);
             }

         });
    });
 }
 */