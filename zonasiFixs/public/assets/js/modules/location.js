import { state } from './state.js';
import { hitungJarak, formatJarak } from './utils.js';
import { displaySchoolList, showLoading, hideLoading } from './ui.js';

// Handle getting the current user location via Geolocation API
export function getCurrentLocation() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung Geolocation!');
        return;
    }

    showLoading();

    navigator.geolocation.getCurrentPosition(
        function (position) {
            setUserLocation(
                position.coords.latitude,
                position.coords.longitude
            );
            hideLoading();
        },
        function (error) {
            hideLoading();
            alert('Tidak dapat mengakses lokasi Anda. Pastikan Anda memberikan izin akses lokasi.');
            console.error('Geolocation error:', error);
        }
    );
}

// Set the user's location on the map and calculate distances
export function setUserLocation(lat, lng) {
    if (!state.map) return;

    // Remove old user marker
    if (state.userMarker) {
        state.map.removeLayer(state.userMarker);
    }

    // Add new user marker (red)
    state.userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(state.map);

    state.userMarker.bindPopup('<strong>Anda disini</strong>').openPopup();

    // Pan map to user location
    state.map.setView([lat, lng], 14);

    // Calculate distances and update list
    calculateDistances(lat, lng);
}

// Calculate distances from user location to all schools and update the list
export function calculateDistances(userLat, userLng) {
    // Calculate distance for each school
    let schoolsWithDistance = window.sekolahData.map(school => {
        const distance = hitungJarak(
            userLat, userLng,
            school.latitude, school.longitude
        );

        return {
            ...school,
            distance: distance,
            distanceFormatted: formatJarak(distance),
            inZone: distance <= parseInt(school.radius)
        };
    });

    // Apply current filters
    if (state.currentSearchSchool) {
        const keyword = state.currentSearchSchool.toLowerCase();
        schoolsWithDistance = schoolsWithDistance.filter(school => {
            const nama = school.nama.toLowerCase();
            const npsn = school.npsn ? school.npsn.toLowerCase() : '';
            return nama.includes(keyword) || npsn.includes(keyword);
        });
    }

    if (state.currentFilterKecamatan) {
        schoolsWithDistance = schoolsWithDistance.filter(s => s.kecamatan === state.currentFilterKecamatan);
    }

    // Sort by distance
    schoolsWithDistance.sort((a, b) => a.distance - b.distance);

    // Update school list
    displaySchoolList(schoolsWithDistance, true);
}
