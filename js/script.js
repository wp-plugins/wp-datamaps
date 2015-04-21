 jQuery(function($){

            placename = options_object.text_string;
            mapColor = options_object.color;
            height = options_object.height;
            width = options_object.width;
            desc = options_object.desc;
            hover = options_object.hover;
            markerColor = options_object.markerColor;
            markerHover = options_object.markerHover;

            $('#container').css('height',height);
            $('#container').css('width',width)

            var key = "AIzaSyA3Dwx_vBbqbBsOWq0V0Nxa40YCqTohyz0";
    
            $.ajax({
                url: "https://maps.googleapis.com/maps/api/geocode/json?address="+placename+"&key="+ key,
                mthod: "GET",
                datatype: "json"
            })
            .done(function(response){
                latitude = response.results[0].geometry.location.lat;
                longitude = response.results[0].geometry.location.lng;
                newMap(latitude, longitude);
            });
    

            function newMap(lat, lng){
                var map = new Datamap({
                    element: document.getElementById("container"),
                    responsive: true,
                    projection: "mercator",
                    fills: {
                        "marker": markerColor,
                        defaultFill: mapColor 
                    },
                    data: {
                        "marker": {fillKey: "marker"},
                    },
                    geographyConfig: {
                        highlightFillColor: hover,
                        popupOnHover: false,
                        highlightBorderColor: 'rgba(255,255,255,0.2)'
                    },
                    bubblesConfig: {
                        highlightFillColor: markerHover
                    },
                    setProjection: function(element) {
                        var projection = d3.geo.equirectangular()
                            .center([lng, lat])
                            .rotate([4.4, 0])
                            .scale(600)
                            .translate([element.offsetWidth / 2, element.offsetHeight / 2]);
                    var path = d3.geo.path()
                    .projection(projection);
            
                    return {path: path, projection: projection};
                    },

                });  

                var visited = [{
                    radius: 5,
                    fillKey: "marker",
                    latitude:lat,
                    longitude:lng 
                  }
                ];

                map.bubbles(visited, {
                    popupTemplate: function (geo, data) { 
                            return ["<div class='hoverinfo'>"+desc+"</div>"].join("");
                    },
                });    

                map.svg.call(d3.behavior.zoom() 
                    .on("zoom", redraw));

                function redraw() {
                    map.svg.selectAll("g").attr("transform", "translate(" + d3.event.translate + "), scale(" + d3.event.scale + ")");
                }

            }
                  

            

            

});