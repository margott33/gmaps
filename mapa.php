<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Google Maps JavaScript API v3 Example: Common Loader</title>
<script type="text/javascript" src="jquery-1.9.0.js"></script>
<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=false"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="util.js"></script>
<script type="text/javascript">

	//Icons
	var customIcons = {
      free: {
        icon: 'https://cdn4.iconfinder.com/data/icons/32x32-free-design-icons/32/Ok.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      busy: {
        icon: 'https://cdn4.iconfinder.com/data/icons/32x32-free-design-icons/32/Cancel.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

	//Popup dos markers
	var infoWindow = null;	

	//A visibilidade do mapa precisa estar global
	var map = null;
	
	//Este é um array global dos marcadores presentes na tela
	var markersArray = [];

	/*
	 * Inicialização da API de Mapas do Google 
	 */
	function initialize() {

		//Não vou explicar o óbvio!!!
		var myLatlng = new google.maps.LatLng(-34.459270000, -58.891050000);
		var myOptions = {
			zoom : 12,
			center : myLatlng,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		}

		map = new google.maps.Map(document.getElementById("map_canvas"),
				myOptions);
				
		infoWindow = new google.maps.InfoWindow;				
		
		//Esse método eu criei para realizar o load dos markers no mapa
		//Execução imediata!!!
		updateMaps();

		//Definimos tambem execução com intervalo de tempo
		window.setInterval(updateMaps, 60000);

	}
	
	/*
	 * Método que remove os overlays dos markers
	 */
    function clearOverlays() {
	  for (var i = 0; i < markersArray.length; i++ ) {
	   markersArray[i].setMap(null);
	  }
	}
	
	/*
	 * Método que realiza chama o caminho do xml de dados
	 * e atualiza o mapa
	 */	
	function updateMaps() {

		// Vamos remover o que já havia de overlay
		// É possível implementar a remoção e inclusão seletiva
		clearOverlays();

		//Aqui é o pulo do gato, que muita gente perde noites de sono
		//e quando você para para ver a solução, percebe que é tão óbvia
		
		//Quando chamamos um arquivo, o browser pode tomar a decisão
		//de armazenar em cache. Se o browser utilizar cache, as próximas 
		//requisições do mesmo recurso não batem no servidor.
		
		//Definindo um modificador único para o arquivo de dados conseguimos "FORÇAR" 
		//o browser a baixar novamente o arquivo.
		
		//Em java eu utilizo o header do http para dizer NO-CACHE!!
		
		var timestamp = new Date().getTime();
		var data = 'xml.php?t=' + timestamp;
		
		//Me guardo o direito a não explicar o óbvio, novamente
		$.get(data, {}, function(data) {
			$(data).find("marker").each(
					function() {
						var marker = $(this);
						var status = marker.attr("status")
						var icon = customIcons[status] || {};
						var latlng = new google.maps.LatLng(parseFloat(marker
								.attr("lat")), parseFloat(marker.attr("lng")));
						var html = "<b><h3>"+marker.attr("id")+"</h3> Estado:" +marker.attr("EVE")+"</b><br/>"+marker.attr("FH");
						var marker = new google.maps.Marker({
							position : latlng,
							map : map,
							icon: icon.icon,
							shadow: icon.shadow,
						});
						
						google.maps.event.addListener(marker, 'click', function() {
						        infoWindow.setContent(html);
						        infoWindow.open(map, marker);
						      });						
						
					//Opa... bora guardar as referências dos markers??
					markersArray.push(marker);
						
					google.maps.event.addListener(marker, "click", function() {});
					});
			});

		}

		google.setOnLoadCallback(initialize);
	
</script>