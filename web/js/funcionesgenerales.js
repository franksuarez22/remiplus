//Para habilitar filtro de busqueda en select2
$.fn.modal.Constructor.prototype._enforceFocus = function() {};

//Quita clase active a una pestaña en tabs
if ( $("ul.nav-tabs li a.nav-link").length > 0 ) {
    $("ul.nav-tabs li a.nav-link").removeClass("active");
    $("ul.nav-tabs li.nav-item:first").addClass("active");
}
