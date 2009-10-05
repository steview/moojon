$(function(){
	$('.call').hide();
	$('#exception_report_h1').click(function(){$('.call').toggle();});
	$('.line').click(function(){$('#'+this.id+'source').toggle();});
});