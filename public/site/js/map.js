function initMap() {
    var mapDiv = document.getElementById('map');

    var mapOptions = {
        zoom: 15,
        center: new google.maps.LatLng(47.030347, 28.838464),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };

    var map = new google.maps.Map(mapDiv, mapOptions);

    var marker = new google.maps.Marker({
        position: { lat: 47.029837, lng: 28.839417 },
    });
    marker.setMap(map);

    var infowindow = new google.maps.InfoWindow({
        content: "<strong>Neoasig</strong>"
    });

    infowindow.open(map, marker);
}
google.maps.event.addDomListener(window, 'load', init);
