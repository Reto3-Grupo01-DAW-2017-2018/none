/*$(document).ready(pruebasas);

function pruebasas(){
    $(".openAside>span").click(function(){
        $("aside").slideToggle();
        $(".closeAside").show(1000);
        $(".openAside").hide(1000);
    });
    $(".closeAside>span").click(function(){
        $("aside").slideUp(1001);
        $(".closeAside").hide(1000);
        $(".openAside").show(1000);
    });
}*/


function openNav(){
    $("body").append("<style type='text/css'>@media screen and (max-width: 775px) {" +
        "aside{display: block; position: absolute;background-color:lightgrey;right:0px;max-width:250px;border-bottom: 1px solid black; border-top: 1px solid black; }" +
        ".openAside{display:none;}" +
        ".closeAside{display:inline-block;zIndex:1} }</style>");
}
function closeNav(){
    $("body").append("<style type='text/css'>@media screen and (max-width: 775px) {" +
        "aside{display: none; position: static;background-color:white;}" +
        ".openAside{display:inline-block;}" +
        ".closeAside{'display':'none';} }</style>");
}

