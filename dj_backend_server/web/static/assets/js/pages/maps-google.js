/*
Template Name: TailFox - Responsive Tailwind Admin Dashboard
Author: Myra Studio
Website: https://myrathemes.com/
Contact: myrathemes@gmail.com
File: Google Map
*/


class GoogleMap {

    initBasicGoogleMap() {
        var map = new GMaps({
            div: '#gmaps-basic', lat: -12.043333, lng: -77.028333
        });
    }

    initMarkerGoogleMap() {
        var map = new GMaps({
            div: '#gmaps-markers', lat: -12.043333, lng: -77.028333
        });
    }

    initStreetViewGoogleMap() {
        var panorama = GMaps.createPanorama({
            el: '#panorama', lat: 42.3455, lng: -71.0983
        });
    }

    initMapTypes() {
        var map = new GMaps({
            el: '#gmaps-types', lat: 42.3455, lng: -71.0983, mapTypeControlOptions: {
                mapTypeIds: ["hybrid", "roadmap", "satellite", "terrain", "osm", "cloudmade"]
            }
        });
        map.addMapType("osm", {
            getTileUrl: function (coord, zoom) {
                return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
            }, tileSize: new google.maps.Size(256, 256), name: "OpenStreetMap", maxZoom: 18
        });
        map.addMapType("cloudmade", {
            getTileUrl: function (coord, zoom) {
                return "http://b.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/1/256/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
            }, tileSize: new google.maps.Size(256, 256), name: "CloudMade", maxZoom: 18
        });
        map.setMapTypeId("osm");
        return map;
    }

    initUltraLightMap() {
        var map = new GMaps({
            div: '#ultra-light', lat: 42.3455, lng: -71.0983, styles: [{
                "featureType": "water", "elementType": "geometry", "stylers": [{
                    "color": "#e9e9e9"
                }, {
                    "lightness": 17
                }]
            }, {
                "featureType": "landscape", "elementType": "geometry", "stylers": [{
                    "color": "#f5f5f5"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 17
                }]
            }, {
                "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 29
                }, {
                    "weight": 0.2
                }]
            }, {
                "featureType": "road.arterial", "elementType": "geometry", "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 18
                }]
            }, {
                "featureType": "road.local", "elementType": "geometry", "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 16
                }]
            }, {
                "featureType": "poi", "elementType": "geometry", "stylers": [{
                    "color": "#f5f5f5"
                }, {
                    "lightness": 21
                }]
            }, {
                "featureType": "poi.park", "elementType": "geometry", "stylers": [{
                    "color": "#dedede"
                }, {
                    "lightness": 21
                }]
            }, {
                "elementType": "labels.text.stroke", "stylers": [{
                    "visibility": "on"
                }, {
                    "color": "#ffffff"
                }, {
                    "lightness": 16
                }]
            }, {
                "elementType": "labels.text.fill", "stylers": [{
                    "saturation": 36
                }, {
                    "color": "#333333"
                }, {
                    "lightness": 40
                }]
            }, {
                "elementType": "labels.icon", "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "transit", "elementType": "geometry", "stylers": [{
                    "color": "#f2f2f2"
                }, {
                    "lightness": 19
                }]
            }, {
                "featureType": "administrative", "elementType": "geometry.fill", "stylers": [{
                    "color": "#fefefe"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{
                    "color": "#fefefe"
                }, {
                    "lightness": 17
                }, {
                    "weight": 1.2
                }]
            }]
        });
    }

    initDarkMap() {
        var map = new GMaps({
            div: '#dark', lat: 42.3455, lng: -71.0983, styles: [{
                "featureType": "all", "elementType": "labels", "stylers": [{
                    "visibility": "on"
                }]
            }, {
                "featureType": "all", "elementType": "labels.text.fill", "stylers": [{
                    "saturation": 36
                }, {
                    "color": "#000000"
                }, {
                    "lightness": 40
                }]
            }, {
                "featureType": "all", "elementType": "labels.text.stroke", "stylers": [{
                    "visibility": "on"
                }, {
                    "color": "#000000"
                }, {
                    "lightness": 16
                }]
            }, {
                "featureType": "all", "elementType": "labels.icon", "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "administrative", "elementType": "geometry.fill", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 17
                }, {
                    "weight": 1.2
                }]
            }, {
                "featureType": "administrative.country", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#e5c163"
                }]
            }, {
                "featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#c4c4c4"
                }]
            }, {
                "featureType": "administrative.neighborhood", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#e5c163"
                }]
            }, {
                "featureType": "landscape", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "poi", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 21
                }, {
                    "visibility": "on"
                }]
            }, {
                "featureType": "poi.business", "elementType": "geometry", "stylers": [{
                    "visibility": "on"
                }]
            }, {
                "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{
                    "color": "#e5c163"
                }, {
                    "lightness": "0"
                }]
            }, {
                "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#ffffff"
                }]
            }, {
                "featureType": "road.highway", "elementType": "labels.text.stroke", "stylers": [{
                    "color": "#e5c163"
                }]
            }, {
                "featureType": "road.arterial", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 18
                }]
            }, {
                "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{
                    "color": "#575757"
                }]
            }, {
                "featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#ffffff"
                }]
            }, {
                "featureType": "road.arterial", "elementType": "labels.text.stroke", "stylers": [{
                    "color": "#2c2c2c"
                }]
            }, {
                "featureType": "road.local", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 16
                }]
            }, {
                "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{
                    "color": "#999999"
                }]
            }, {
                "featureType": "transit", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 19
                }]
            }, {
                "featureType": "water", "elementType": "geometry", "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 17
                }]
            }]
        });
    }

    init() {
        this.initBasicGoogleMap();
        this.initMarkerGoogleMap();
        this.initStreetViewGoogleMap();
        this.initMapTypes();
        this.initUltraLightMap();
        this.initDarkMap();
    }

}

document.addEventListener('DOMContentLoaded', function (e) {
    new GoogleMap().init();
});