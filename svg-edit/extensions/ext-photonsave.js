/*
 * ext-server_moinsave.js
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Alexis Deveria
 *              2011 MoinMoin:ReimarBauer
 *                   adopted for moinmoins item storage. it sends in one post png and svg data
 *                   (I agree to dual license my work to additional GPLv2 or later)
 *
 */

svgEditor.addExtension("server_opensave", {
	callback: function() {

		var server_receive_url = '/svg-edit/extensions/photon-filesave.php';
		
		// Create upload target (hidden iframe)
		var target = $('<iframe name="output_frame" src="#"/>').hide().appendTo('body');
	
		svgEditor.setCustomHandlers({
			save: function(win, data) {
				/* get the image name */
				var title = svgCanvas.getDocumentTitle();
				var filename = title.replace(/[^a-z0-9\.\_\-]+/gi, '_');
				
				if (title.length == 0) {
					alert("You must set a title in the document properties before you can save.");
					return;
				}
				
				/* grab an encoded version of data */
				var svg_data = encodeURI(data);				
				
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", server_receive_url);
				form.setAttribute("target", "output_frame");
				form.setAttribute("target", "a");
				
				var svg_data_field = document.createElement("input");
				svg_data_field.setAttribute("type", "hidden");
				svg_data_field.setAttribute("name", "svgdata");
				svg_data_field.setAttribute("value", svg_data);
				form.appendChild(svg_data_field);				
				
				var svg_name_field = document.createElement("input");
				svg_name_field.setAttribute("type", "hidden");
				svg_name_field.setAttribute("name", "svgname");
				svg_name_field.setAttribute("value", filename);
				form.appendChild(svg_name_field);
				
				document.body.appendChild(form);
				
				form.submit().remove();			
			}
		});
	
	}
});

