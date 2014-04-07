/*
 * ext-shapes.js
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Christian Tzurcanu
 * Copyright(c) 2010 Alexis Deveria
 *
 */

svgEditor.addExtension("shapes", function() {
	var current_d, cur_shape_id;
	var canv = svgEditor.canvas;
	var cur_shape;
	var start_x, start_y;
	var svgroot = canv.getRootElem();
	var lastBBox = {};
	
	// This populates the category list
	var categories = {
		wells: 'Wells',
		wells2: 'Wells2',
	};
	
	var library = {
	};
	
	var cur_lib = null;
	
	var mode_id = 'shapelib';
	
	var icon_width = 24;
	var icon_height = 24;
	
	function loadIcons() {
		$('#shape_buttons').empty();
		
		// Show lib ones
		$('#shape_buttons').append(cur_lib.buttons);
	}
	
	/* select the library specified by cat_id 
	 * if the library isn't in memory then load it
	 */
	function loadLibrary(cat_id) {
	
		var lib = library[cat_id];
		
		/* if the library hasn't been loaded into memory load it */
		if(!lib) {			
			$('#shape_buttons').html('Loading...');
			console.log("File: extensions/shapelib/" + cat_id + ".json");
			$.getJSON('extensions/shapelib/' + cat_id + '.json', function(result, textStatus) {
				console.log("got " + result.data + " " + result.size + " " + result.fill);
				cur_lib = library[cat_id] = {
					data: result.data,
					size: result.size,
					fill: result.fill
				}
				makeButtons(cat_id, result);
				loadIcons();
			});
			console.log("here");
		}
		
		/* set the current library to cat_id */
		cur_lib = lib;
		/* if the buttons haven't been loaded generate them */
		if(!lib.buttons.length)	makeButtons(cat_id, lib);
		loadIcons();
	}
	
	function create_svg_icon(shape_data) {
		console.log("in_tag: " + shape_data);
			
		// We need to change the svg string so that the viewport is set to the current svg width/height and then set the svg width/height to icon size
		
		// the first tag is the svg tag. It is ended by >. Seperate the svg tag from the rest
		// we drop the > as we will put a viewbox in its place 
		var svg_tag = shape_data.substring(0, shape_data.indexOf(">"));
		var svg_remaining = shape_data.substring(shape_data.indexOf(">") + 1, shape_data.length);
		
		// split the svg tag based on " character
		var parts = svg_tag.split('"');
		
		// remove the svg tag which is attached to the first element
		parts[0] = parts[0].substring(parts[0].indexOf("g")+1, parts[0].length);
		
		// find the width and height elements, their arguments are in the next string
		var svg_width;
		var svg_height;
		for (var i=0; i<parts.length; i++) {
			// remove whitspace so formatting can't get in the way of detection
			var part_no_whitespace = parts[i].replace(/\s+/g,"");
			// retrieve current width and replace with icon width
			if (part_no_whitespace === "width=") {
				svg_width = parts[i+1];
				parts[i+1] = icon_width.toString();
			}
			// retrieve current height and replace with icon height
			if (part_no_whitespace === "height=") {
				svg_height = parts[i+1];
				parts[i+1] = icon_height.toString();
			}
		}
		
		// reassemble the svg tag
		svg_tag = parts.join('"');
		svg_tag = "<svg " + svg_tag;
		
		// we now need to add viewbox to end of svg tag using original svg coords
		vb = [ -svg_width/2, -svg_height/2, svg_width, svg_height].join(" ");
		//i'm unsure why but it seems viewBox MUST have a capitol B...
		svg_tag = svg_tag + ' viewBox="' + vb + '">';
		
		// override all stroke widths with the greater of svg_width or svg_height divide 10
		// this makes it big enough it can be seen
		var stroke_width = Math.max(svg_width, svg_height)/10;
		var parts = svg_remaining.split('=');
		for (var i=0; i<parts.length; i++) {
			var cur_part = parts[i];
			var attr = cur_part.substring(cur_part.lastIndexOf(" ")+1, cur_part.length);
			// find all stroke widths case insensitive
			if (attr.toUpperCase() === "stroke-width".toUpperCase()) {
				var cur_value = parts[i+1];				
				// we are replacing the value part, thus we need to get the next attr so we can retain it
				var end = cur_value.substring(cur_value.indexOf(" "), cur_value.length);
				//overwrite current part with '"(stroke-width)" (next_attr)'
				parts[i+1] = "\"" + stroke_width + "\"" + end;
			}
		}
		// rejoin all the parts
		svg_remaining = parts.join('=');
		
		// reassemble the entire string
		shape_data = svg_tag + svg_remaining;
		console.log("final tag: " + shape_data);
		
		// create the icon
		return new DOMParser().parseFromString(shape_data, 'text/xml');		
	}
	
	/* create the buttons which select svg images in the library */
	function makeButtons(cat, shapes) {
		var size = cur_lib.size || 300;
		var fill = cur_lib.fill || false;
		var off = size * .05;
		//var vb = [-off, -off, size + off*2, size + off*2].join(' ');
		//var vb = [0, 0, 32000, size + off*2].join(' ');
		var stroke = fill ? 0: (size/30);
		
		var data = shapes.data;
		
		cur_lib.buttons = [];
	
		for(var id in data) {
			var shape_d = data[id];
			
			shape_document = create_svg_icon(shape_d);
			
			var svg_icon_elem = $(document.importNode(shape_document.documentElement,true));
			
			// set icon width and height
			shape_document.documentElement.setAttribute('width', icon_width);
			shape_document.documentElement.setAttribute('height', icon_height);
			
			var icon_btn = svg_icon_elem.wrap('<div class="tool_button">').parent().attr({
				id: mode_id + '_' + id,
				title: id
			});
			
			// Store for later use
			cur_lib.buttons.push(icon_btn[0]);
		}
		
	}

	
	return {
		svgicons: "extensions/ext-shapes.xml",
		buttons: [{
			id: "tool_shapelib",
			type: "mode_flyout", // _flyout
			position: 6,
			title: "Shape library",
			events: {
				"click": function() {
					canv.setMode(mode_id);
				}
			}
		}],
		callback: function() { /* callback seems to be called once at startup? */
			$('<style>').text('\
			#shape_buttons {\
				overflow: auto;\
				width: 180px;\
				max-height: 300px;\
				display: table-cell;\
				vertical-align: middle;\
			}\
			\
			#shape_cats {\
				min-width: 110px;\
				display: table-cell;\
				vertical-align: middle;\
				height: 300px;\
			}\
			#shape_cats > div {\
				line-height: 1em;\
				padding: .5em;\
				border:1px solid #B0B0B0;\
				background: #E8E8E8;\
				margin-bottom: -1px;\
			}\
			#shape_cats div:hover {\
				background: #FFFFCC;\
			}\
			#shape_cats div.current {\
				font-weight: bold;\
			}').appendTo('head');

		
			var btn_div = $('<div id="shape_buttons">');
			$('#tools_shapelib > *').wrapAll(btn_div);
			
			var shower = $('#tools_shapelib_show');
			
			// Do mouseup on parent element rather than each button
			$('#shape_buttons').mouseup(function(evt) {
				var btn = $(evt.target).closest('div.tool_button');
				
				if(!btn.length) return;
				
				var copy = btn.children().clone();
				shower.children(':not(.flyout_arrow_horiz)').remove();
				shower
					.append(copy)
					.attr('data-curopt', '#' + btn[0].id) // This sets the current mode
					.mouseup();
				canv.setMode(mode_id);
				
				cur_shape_id = btn[0].id.substr((mode_id+'_').length);
				current_d = cur_lib.data[cur_shape_id];
				
				console.log("current_d " + current_d);
				
				$('.tools_flyout').fadeOut();

			});
			
			var shape_cats = $('<div id="shape_cats">');
			
			var cat_str = '';
			
			/* build list of libraries */
			$.each(categories, function(id, label) {
				cat_str += '<div data-cat=' + id + '>' + label + '</div>';
			});
			console.log(cat_str);
			
			/* when mouse is released on top of an element in the category list */
			shape_cats.html(cat_str).children().bind('mouseup', function() {
				var catlink = $(this);
				/* remove the current category items from view?? */
				catlink.siblings().removeClass('current');
				catlink.addClass('current');
				
				loadLibrary(catlink.attr('data-cat'));
				
				return false;
			});
			
			shape_cats.children().eq(0).addClass('current');
			
			/* add shape categories infront of category items so that shape categories are on the left */
			$('#tools_shapelib').prepend(shape_cats);

			/* when the mouse is released ontop update the mode of svg edit */
			shower.mouseup(function() {
				canv.setMode(current_d ? mode_id : 'select');
			});

			
			$('#tool_shapelib').remove();
			
			var h = $('#tools_shapelib').height();
			$('#tools_shapelib').css({
				'margin-top': -(h/2 - 15),
				'margin-left': 3
			});

	
		},
		mouseDown: function(opts) {			
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			var e = opts.event;
			var x = start_x = opts.start_x;
			var y = start_y = opts.start_y;
			var cur_style = canv.getStyle();
			
			console.log("down!");

			/*cur_shape = canv.addSvgElementFromJson({
				"element": "path",
				"curStyles": true,
				"attr": {
					"d": current_d,
					"id": canv.getNextId(),
					"opacity": cur_style.opacity / 2,
					"style": "pointer-events:none"
				}
			});*/
			
			/*cur_shape = new DOMParser().parseFromString(current_d, 'text/xml');
			svgdoc = document.getElementById("svgcanvas");
			console.log(svgdoc);
			console.log(canv);*/
			canv.addSvgElementFromString(current_d);
			/*// Make sure shape uses absolute values
			if(/[a-z]/.test(current_d)) {
				current_d = cur_lib.data[cur_shape_id] = canv.pathActions.convertPath(cur_shape);
				console.log("current_d fixed" + current_d);
				cur_shape.setAttribute('d', current_d);
				canv.pathActions.fixEnd(cur_shape);
			}
	
			cur_shape.setAttribute('transform', "translate(" + x + "," + y + ") scale(0.005) translate(" + -x + "," + -y + ")");
			
// 			console.time('b');
			canv.recalculateDimensions(cur_shape);
			
			var tlist = canv.getTransformList(cur_shape);
			
			lastBBox = cur_shape.getBBox();
			
			return {
				started: true
			}*/
			// current_d
		},
		mouseMove: function(opts) {
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			var zoom = canv.getZoom();
			var evt = opts.event

			console.log("move!");			
			
			var x = opts.mouse_x/zoom;
			var y = opts.mouse_y/zoom;
			
			var tlist = canv.getTransformList(cur_shape),
				box = cur_shape.getBBox(), 
				left = box.x, top = box.y, width = box.width,
				height = box.height;
			var dx = (x-start_x), dy = (y-start_y);

			var newbox = {
				'x': Math.min(start_x,x),
				'y': Math.min(start_y,y),
				'width': Math.abs(x-start_x),
				'height': Math.abs(y-start_y)
			};

			var ts = null,
				tx = 0, ty = 0,
				sy = height ? (height+dy)/height : 1, 
				sx = width ? (width+dx)/width : 1;

			var sx = newbox.width / lastBBox.width;
			var sy = newbox.height / lastBBox.height;
			
			sx = sx || 1;
			sy = sy || 1;
			
			// Not perfect, but mostly works...
			if(x < start_x) {
				tx = lastBBox.width;
			}
			if(y < start_y) ty = lastBBox.height;
			
			// update the transform list with translate,scale,translate
			var translateOrigin = svgroot.createSVGTransform(),
				scale = svgroot.createSVGTransform(),
				translateBack = svgroot.createSVGTransform();
				
			translateOrigin.setTranslate(-(left+tx), -(top+ty));
			if(!evt.shiftKey) {
				var max = Math.min(Math.abs(sx), Math.abs(sy));

				sx = max * (sx < 0 ? -1 : 1);
				sy = max * (sy < 0 ? -1 : 1);
			}
			scale.setScale(sx,sy);
			
			translateBack.setTranslate(left+tx, top+ty);
			var N = tlist.numberOfItems;
			tlist.appendItem(translateBack);
			tlist.appendItem(scale);
			tlist.appendItem(translateOrigin);

			canv.recalculateDimensions(cur_shape);
			
			lastBBox = cur_shape.getBBox();
		},
		mouseUp: function(opts) {			
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			console.log("up!");
			
			if(opts.mouse_x == start_x && opts.mouse_y == start_y) {
				return {
					keep: false,
					element: cur_shape,
					started: false
				}
			}
			
			return {
				keep: true,
				element: cur_shape,
				started: false
			}
		}		
	}
});

