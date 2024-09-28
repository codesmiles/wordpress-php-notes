   <script
                        src="https://maps.googleapis.com/maps/api/js?key=YOUR-API-KEY&loading=async&callback=initMap&libraries=marker" async defer>
                    </script>
                        
                        <script>
                            // START MAP
                            const startLat = parseFloat(data.start_location.lat);
                            const startLng = parseFloat(data.start_location.long);
                            const currentLat = parseFloat(data.current_location.lat);
                            const currentLng = parseFloat(data.current_location.long);
                            const finalLat = parseFloat(data.end_location.lat);
                            const finalLng = parseFloat(data.end_location.long);

                   
                            function initMap () {
                            let startPosition = { lat: startLat, lng: startLng };
                            let currentPosition = { lat: currentLat, lng: currentLng };
                            let endPosition = { lat: finalLat, lng: finalLng };
                            
                            let map = new google.maps.Map(document.getElementById("view_map"), {
                                zoom: 5,
                                center: startPosition,
                                mapId: "DEMO_MAP_ID"
                            });
                            
                            const addMarker = (prop) => {
                                return new google.maps.Marker({
                                    position: prop.coordinates,
                                    map: map,
                                    label: prop.label,
                                    ...prop
                                });
                            }
                            
                            addMarker({
                                coordinates:startPosition,
                                label: "S"
                            });
                            
                            let currentMarker = addMarker({
                                coordinates:currentPosition,
                                label: "C",
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 10,
                                    fillColor: 'red',
                                    fillOpacity: 1,
                                    strokeColor: 'white',
                                    strokeWeight: 2,
                                    animation: google.maps.Animation.BOUNCE
                                }
                            })
                            
                            addMarker({
                                coordinates: endPosition,
                                 label: "E",
                               
                            })
        
                            var line = new google.maps.Polyline({
                                path: [startPosition, currentPosition, endPosition],
                                geodesic: true,
                                strokeColor: '#FF0000',
                                strokeOpacity: 1.0,
                                strokeWeight: 2
                            });
        
                            line.setMap(map);
        
                            // Blinking effect for the current marker
                            setInterval(() => {
                                currentMarker.setVisible(!currentMarker.getVisible());
                            }, 500);
                        };
                      
                        window.onload = initMap();
                                 </script>