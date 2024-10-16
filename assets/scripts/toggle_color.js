$(document).ready(function() {
	$('#toggleColor').on('click', function() {
		var currentBgColor = $('body').css('background-color');
		var currentTextColor = $('body').css('color');
	
		$('body').css({
			'background-color': currentTextColor,
			'color': currentBgColor
		});
		$('nav a').css({
			'color': currentBgColor
		});
	});
});