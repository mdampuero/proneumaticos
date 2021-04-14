$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            height--;
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    })
});
function autoSlide(absoluteId, contadorSliderId){
     var target = document.getElementById(absoluteId);
     targetContador = document.getElementById(contadorSliderId);
     intervalo = setInterval(function(){         
     $(".bandera").fadeTo(100, 0.5);
     switch(target.style.left){
        case '-715px':
            $("#circulo_3").fadeTo(100, 1);
            target.style.left = '-1430px';
            targetContador.innerHTML = '3/4';
            break;
        case '-1430px':
            $("#circulo_4").fadeTo(100, 1);
            target.style.left = '-2145px';
            targetContador.innerHTML = '4/4';
            break;
        case '-2145px':
            $("#circulo_1").fadeTo(100, 1);
            target.style.left = '0px';
            targetContador.innerHTML = '1/4';
            break;
        default:
            $("#circulo_2").fadeTo(100, 1);
            target.style.left = '-715px';
            targetContador.innerHTML = '2/4';
            break;
        }
    }, 10000);
}
function abrir (url,alto,ancho,alineacion){ 
    if (alineacion == "centro"){
        altoPantalla = parseInt(screen.availHeight);
        anchoPantalla = parseInt(screen.availWidth);
        //Calculo en punto medio
        centroAncho = parseInt((anchoPantalla/2))
        centroAlto = parseInt((altoPantalla/2))
        // ubico la venta segun sus dimensiones
        laXPopup = centroAncho - parseInt((ancho/2))
        laYPopup = centroAlto - parseInt((alto/2))
    }else if (alineacion == "esquina"){
        laXPopup = 0
        laYPopup = 0
    }
    window.open(url,'','location=no, left ='+ laXPopup +',top='+ laYPopup +', menubar=no, scrollbars=yes, status=no, toolbar=no, resizable=yes, height='+alto+', width='+ancho+'') ;
}
function slideDiv(absoluteId, pixeles, idCirculo){
    clearInterval(intervalo);
    var target = document.getElementById(absoluteId);
    //target.style.webkitAnimation = 'none';
    //target.style.MozAnimation = 'none';
    target.style.left = pixeles;
    //var targetContador = document.getElementById(contadorSlider);
    //targetContador.innerHTML =  numero;
    $(".bandera").fadeTo(100, 0.6, function(){		
            targetCirculo = document.getElementById(idCirculo);
            targetCirculo.style.opacity = 1;
            });
	autoSlide(absoluteId);
}        
