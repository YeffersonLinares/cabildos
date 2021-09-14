$(document).ready(function(){
    $(".dropdown-submenu").mouseenter(function(){
        var submenu = $(this).find("ul:first");
        submenu.removeClass('subLeft');     // si tiene la clase la quita
        
        if(submenu.length == 1){
            var rightMenu = $('.horizontal-menu').offset().left + $('.horizontal-menu').width();        // tamaño total del menu
            var rightSubmenu = submenu.offset().left + submenu.outerWidth();    // tamaño del hijo
            
            if(rightSubmenu > rightMenu){
                submenu.addClass('subLeft');                                    // agrega la clase para mostrarse a la izquierda
            }
        }

    });
});
