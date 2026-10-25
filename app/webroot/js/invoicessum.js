
/**************************
 * カンマ編集を行うFunction
 **************************/
/*
function toComma(obj){
  if((obj.value).trim().length != 0 && !isNaN(obj.value)){
    obj.value = Number(obj.value).toLocaleString();
  }
}
*/

/**************************
 * カンマ編集を解除するFunction
 **************************/
/*
function offComma(obj){
  var reg = new RegExp(",", "g");
  var chgVal = obj.value.replace(reg, "");
  if(!isNaN(chgVal)){
    obj.value = chgVal;  //値セット
    obj.select();        //全選択
  }
}

window.onload = function(){
	var elements = document.getElementsByClassName("number");
	for(var i=0;i<elements.length;i++){
		//elements[i].onfocus = function(){offComma(this);}
		elements[i].onblur = function(){toComma(this);}
	}
}
*/

/**************************
 * 現在不使用↑
 **************************/

$(function(){
	var tax = 0.1;
	var test = parseInt($('#InvoiceSubtotal').val());
	if (Number.isNaN(test)) {
		var total = 0;
	} else {
		var total = parseInt($('#InvoiceSubtotal').val()) + parseInt($('#InvoiceConsumptiontax').val());
	}
	var test2 = parseInt($('#InvoiceSubtotal2').val());
	if (Number.isNaN(test2)) {
		var total2 = 0;
	} else {
		var total2 = parseInt($('#InvoiceSubtotal2').val());
	}
	var total3 = 0;
	$('.price').on('change', function() {
		var pg = [];
		for (var i = 0; i <= $('.price').length; i++) {
			var output = $('#payment' + i);
			var q = $('#quantity' + i).val();
			var price = $('#price' + i).val();
			if (price != null) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			//console.log(i);
			var subtotal = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal += pg[j];
			}
			$('#InvoiceSubtotal').val(subtotal);
			var consumptiontax = Math.floor(subtotal * tax);
			$('#InvoiceConsumptiontax').val(consumptiontax);
			total = subtotal + consumptiontax;
			if (total2 != 0) {
				total3 = total + total2;
			} else {
				total3 = total;
			}
			$('#InvoiceTotal').val(total3);
		}
	});
	$('.price2').on('change', function() {
		var pg = [];
		$('.price2').each(function(i) {
			var output = $('#payment2' + i);
			var q = $('#quantity2' + i).val();
			var price = $('#price2' + i).val();
			$('#price2' + i).val(price);
			if (price != 0) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal2 = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal2 += pg[j];
			}
			$('#InvoiceSubtotal2').val(subtotal2);
			total2 = subtotal2;
			if (total != 0) {
				total3 = total + total2;
			} else {
				total3 = total2;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
	$('.quantity').on('change', function() {
		var pg = [];
		for (var i = 0; i <= $('.quantity').length; i++) {
			var output = $('#payment' + i);
			var q = $('#quantity' + i).val();
			var price = $('#price' + i).val();
			if (price != null) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal += pg[j];
			}
			$('#InvoiceSubtotal').val(subtotal);
			var consumptiontax = Math.floor(subtotal * tax);
			$('#InvoiceConsumptiontax').val(consumptiontax);
			total = subtotal + consumptiontax;
			if (total2 != 0) {
				total3 = total + total2;
			} else {
				total3 = total;
			}
			$('#InvoiceTotal').val(total3);
		}
	});
	$('.quantity2').on('change', function() {
		var pg = [];
		$('.quantity2').each(function(i) {
			var output = $('#payment2' + i);
			var q = $('#quantity2' + i).val();
			var price = $('#price2' + i).val();
			$('#price2' + i).val(price);
			if (price != 0) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal2 = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal2 += pg[j];
			}
			$('#InvoiceSubtotal2').val(subtotal2);
			total2 = subtotal2;
			if (total != 0) {
				total3 = total + total2;
			} else {
				total3 = total2;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
});
