$(function() {
    $('#tabs > li').each(function() {
        var z_index = $(this).length - $(this).index();
        $(this).css({
            'background': $(this).attr('vc-code'),
            'z-index': z_index + 1
        });

        var c_color = $(this).attr('vc-code');
        $('div:nth-child(' + ($(this).index() + 1) + ') > .main').css('background', c_color);
    });


    $('#tabs > li').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $('.tab-panes > div#' + $(this).attr('title')).addClass('showit').siblings().removeClass('showit');
    });
    $('.tab-panes > div > div:not(.main)').each(function(){
        var shadelev = ($(this).siblings('.main').index() - $(this).index()) * 5;
        var shade = $(this).siblings('.main').css('background');
        shade = rgb2hex(shade);
        var newshade = LightenDarkenColor(shade, shadelev);
        $(this).css('background', newshade);
    });

    $('.tab-panes > div > div').each(function() {
        var code = $(this).css('background');
        code = rgb2hex(code);
        $(this).append('<a href="#">' + code + '</a>');
    });
});

function rgb2hex(rgb) {
    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
    return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
}

function LightenDarkenColor(col, amt) {
    var usePound = false;
    if (col[0] == "#") {
        col = col.slice(1);
        usePound = true;
    }
    var num = parseInt(col, 16);
    var r = (num >> 16) + amt;
    if (r > 255) r = 255;
    else if (r < 0) r = 0;
    var b = ((num >> 8) & 0x00FF) + amt;
    if (b > 255) b = 255;
    else if (b < 0) b = 0;
    var g = (num & 0x0000FF) + amt;
    if (g > 255) g = 255;
    else if (g < 0) g = 0;
    return (usePound ? "#" : "") + String("000000" + (g | (b << 8) | (r << 16)).toString(16)).slice(-6);
}

/*El codigo de arriba es de una paleta de codepen, editado y modificado para el uso en nuestro página*/

$(document).ready(eventos);

function eventos(){
    cookieColorPrincipal();
    cookieColorFontPrincipal();
    $("#divColor>div>div>a").bind('click', { param: $(this) }, cambiarColorPicked);
    $("#botonAceptarColor").click(comprobarColor);
    $(".toggle input").click(cambiarFontColor);
}

function cambiarColorPicked(event){
    var colorPicked=$(event.target).text();
    $("#colorSelected").css({"background-color":colorPicked})
    $("#colorSelected > p").text(colorPicked);
}


function cambiarFontColor(event){
    if($(".toggle input:checked").length>0){
        $("#colorSelected > p").css("color","black");
    }else{
        $("#colorSelected > p").css("color","white");
    }
}

function comprobarColor (){
    if ($("#colorSelected > p").text()==""){
        alert("Elige un color");
    }else{
        var colorSelected=$("#colorSelected > p").text();
        document.cookie="colorNav="+encodeURIComponent(colorSelected)+";max-age=86400";
        //Cookies.set("colorFondo",colorSelected);
        cookieColorPrincipal();
        if($(".toggle input:checked").length>0){
            document.cookie="colorNavFont="+encodeURIComponent("black")+";max-age=86400";
            cookieColorFontPrincipal();
        }else{
            document.cookie="colorNavFont="+encodeURIComponent("white")+";max-age=86400";
            cookieColorFontPrincipal();
        }
    }
}

function cookieColorPrincipal(){
    var color=readCookie('colorNav');
    if(color!=null){
        $("#navPrincipal,footer>div,.dropdown-menu>li>a,.dropdown-menu").css("background-color",color);
    }
}

function cookieColorFontPrincipal(){
    var color=readCookie('colorNavFont');
    if(color!=null){
        $("#navPrincipal>li>a,footer>div,.dropdown-menu>li>a").css("color",color);
    }
}

function readCookie (name) {

    var nameEQ = name + "=";
    var ca = document.cookie.split (';');

    // El método split genera un array a partir de un string

    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt (0)==' ')
            c = c. substring (1,c.length);
        if (c.indexOf(nameEQ) == 0) {
            return decodeURIComponent ( c. substring (nameEQ.length ,c.length)
            );
        }
    }
    return null;
}