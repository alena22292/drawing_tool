<html lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Online Drawing Application | Web Design Deluxe</title>
	<script src="https://kit.fontawesome.com/bae5c5edb7.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<!-- leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.css"/>
  <!-- CDN MAPBOX -->
  <script src='https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js'></script>
	<link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />
  <style>
      body {
      	margin: 0;
      	padding: 0;
      }
      a {
      	text-decoration:none; 
        cursor:pointer; 
        color: white; 
      }
      .flex {
      	display:  flex;
      	margin-bottom: 20px;
      }
      #wrapper { 
			  position:relative; 
			  background:#f8f8f8; 
        color:#d7d7d7; 
			  width:960px; 
			  margin:0 auto; 
			  padding-top:75px;
			} 
			  
			#blackboardPlaceholder { 
			  /*background:black; */
			  width:924px; 
			  height:599px; 
			  margin:0 auto; 
			  padding:14px 0 75px 14px; 
			  cursor:crosshair; 
			}
			div {
				margin: 0 auto;
			}
			#map {
    		height: 80%;
    		width: 80%;
    	}
			#drawingCanvas { 
				 position:absolute; 
				 border:none; 
				 color:#FFF; 
				 overflow:hidden; 
				 background-color:transparent; 
				 z-index: 1000;
				 top:  124px;
			 } 
			#tempCanvas { 
				position: absolute; 
				width:897px; 
				height:532px; 
				overflow:hidden;
				background-color:transparent;  
				z-index: 1000;
				top:  124px;
			}
			.noscript { padding:50px 30px 0 40px; width:820px; } 
			.btn {
				margin-left: 12px;
		    border: 1px solid black;
		    font-size: 14px;
		    padding: 1px 8px;
		    color: white;
		    background: black;
		    cursor: pointer;
			} 
			select { 
			 font-family:Verdana, Geneva, sans-serif; 
			 font-size:12px; 
			 background-color:#EAEAEA; 
			}

			/*color-palet*/
			.color-palet {
				 position:absolute; 
				 z-index:99999; 
				 margin-top:-30px; 
				 right: 0;
			}

			#orange {
				background-color:#ff9600;
			}
			#red {
				background-color:red;
			}
			#blue {
				background-color:blue;
			}
			#green {
				background-color:green;
			}
      #yellow {
				background-color:yellow;
			}
			#white {
				background-color:white;
			}
		
			.color {
				width: 30px;
				height: 30px;
			  cursor: pointer;
			}
  </style>
</head>
<body>
	<h1>Hello, it is a drawing tool</h1>
<!-- <div id="wrapper"> 
    <div id="blackboardPlaceholder"> 
    	<div class="flex"> -->
	    	
					<!-- <select name="selector" id="selector"> 
						<option value="chalk">Chalk</option> 
						<option value="line">Line</option> 
						<option value="rect">Rectangle</option> 
					</select>  -->

				<!-- reload the page clear up all drawings -->
				<!-- <div class="btn" id="clear"><a href="javascript:location.reload(true)">Clear</a></div> -->
				<!-- <div class="btn" id="toggleMap">Map</div> -->
				
			<!-- </div> -->
			<!-- Chalk Pieces -->
			<!-- <div class="color-palet">
				<div id="orange" class="color" onclick="context.strokeStyle = '#ff9600'; context.lineWidth = '2';"></div>
				<div id="red" class="color" onclick="context.strokeStyle = 'red'; context.lineWidth = '2';"></div>
				<div id="blue" class="color" onclick="context.strokeStyle = 'blue'; context.lineWidth = '2';"></div>
				<div id="green" class="color" onclick="context.strokeStyle = 'green'; context.lineWidth = '2';"></div>
				<div id="yellow" class="color" onclick="context.strokeStyle = 'yellow'; context.lineWidth = '2';"></div>
				<div id="white" class="color" onclick="context.strokeStyle = 'white'; context.lineWidth = '2';"></div>		
			</div> -->
			
			<div id='map'>	
			</div>
			<!-- <canvas id="drawingCanvas" height="532" width="897"></canvas>  -->

 <!--    </div> 
  </div>  -->

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.js"></script>

   <script type="text/javascript">
		  // MapBox API
			const accessToken = 'pk.eyJ1IjoiYWxlbmEyMjI5MiIsImEiOiJja2g2OHE4ZTgwYnhhMnV1aWE0eGE4ZGI4In0.rQgBEM3EBhw-5_nT4O74ow';
			// Layers
			const mapBoxLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
			    id: 'mapbox/streets-v11',
			    accessToken: accessToken,
			    maxZoom: 19,
			    tileSize: 512,
			    zoomOffset: -1,
			    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
			    detectRetina: true
			});

      const myMap = L.map('map', {layers: [mapBoxLayer]}).setView([51.863831603254674, 5.855798721313476], 17);

			var greenIcon = L.icon({
		    iconUrl: 'images/map2.png',
		    iconSize:     [229, 229], // size of the icon
		    iconAnchor:   [130, 130], // point of the icon which will correspond to marker's location
		});

			// L.marker([51.863831603254674, 5.855798721313476], {icon: greenIcon, draggable:'true'}).addTo(myMap);

			const drawnItems = new L.FeatureGroup();
			var json = drawnItems.toGeoJSON();

			myMap.addLayer(drawnItems);

			const drawControl = new L.Control.Draw({
				   draw: {
              polygon: {
              	  // allowIntersection: false,
	                showArea: true
	            },
	            marker: {
                  icon: greenIcon
              }
           },
	         edit: {
	             featureGroup: drawnItems,
	             poly: {
	                allowIntersection: false
	            }
	         }
	     });

			myMap.addControl(drawControl);

			// This line is fixed the bug of closing poligon while drawing;
			L.Draw.Polyline.prototype._onTouch = L.Util.falseFn;

			myMap.on(L.Draw.Event.CREATED, function (event) {
	        var layer = event.layer;
	        
	        // console.log(layer.getLatLngs());
	        drawnItems.addLayer(layer);
	    });

	</script>


  <!-- Add Canvas as a Drawing tool -->


  <script type="text/javascript">
 //  	var context;
 //  	if(window.addEventListener) {
 //  		 window.addEventListener('load', function () {
 //  		   var canvas, canvaso, contexto; 

	// 	     // Default tool. (chalk, line, rectangle) 
	// 	     var tool; 
	// 	     var tool_default = 'chalk'; 

	// 	     function init () { 
	// 			   canvaso = document.getElementById('drawingCanvas'); 

	// 			   if (!canvaso) { 
	// 				   alert('Error! The canvas element was not found!'); 
	// 				   return; 
	// 			   } 
	// 			   if (!canvaso.getContext) { 
	// 				   alert('Error! No canvas.getContext!'); 
	// 				   return; 
	// 			   } 

	// 			   // Create 2d canvas. 
	// 			   contexto = canvaso.getContext('2d'); 
	// 			   if (!contexto) { 
	// 				   alert('Error! Failed to getContext!'); 
	// 				   return; 
	// 			   } 

	// 			   // Build the temporary canvas. 
	// 			   var container = canvaso.parentNode; 
	// 			   canvas = document.createElement('canvas'); 
	// 			   if (!canvas) { 
	// 				   alert('Error! Cannot create a new canvas element!'); 
	// 				   return; 
	// 			   } 

	// 			 canvas.id     = 'tempCanvas'; 
	// 			 canvas.width  = canvaso.width; 
	// 			 canvas.height = canvaso.height; 
	// 			 container.appendChild(canvas); 
	// 			 context = canvas.getContext('2d'); 

	// 			 context.strokeStyle = "#FFFFFF";// Default line color. 
	// 			 context.lineWidth = 1.0;// Default stroke weight. 
				  
	// 			 // Fill transparent canvas with dark grey (So we can use the color to erase). 
	// 			 context.fillStyle = "transparent"; 

	// 			 context.fillRect(0,0,897,532);//Top, Left, Width, Height of canvas.

	// 			 // Create a select field with our tools. 
	// 			 var tool_select = document.getElementById('selector'); 
	// 			 if (!tool_select) { 
	// 				 alert('Error! Failed to get the select element!'); 
	// 				 return; 
	// 			 } 
	// 			 tool_select.addEventListener('change', ev_tool_change, false); 
				  
	// 			 // Activate the default tool (chalk). 
	// 			 if (tools[tool_default]) { 
	// 				 tool = new tools[tool_default](); 
	// 				 tool_select.value = tool_default; 
	// 			 } 

	// 		   // Event Listeners. 
	// 		   canvas.addEventListener('mousedown', ev_canvas, false); 
	// 		   canvas.addEventListener('mousemove', ev_canvas, false); 
	// 		   canvas.addEventListener('mouseup',   ev_canvas, false); 
	// 	} 

	// 	// Get the mouse position. 
	// 	function ev_canvas (ev) { 
	// 	   if (ev.layerX || ev.layerX == 0) { // Firefox 
	// 		   ev._x = ev.layerX; 
	// 		   ev._y = ev.layerY; 
	// 	   } else if (ev.offsetX || ev.offsetX == 0) { // Opera 
	// 		   ev._x = ev.offsetX; 
	// 		   ev._y = ev.offsetY; 
	// 	   } 
	// 		 // Get the tool's event handler. 
	// 	   var func = tool[ev.type]; 
	// 	   if (func) { 
	// 	    func(ev); 
	// 	   } 
	// 	} 
	// 	function ev_tool_change (ev) { 
	// 	  if (tools[this.value]) { 
	// 	   tool = new tools[this.value](); 
	// 	  } 
	// 	} 

	// 	// Create the temporary canvas on top of the canvas, which is cleared each time the user draws. 
	// 	function img_update () { 
	// 	  contexto.drawImage(canvas, 0, 0); 
	// 	  context.clearRect(0, 0, canvas.width, canvas.height); 
	// 	} 

	// 	var tools = {}; 
	//   // Chalk tool. 
	// 	tools.chalk = function () { 
	//    var tool = this; 
	//    this.started = false; 
	//    // Begin drawing with the chalk tool. 
	//    this.mousedown = function (ev) { 
	// 	   context.beginPath(); 
	// 	   context.moveTo(ev._x, ev._y); 
	// 	   tool.started = true; 
	//    }; 
	//    this.mousemove = function (ev) { 
	// 	   if (tool.started) { 
	// 		   context.lineTo(ev._x, ev._y); 
	// 		   context.stroke(); 
	// 	   } 
	//    }; 
	//    this.mouseup = function (ev) { 
	// 	   if (tool.started) { 
	// 		   tool.mousemove(ev); 
	// 		   tool.started = false; 
	// 		   img_update(); 
 //       } 
 //     }; 
 //   };

 //   // The rectangle tool. 
	// 	 tools.rect = function () { 
	// 	 var tool = this; 
	// 	 this.started = false; 
	// 	 this.mousedown = function (ev) { 
	// 		 tool.started = true; 
	// 		 tool.x0 = ev._x; 
	// 		 tool.y0 = ev._y; 
	// 	 }; 
	// 	 this.mousemove = function (ev) { 
	// 		 if (!tool.started) { 
	// 		   return; 
	// 		 } 
	// 		 // This creates a rectangle on the canvas. 
	// 		 var x = Math.min(ev._x,  tool.x0), 
	// 		 y = Math.min(ev._y,  tool.y0), 
	// 		 w = Math.abs(ev._x - tool.x0), 
	// 		 h = Math.abs(ev._y - tool.y0); 
	// 		 context.clearRect(0, 0, canvas.width, canvas.height);// Clears the rectangle onload. 
			  
	// 		if (!w || !h) { 
	// 	   return; 
	// 	  } 

	// 		context.strokeRect(x, y, w, h); 
	// 	}; 

	// 	// Now when you select the rectangle tool, you can draw rectangles. 
	// 	this.mouseup = function (ev) { 
	// 	   if (tool.started) { 
	// 		   tool.mousemove(ev); 
	// 		   tool.started = false; 
	// 		   img_update(); 
	// 	   } 
	// 	}; 
	// };

 // // The line tool. 
 // tools.line = function () { 
	//  var tool = this; 
	//  this.started = false; 
	//  this.mousedown = function (ev) { 
	// 	 tool.started = true; 
	// 	 tool.x0 = ev._x; 
	// 	 tool.y0 = ev._y; 
	//  }; 
	//  this.mousemove = function (ev) { 
	// 	 if (!tool.started) { 
	// 	  return; 
	// 	 } 
	// 	 context.clearRect(0, 0, canvas.width, canvas.height); 

	// 	 // Begin the line. 
	// 	 context.beginPath(); 
	// 	 context.moveTo(tool.x0, tool.y0); 
	// 	 context.lineTo(ev._x,   ev._y); 
	// 	 context.stroke(); 
	// 	 context.closePath(); 
	//  }; 

	//  // Now you can draw lines when the line tool is seletcted. 
	//  this.mouseup = function (ev) { 
	// 	 if (tool.started) { 
	// 		 tool.mousemove(ev); 
	// 		 tool.started = false; 
	// 		 img_update(); 
	// 	 } 
	//  }; 
 // };

 // init();

 // $('#toggleMap').click(function(){
 // 	if ( $(this).text() == "Map") {
 // 		$(this).text( "Drawing Tool");

 // 		// there should be a map functions;
 // 		$('#drawingCanvas').css('z-index', '-1');
 // 		$('#tempCanvas').css('z-index', '-1');
 // 		$('.color-palet').hide();
 // 		$('#clear').hide();
 // 	} else {
 // 		$(this).text( "Map");

 //    // You may draw polygons
 //    $('#drawingCanvas').css('z-index', '1000');
 // 		$('#tempCanvas').css('z-index', '1000');
 // 		$('.color-palet').show();
 // 		$('#clear').show();
 // 	}

 // })

 // }, false);
// }
  </script>
</body>
</html>

