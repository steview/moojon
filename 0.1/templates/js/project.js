$(function(){
	$('body').removeClass('js-disabled').addClass('js-enabled');
	
	$('#flash_banner').flash({
		src: "flash/banner.swf",
		width: 960,
		height: 100
	});
	
});