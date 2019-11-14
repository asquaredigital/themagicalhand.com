function initialize() {
    var mapProp = {
      center:new google.maps.LatLng(51.4947368,-0.3698831),
      zoom:17,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
  }