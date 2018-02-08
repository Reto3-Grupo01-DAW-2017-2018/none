/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(inicio);

function inicio()
{
    correo();
    alias();
    comprobarContraseña();
    enviar();
    cookies();
}

function cookies(){
    if($.cookie('not_existing')==null){
        modal = new Modal("Esta página usa cookies, navegando en ella estas aceptando su uso");
        modal.getModal();
        $.cookie('not_existing', 'true', { expires: 7 });
    }
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
                    url: '/../nonecollab/index.php?controller=Usuario&action=correo',
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
                    url: '/../nonecollab/index.php?controller=Usuario&action=username',
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
 * Enviar
 */
function enviar()
{
    $('#userFormu').on('submit',function (e)
    {
        var correoOK=$('[name=emailOK]').val();
        var userOK=$('[name=usernameOK]').val();
        var passOK=$('[name=passwordOK]').val();
        if(correoOK!=1 || userOK!=1 || passOK!=1)
        {
            e.preventDefault();
            modalUsu = new Modal("Revisa datos");
            modalUsu.getModal();
        }
    });
}

