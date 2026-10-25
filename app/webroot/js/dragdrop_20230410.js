/* eslint-env browser */
/* eslint
   semi: ["error", "always"],
   indent: [2, "tab"],
   no-tabs: 0,
   no-multiple-empty-lines: ["error", {"max": 2, "maxEOF": 1}],
   one-var: ["error", "always"] */
/* global REDIPS */

/* enable strict mode */
'use strict';


/*

This code looks a bit complicated but it's a good example of how to use some built-in event handlers.
The most complicated part is to shift orange boxes on the right side.
This DIV elements are unmovable and should follow their counterparts on the left side.
Result value and value of the left box are saved in class name in a format n(n+).
For example, result box could have class n12 or left box could have class n4.
Number from class is read and displayed when user
clicks on DIV element.

*/

// create redips container
// it should be var (not "let") because double click on "?" will not work
var redips = {};
var previousTd = null;
var previousTd2 = null;
// redips initialization
redips.init = function () {

	// reference to the REDIPS.drag class
	let rd = REDIPS.drag;
	// set initial math operation and application mode
	redips.operation = 'addition';
	redips.mode = 'mode1';
	// REDIPS.drag initialization
	rd.init();
	// set shift mode drop mode
	// rd.dropMode = 'single';
    rd.dropMode = 'multple';
	// set shift properties
	rd.shift.mode = 'vertical2';	// set vertical shift (each column is treated separately)
	rd.shift.overflow = 'delete';	// overflowed element will be deleted
	rd.shift.animation = true;		// shift animation (shift animation must be turned on because moveObject uses animation)
	// event handler called in a moment before DIV is dropped (create result box in the most right column)
	rd.event.droppedBefore = function (targetTD) {
		// console.log(targetTD);
	}
	rd.event.draged = function () { // 移動時に現時点でのテーブルセルを取得する
//		previousTd = rd.td.current;
		previousTd2 = rd.td.current.outerHTML;
		// console.log(previousTd);
	};
	rd.event.dropped = function (targetTD) {

//		redips.obj = rd.obj;
//		var cname = redips.obj.previousElementSibling;
		// console.log(cname.className);

		//var alltag = redips.obj.innerHTML;
		//const alltagarray = alltag.split('/');
		//console.log(alltagarray);

		// var previousTd = rd.td.previous;
		// 移動後のテーブルセルを取得　movedで移動前のテーブルセルは取得している
		var currentTd = rd.td.current.outerHTML;
		var currentTd2 = previousTd2;
		let arraybefore = currentTd2.split('/');
		let arrayafter = currentTd.split('/');
		// rd.td.~でtdのHTMLを取得できるが、型がオブジェクトになるので、それをouterHTMLでHTML(string)として取得
		// そうすると大量の空白が含まれたHTMLが取得されるので、trim()によって空白を削除
		// 空白が削除されたHTMLをsubstrによってtd部分のみ抽出し、抽出されたものをsplitで分割し配列に入れる
//		var test1 = previousTd2.trim();
//		var test2 = test1.substr(test1.indexOf('<td data-href="'), test1.indexOf(' style="')).split('/');
//		console.log(test2);
//		var test3 = currentTd2.trim().substr(currentTd2.indexOf('<td data-href="'), currentTd2.indexOf(' style="')).split('/');
//		console.log(test3);
		var request = new XMLHttpRequest();
		request.open("GET","http://localhost/Cojsons/ajax/"+arraybefore[10]+"/"+arrayafter[6]+"/"+arrayafter[4],true);
		//request.open("GET","/Cojsons/ajax/"+arraybefore[10]+"/"+arrayafter[6]+"/"+arrayafter[4],true);
		request.responseType = 'json';
		request.addEventListener('load',function(response){
			window.location.reload();
		});
		request.send();
	};
};



// -------------------------------------------------
// methods called from UI
// -------------------------------------------------

// called on "mode" dropDown menu change
// show hide numbers in upper table
redips.setMode = function (el) {
	let div = document.getElementById('t100').getElementsByTagName('div'),
		i;
	redips.clearAll();
	redips.mode = el.options[el.selectedIndex].value;
	for (i = 0; i < div.length; i++) {
		if (redips.mode === 'mode1') {
			div[i].innerHTML = redips.readNumber(div[i]);
		}
		else {
			div[i].innerHTML = '?';
		}
	}
};


// called on "operation" dropDown menu change
// set operation - addition / multiplication
redips.setOperation = function (el) {
	// set local variables
	let tables = document.getElementById('redips-drag').getElementsByTagName('table'),i;
	// set operation (global) - needed in event.dropped
	redips.operation = el.options[el.selectedIndex].value;
	// loop goes through all fetched tables within drag container
	for (i = 0; i < tables.length; i++) {
		// skip number table (upper table) or mini table (table with "trash" cell)
		if (tables[i].id === 'number' || tables[i].id === 'mini') {
			continue;
		}
		// show selected table
		else if (tables[i].id === redips.operation) {
			tables[i].style.display = '';
		}
		// hide other tables
		else {
			tables[i].style.display = 'none';
		}
	}
};


// called on click of clear button
// method removes all DIV elements (containing redips-drag class name) from addition and multiplication table
redips.clearAll = function () {
	REDIPS.drag.clearTable('addition');
	REDIPS.drag.clearTable('multiplication');
};


// -------------------------------------------------
// attach event listener on body load
// -------------------------------------------------

// add onload event listener
if (window.addEventListener) {
	window.addEventListener('load', redips.init, false);
}
else if (window.attachEvent) {
	window.attachEvent('onload', redips.init);
}
