import { state } from './state.js';
import { displaySchoolsOnMap } from './map.js';
import { displaySchoolList, selectSchool } from './ui.js';
import { highlightText } from './utils.js';

// Filter schools based on current state (search keyword and kecamatan)
export function filterSchools() {
    let filtered = [...window.sekolahData];

    // Filter name/NPSN
    if (state.currentSearchSchool) {
        const keyword = state.currentSearchSchool.toLowerCase();
        filtered = filtered.filter(school => {
            const nama = school.nama.toLowerCase();
            const npsn = school.npsn ? school.npsn.toLowerCase() : '';
            return nama.includes(keyword) || npsn.includes(keyword);
        });
    }

    // Filter kecamatan
    if (state.currentFilterKecamatan) {
        filtered = filtered.filter(s => s.kecamatan === state.currentFilterKecamatan);
    }

    // Update display
    displaySchoolsOnMap(filtered);
    displaySchoolList(filtered);
}

// Show school suggestions in the dropdown
export function showSchoolSuggestions(keyword) {
    const suggestions = document.getElementById('schoolSuggestions');
    if (!suggestions) return;

    const filtered = window.sekolahData.filter(school => {
        const nama = school.nama.toLowerCase();
        const npsn = school.npsn ? school.npsn.toLowerCase() : '';
        const key = keyword.toLowerCase();
        return nama.includes(key) || npsn.includes(key);
    });

    if (filtered.length === 0) {
        suggestions.innerHTML = '<div class="suggestion-item text-muted">Tidak ada hasil</div>';
        suggestions.classList.add('show');
        return;
    }

    let html = '';
    filtered.slice(0, 5).forEach(school => {
        const highlightedName = highlightText(school.nama, keyword);
        const npsnText = school.npsn ? `<small class="text-muted">NPSN: ${school.npsn}</small>` : '';

        html += `
            <div class="suggestion-item" onclick="window.selectSchoolFromSuggestion(${school.id})">
                <div class="fw-bold">${highlightedName}</div>
                <small class="text-muted">${school.kecamatan}</small>
                ${npsnText}
            </div>
        `;
    });

    suggestions.innerHTML = html;
    suggestions.classList.add('show');
}

// Hide school suggestions
export function hideSchoolSuggestions() {
    const suggestions = document.getElementById('schoolSuggestions');
    if (suggestions) {
        suggestions.classList.remove('show');
    }
}

// Select a school from suggestions
export function selectSchoolFromSuggestion(schoolId) {
    const school = window.sekolahData.find(s => s.id == schoolId);
    if (school) {
        const searchInput = document.getElementById('searchSchool');
        if (searchInput) {
            searchInput.value = school.nama;
            state.currentSearchSchool = school.nama;
        }

        hideSchoolSuggestions();

        const clearBtn = document.getElementById('clearSchoolSearch');
        if (clearBtn) clearBtn.style.display = 'block';

        // Filter dan zoom ke sekolah
        filterSchools();
        selectSchool(schoolId);
    }
}

// Clear the school search input and reset related state
export function clearSchoolSearch() {
    const searchInput = document.getElementById('searchSchool');
    if (searchInput) searchInput.value = '';

    state.currentSearchSchool = '';

    const clearBtn = document.getElementById('clearSchoolSearch');
    if (clearBtn) clearBtn.style.display = 'none';

    hideSchoolSuggestions();
    filterSchools();
}

// Reset all filters to initial state
export function resetAllFilters() {
    const searchInput = document.getElementById('searchSchool');
    if (searchInput) searchInput.value = '';

    const filterKecamatan = document.getElementById('filterKecamatan');
    if (filterKecamatan) filterKecamatan.value = '';

    const clearBtn = document.getElementById('clearSchoolSearch');
    if (clearBtn) clearBtn.style.display = 'none';

    state.currentSearchSchool = '';
    state.currentFilterKecamatan = '';

    hideSchoolSuggestions();

    // Remove user marker
    if (state.userMarker && state.map) {
        state.map.removeLayer(state.userMarker);
        state.userMarker = null;
    }

    // Reset map view
    if (state.map) {
        state.map.setView([-0.9371, 100.3600], 13);
    }

    // Display all schools
    displaySchoolsOnMap();
    displaySchoolList(window.sekolahData);
}
