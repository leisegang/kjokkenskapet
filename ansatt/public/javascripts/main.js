
$(document).ready( function() {
	
	$("a.delete").click( function(e) {
		e.preventDefault();
		
		var response = confirm("Er du sikker p\u00E5 at du vil slette dette elementet?");
		
		if (response == true) {
			document.location = $(this).attr("href");
		}
	});
	
	$('.date').datepicker({
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		monthNames: ['Januar','Februar','Mars','April','Mai','Juni', 'Juli','August','September','Oktober','November','Desember'],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
		weekHeader: 'U',
		dayNames: ['S&oslash;ndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'L&oslash;rdag'],
		dayNamesShort: ['S&oslash;n', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'L&oslash;r'],
		dayNamesMin: ['S&oslash;','Ma','Ti','On','To','Fr','L&oslash;']
	});
	
});