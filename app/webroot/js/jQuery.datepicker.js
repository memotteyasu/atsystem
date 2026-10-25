$(function () {
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional["ja"]);
	var Format = 'yy-mm-dd';
	$('input.datepicker1').datepicker({
		dateFormat: 'yy-mm-dd',
		autoSize: 'true',
		showOtherMonths: true,
		selectOtherMonths: true,
		showButtonPanel: true,
		onClose: function(selectedDate) {
			$("input.datepicker2").datepicker("option", "minDate", selectedDate);
		}
	});
	$('input.datepicker2').datepicker({
		dateFormat: 'yy-mm-dd',
		autoSize: 'true',
		showOtherMonths: true,
		selectOtherMonths: true,
		showButtonPanel: true,
		onClose: function(selectedDate) {
			$("input.datepicker1").datepicker("option", "maxDate", selectedDate);
		}
	});
});