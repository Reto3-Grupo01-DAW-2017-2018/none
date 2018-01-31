$(document).ready(function() {
    // run test on initial page load
    checkSize();

    // run test on resize of the window
    $(window).resize(checkSize);
});

function openNav(){
    $(".openAside").css("display","none");
    $("aside").css({"display": "block", "position": "absolute","background-color":"lightgrey","right":"0px"});
    $(".closeAside").css({"display":"inline-block","zIndex":"1"});
}
function closeNav(){
    $(".openAside").css("display","inline-block");
    $("aside").css({"display": "none", "position": "static","background-color":"white"});
    $(".closeAside").css("display","none");
}
function checkSize(){
    if ($("aside").css("display") == "none" ){

    }
}