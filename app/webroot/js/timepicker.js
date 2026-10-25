$(function () {
	jQuery(function(){
		jQuery(document).ready(function(){
			jQuery('input.timepicker').timepicker({
				timeFormat: 'HH:mm',
				interval: 30,
				//defaultTime: 'now',
				startTime: '0',
				dynamic: false,
				scrollbar: true
			});
		})
	});
});
