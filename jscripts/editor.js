editor = (function() {
 	return {
 		init: function() {
 			return true;
 		},
 		insert_text: function(d, h, i) {
 			var g, f, e = document.getElementById(i);
 			if (!e) {
 				return false;
 			}
 			if (document.selection && document.selection.createRange) {
 				e.focus();
 				g = document.selection.createRange();
 				g.text = d + g.text + h;
 				e.focus();
 			} else {
 				if (e.selectionStart || e.selectionStart === 0) {
 					var c = e.selectionStart,
 						b = e.selectionEnd,
 						a = e.scrollTop;
 					e.value = e.value.substring(0, c) + d + e.value.substring(c, b) + h + e.value.substring(b, e.value.length);
 					if (d.charAt(d.length - 2) === "=") {
 						e.selectionStart = (c + d.length - 1);
 					} else {
 						if (c === b) {
 							e.selectionStart = b + d.length;
 						} else {
 							e.selectionStart = b + d.length + h.length;
 						}
 					}
 					e.selectionEnd = e.selectionStart;
 					e.scrollTop = a;
 					e.focus();
 				} else {
 					e.value += d + h;
 					e.focus();
 				}
 			}
 		}
 	};
 }())

/**
* Set display of page element
*
* @param string	id	The ID of the element to change
* @param int	action	Set to 0 if element display should be toggled, -1 for
*			hiding the element, and 1 for showing it.
* @param string	type	Display type that should be used, e.g. inline, block or
*			other CSS "display" types
*/
toggleDisplay = function(id, action, type) {
	if (!type) {
		type = 'block';
	}

	var $element = $('#' + id);

	var display = $element.css('display');
	if (!action) {
		action = (display === '' || display === type) ? -1 : 1;
	}
	$element.css('display', ((action === 1) ? type : 'none'));
};

/**
* Get the HTML for a color palette table.
*
* @param string dir Palette direction - either v or h
* @param int width Palette cell width.
* @param int height Palette cell height.
*/
colorPalette = function(dir, width, height) {
	var r = 0, 
		g = 0, 
		b = 0,
		numberList = new Array(6),
		color = '',
		html = '';

	numberList[0] = '00';
	numberList[1] = '40';
	numberList[2] = '80';
	numberList[3] = 'BF';
	numberList[4] = 'FF';

	var tableClass = (dir == 'h') ? 'horizontal-palette' : 'vertical-palette';
	html += '<table class="not-responsive colour-palette ' + tableClass + '" style="width: auto;">';

	for (r = 0; r < 5; r++) {
		if (dir == 'h') {
			html += '<tr>';
		}

		for (g = 0; g < 5; g++) {
			if (dir == 'v') {
				html += '<tr>';
			}

			for (b = 0; b < 5; b++) {
				color = String(numberList[r]) + String(numberList[g]) + String(numberList[b]);
				html += '<td style="background-color: #' + color + '; width: ' + width + 'px; height: ' + height + 'px;">';
				html += '<a href="#" data-color="' + color + '" style="display: block; width: ' + width + 'px; height: ' + height + 'px; " alt="#' + color + '" title="#' + color + '"></a>';
				html += '</td>';
			}

			if (dir == 'v') {
				html += '</tr>';
			}
		}

		if (dir == 'h') {
			html += '</tr>';
		}
	}
	html += '</table>';
	return html;
};

/**
* Register a color palette.
*
* @param object el jQuery object for the palette container.
*/
registerPalette = function(el,area) {
	var	orientation	= el.attr('data-orientation'),
		height		= el.attr('data-height'),
		width		= el.attr('data-width'),
		target		= el.attr('data-target'),
		bbcode		= el.attr('data-bbcode');

	// Insert the palette HTML into the container.
	el.html(colorPalette(orientation, width, height));

	// Add toggle control.
	$('#color_palette_toggle').click(function(e) {
		el.toggle();
		e.preventDefault();
	});

	// Attach event handler when a palette cell is clicked.
	$(el).on('click', 'a', function(e) {
		var color = $(this).attr('data-color');

		if (bbcode) {
			editor.insert_text('[color=#' + color + ']', '[/color]', ''+area+'');
		} else {
			$(target).val(color);
		}
		e.preventDefault();
	});
}

/**
* Apply code editor to all textarea elements with data-bbcode attribute
*/
$(function() {
	$('.color_palette_placeholder_message').each(function() {
		registerPalette($(this),$(this).attr('data-local'));
	});
});

MyBBEditor = {};

MyBBEditor.insertText = function(text) {
	editor.insert_text(text,'','message'); return false;
}