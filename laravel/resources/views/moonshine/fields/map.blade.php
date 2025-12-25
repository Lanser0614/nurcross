<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div id="map" style="height: 300px;"></div>

<script>
    document.addEventListener("turbo:load", initMap);
    document.addEventListener("DOMContentLoaded", initMap);

    function initMap() {
        if (window.mapReady) return;
        window.mapReady = true;

        let lat = {{ $lat ?? 'null' }};
        let lng = {{ $lng ?? 'null' }};

        // input поля MoonShine
        let latInput = document.querySelector(`input[name="{{ $lat }}"]`);
        let lngInput = document.querySelector(`input[name="{{ $lng }}"]`);

        if (!lat) lat = 41.31;
        if (!lng) lng = 69.28;

        let map = L.map('map').setView([lat, lng], 14);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', (e) => {
            const pos = e.target.getLatLng();
            latInput.value = pos.lat.toFixed(6);
            lngInput.value = pos.lng.toFixed(6);
        });

        map.on('click', (e) => {
            const pos = e.latlng;
            marker.setLatLng(pos);
            latInput.value = pos.lat.toFixed(6);
            lngInput.value = pos.lng.toFixed(6);
        });
    }
</script>
