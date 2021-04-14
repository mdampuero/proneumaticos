// JavaScript Document

//******************************************
//IS EMPTY
//******************************************
function is_empty(field,message){
	if(field.value==""){
		field.className='input_error';
		field.focus();
		document.getElementById('label_error').innerHTML = message;
		return true;
	}else{
		field.className='';
		document.getElementById('label_error').innerHTML = '&nbsp;';
		return false;
	}
	
}

//******************************************
//SEND FORM
//******************************************
function send_form(form,message){
	
		document.getElementById('label_error').className = "enviando";		
		document.getElementById('label_error').innerHTML = message + "...";
		form.submit();	
	
}
function remove_element(element)
    {
        var o = document.getElementById(element);
        o.parentNode.removeChild(o);
    }
function refresh_object(id,url,message){
    var m=(message=='')?'Cargando...':message;
    document.getElementById(id).innerHTML=m;
    $("#"+id).load(url);	
    /* $.post("urls"+number_service, 
                { 
                    legend: "And a a la concha de tu hermana" }, function(data){
                    $("#div_"+number_service).html(data);
                }
            );*/
}
function confirmar( mensaje ) {
	return confirm( mensaje );
}


//******************************************
//IS VALID EMAIL
//******************************************

function is_valid_email(field,message){
	if(field.value!=""){
		var filter=/^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,4}$/;
	//	var filter=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/;
		if(!filter.test(field.value)){
			field.className='input_error';
			field.focus();
			document.getElementById('label_error').innerHTML = message;
			return true;
		}else{
			field.className='';
			return false;
		}
	}
}

//******************************************
//IS NUMBER
//******************************************
function is_number(field,message,direction){
	if((/^([0-9])*$/.test(field.value))){
		if(direction==true){
			field.className='input_error';
			field.focus();
			document.getElementById('label_error').innerHTML = message;
			return true;
		}else{
			field.className='';
			document.getElementById('label_error').innerHTML = '&nbsp;';
			return false;
		}
	}else{
		if(direction==false){
			field.className='input_error';
			field.focus();
			document.getElementById('label_error').innerHTML = message;
			return true;
		}else{
			field.className='';
			document.getElementById('label_error').innerHTML = '&nbsp;';
			return false;
		}
	}	
}


//******************************************
//IS ENTER
//******************************************

function is_enter(code){
    if(code.keyCode==13) validate_form();
}

function send_search(code,url){
    if(code.keyCode==13){
        location.href=url;
    }
}

//******************************************
//IS_INVALID_FILE
//recibe campo, array de extensiones validas, y un mensaje.
//******************************************

function is_invalid_file(field, extensions, message){
	
		var file_name=field.value.split(".");
		var posicion_extension=file_name.length - 1;
		var extension=file_name[posicion_extension].toUpperCase();
		
		var error = true;
		
		for(var i = 0; i < extensions.length; i++){
			if(extension == extensions[i]){
				error = false;
			}	
		}
		if(field.value=="" || error == false){
			field.className='';
			document.getElementById('label_error').innerHTML = '&nbsp;';
			return false;
		}else{			
			field.className='input_error';
			field.focus();
			document.getElementById('label_error').innerHTML = message;
			return true;
					
		}
	
}

//******************************************
//is_equal
//recive 2 valores, 1 obtenido y 1 predeterminado como limite, si son distintos falla.
//******************************************

function is_equal(valor, limite, message){
	if(valor == limite){
		document.getElementById('label_error').innerHTML = '&nbsp;';
		return false;
	}
	
	else{
		document.getElementById('label_error').innerHTML = message;
		return true;
	}
}

function is_pass_equal(field1, field2, message){
	
	if(field1.value == field2.value){				
		document.getElementById('label_error').innerHTML = '&nbsp;';
		return false;
		
	}else{		
		field1.className='input_error';
		field2.className='input_error';
		field1.focus();
		document.getElementById('label_error').innerHTML = message;
		return true;
	}
}

//******************************************************
    //PASAR ELEMENTOS DE UN SELECT A OTRO*******************
    //******************************************************
    var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);

    function addOption(theSel, theText, theValue){
    var newOpt = new Option(theText, theValue);
    var selLength = theSel.length;
    theSel.options[selLength] = newOpt;
    }
    function deleteOption(theSel, theIndex){ 
    var selLength = theSel.length;
    if(selLength>0){
        theSel.options[theIndex] = null;
    }
    }
    function moveOptions(theSelFrom, theSelTo){
    var selLength = theSelFrom.length;
    var selLengthTo = theSelTo.length;

            var selectedText = new Array();
            var selectedValues = new Array();
            var selectedCount = 0;
            var i;
            for(i=selLength-1; i>=0; i--){
                    if(theSelFrom.options[i].selected){
                    selectedText[selectedCount] = theSelFrom.options[i].text;
                    selectedValues[selectedCount] = theSelFrom.options[i].value;
                    deleteOption(theSelFrom, i);
                    selectedCount++;
                    }
            }
            for(i=selectedCount-1; i>=0; i--){
                    addOption(theSelTo, selectedText[i], selectedValues[i]);
            }
            if(NS4) history.go(0);

    }
    
    function arriba(idTarget) {
	obj=document.getElementById(idTarget);
	indice=obj.selectedIndex;
	if (indice>0) cambiar(obj,indice,indice-1);
}
    function abajo(idTarget) {
            obj=document.getElementById(idTarget);
            indice=obj.selectedIndex;
            if (indice!=-1 && indice<obj.length-1)
                    cambiar(obj,indice,indice+1);
    }
function cambiar(obj,num1,num2) {
	proVal=obj.options[num1].value;
	proTex=obj.options[num1].text;
	obj.options[num1].value=obj.options[num2].value;	
	obj.options[num1].text=obj.options[num2].text;	
	obj.options[num2].value=proVal;
	obj.options[num2].text=proTex;
  obj.selectedIndex=num2;
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

/*
 *
 *
 */
function toggleDiv(idDiv, source, toNone){
    target = document.getElementById(idDiv);
    noneTraget = document.getElementById(toNone);
    $(source).fadeOut(500);
    $(noneTraget).fadeOut(500, function(){
        $(target).fadeIn(500);
    });
}

/*
 *
 *
 **/

function showCategory(idDiv){
    target = document.getElementById(idDiv);
        $(".info").slideUp(0);
        $(target).fadeIn(500);
}

function hideVideos(){
    $('.video').fadeOut(400);
}