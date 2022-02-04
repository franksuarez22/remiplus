//Capas base
const basemaps = {
  "OpenStreetMaps": L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      minZoom: 2,
      maxZoom: 19,
      id: "osm.streets"
    }
  ),
  "Google-Map": L.tileLayer(
    "https://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}",
    {
      minZoom: 2,
      maxZoom: 19,
      id: "google.street"
    }
  ),
  "Google-Satellite": L.tileLayer(
    "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
    {
      minZoom: 2,
      maxZoom: 19,
      id: "google.satellite"
    }
  ),
  "Google-Hybrid": L.tileLayer(
    "https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}",
    {
      minZoom: 2,
      maxZoom: 19,
      id: "google.hybrid"
    }
  ),
  "CartoDB_DarkMatter" : L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
		subdomains: 'abcd',
		minZoom: 2,
		maxZoom: 19,
		id: "Dark.matter"
	})
};



//Map Options
const mapOptions = {
  zoomControl: true,
  attributionControl: true,
  center: [8.118513, -66.213671],
  zoom: 6,
  layers: [basemaps.OpenStreetMaps],
  scrollWheelZoom: false,
  //visualClick: true
};

//Render Main Map
map = L.map("map", mapOptions);

L.control.layers(basemaps, null,{collapsed:true}).addTo(map);

// create a fullscreen button and add it to the map
L.control.fullscreen({
  position: 'topleft', 
  title: 'Pantalla completa', 
  titleCancel: 'Salir pantalla completa ', 
  content: null, 
  forceSeparateButton: true, 
  forcePseudoFullscreen: true, 
  fullscreenElement: false 
}).addTo(map);

let volverInicio = () => {
map.flyTo([8.118513, -66.213671], 6, {
    animate: true,
    duration: 2 // in seconds
  });	
};
L.easyButton( '<span class="fas fa-home"></span>', volverInicio).addTo(map);

let grupo_marcadores_ubicaciones = L.layerGroup().addTo(map);
var inputlat = document.getElementById("latitud");
var inputlng = document.getElementById("longitud");
var inputdireccion = document.getElementById("direccion");
let crearMarcador = (e) => {
    grupo_marcadores_ubicaciones.clearLayers();
    markerIncidencia = new L.marker(e.latlng, {}).addTo(grupo_marcadores_ubicaciones).addTo(map);
    //markerIncidencia.bindPopup("<b>Coodenadas:</b> "+e.latlng + " Latitud: " +e.latlng.lat + ", Longitud: " + e.latlng.lng).openPopup(); 
    inputlat.value=e.latlng.lat;
    inputlng.value=e.latlng.lng;

    fetch("https://nominatim.openstreetmap.org/reverse?" +
              "format=json&amp;addressdetails=0&zoom=18&" +
              "lat=" + e.latlng.lat + "&lon=" + e.latlng.lng )
    .then(response => response.json())
    .then(function(response) {
      //if(response.ok) {
          inputdireccion.value=response.display_name;
      //} else {
        //console.log('Respuesta de red OK pero respuesta HTTP no OK');
      //}
    })
    .catch(function(error) {
      console.log('Hubo un problema con la petición Fetch:' + error.message);
    });
};
map.on('click', crearMarcador);


setTimeout(function() {
    map.invalidateSize();
}, 10);
