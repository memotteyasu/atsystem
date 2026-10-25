$(function () {
	jQuery(function(){
		jQuery(document).ready(function(){
			jQuery('input.timepicker2').timepicker({
				timeFormat: 'HH:mm',
				interval: 30,
				minTime: '00:00',
				maxTime: '23:45',
				//defaultTime: 'now',
//				startTime: '0',
				dynamic: false,
				scrollbar: true
			});
		})
	});
});