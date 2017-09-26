$(document).ready(function() {


	/* GESTION DU COLLAPSE POUR LE MENU PRINCIPAL */
	$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });


	/* RENDRE UNE LIGNE D'UN TABLEAU CLICKABLE ET FAIRE LA REDIRECTION */
	$('tr.clickrow').on('click', function(){
		var url = $(this).data('url');
		console.log(url);
		window.document.location = url;
	});

});