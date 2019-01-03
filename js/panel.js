
$( document ).ready(function() {

    makeItRain();
    createFormCheck();
    $(".display-content").click( collapseFolder );
    $(".reset").click( function() { hideForm($(this)) } );
    $("i").click( function() { sendPostReq($(this).attr("class").substring(4), $(this)); } );
    $("#destroy").click( function() { $.redirect('admin.php', { destroy : true }); } );

});

/*
    Cuando se hace click en alguna carpeta, esta muestra el contenido,
    y cambia el icono por el de una carpeta abierta.
*/
function collapseFolder() {

    var folderitem = $(this).children()[0];
    var textitem = $(this).children()[1];
    var marginParent = parseInt($( folderitem ).css("marginLeft"));
    var nextItem = $($($(this).closest("div.rowin")).next()).children();
    

    if($(folderitem).hasClass("fa-folder")) {
        $(folderitem).addClass('fa-folder-open').removeClass('fa-folder');
        $(textitem).addClass('name-folder-open').removeClass('name-folder');

        for(var i = 0; i < nextItem.length; i++) {
            if($($(nextItem[i]).children()[0]).hasClass("rowin")) {
                $(nextItem[i]).find('i.inside').css("marginLeft", (marginParent + 30) + "px");
            }
        }
        
    } else {
        $(folderitem).addClass('fa-folder').removeClass('fa-folder-open');
        $(textitem).addClass('name-folder').removeClass('name-folder-open');
    }

}

/*
    Envia una peticion por AJAX al panel de admin, que es el que se encarga de procesarla,
    envia una peticion distinta segun el icono que se haya pulsado.
*/
function sendPostReq(type, itm) {

    switch(type) {

        case "fa-file-download":
            $.redirect('admin.php', { id_file_dw : itm.attr("id") });
            break;

        case "fa-trash-alt":
            $.redirect('admin.php', { id_file_rm : itm.attr("id") });
            break;

        case "fa-file-medical":
            $(itm).parent().addClass('hide').removeClass('show');
            $($(itm).parent()).prev().addClass('show').removeClass('hide');
            break;
        
        case "fa-folder-plus":
            var nom = prompt("Introduce el nombre de la nueva carpeta: ");
            $.redirect('admin.php', { cte_new_folder : nom, prnt : itm.attr("id") });
            break;
        
        case "fa-folder-minus":
            var confirmacion = confirm("En caso de que la carpeta contenga archivos, desea borrarlos (de lo contrario, no se realizaran cambios.)?");
            $.redirect('admin.php', { cte_rm_folder : itm.attr("id"), confirm : confirmacion });
            break;

        default:
            break;

    }
}

/*
    Oculta de nuevo el form correspondiente, y vuelve a mostrar las opciones.
*/
function hideForm(itm) {

    var prnt = $($(itm).parent()).parent();
    $(prnt).addClass('hide').removeClass('show');
    ($(prnt).next()).addClass('show').removeClass('hide');

}

/*
    Se encarga de mostrar al usuario el numero de archivos seleccionados en caso de ser varios,
    y el nombre del archivo seleccionado, en caso de ser uno.

    Code by Osvaldas Valutis, www.osvaldas.info
*/
function createFormCheck() {

    var inputs = document.querySelectorAll( '.inpUser' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Areglados los errores de firefox
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
        input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
    });
    
}

function makeItRain() {

    var COUNT = 300;
    var masthead = document.querySelector('.sky');
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    var width = masthead.clientWidth;
    var height = masthead.clientHeight;
    var i = 0;
    var active = false;
  
    function onResize() {
      width = masthead.clientWidth;
      height = masthead.clientHeight;
      canvas.width = width;
      canvas.height = height;
      ctx.fillStyle = '#FFF';
  
      var wasActive = active;
      active = width > 600;
  
      if (!wasActive && active)
        requestAnimFrame(update);
    }
  
    var Snowflake = function () {
      this.x = 0;
      this.y = 0;
      this.vy = 0;
      this.vx = 0;
      this.r = 0;
  
      this.reset();
    }
  
    Snowflake.prototype.reset = function() {
      this.x = Math.random() * width;
      this.y = Math.random() * -height;
      this.vy = 1 + Math.random() * 3;
      this.vx = 0.5 - Math.random();
      this.r = 1 + Math.random() * 2;
      this.o = 0.5 + Math.random() * 0.5;
    }
  
    canvas.style.position = 'absolute';
    canvas.style.left = canvas.style.top = '0';
  
    var snowflakes = [], snowflake;
    for (i = 0; i < COUNT; i++) {
      snowflake = new Snowflake();
      snowflake.reset();
      snowflakes.push(snowflake);
    }
  
    function update() {
  
      ctx.clearRect(0, 0, width, height);
  
      if (!active)
        return;
  
      for (i = 0; i < COUNT; i++) {
        snowflake = snowflakes[i];
        snowflake.y += snowflake.vy;
        snowflake.x += snowflake.vx;
  
        ctx.globalAlpha = snowflake.o;
        ctx.beginPath();
        ctx.arc(snowflake.x, snowflake.y, snowflake.r, 0, Math.PI * 2, false);
        ctx.closePath();
        ctx.fill();
  
        if (snowflake.y > height) {
          snowflake.reset();
        }
      }
  
      requestAnimFrame(update);
    }

    window.requestAnimFrame = (function(){
      return  window.requestAnimationFrame       ||
              window.webkitRequestAnimationFrame ||
              window.mozRequestAnimationFrame    ||
              function( callback ){
                window.setTimeout(callback, 1000 / 60);
              };
    })();
  
    onResize();
    window.addEventListener('resize', onResize, false);
  
    masthead.appendChild(canvas);
}