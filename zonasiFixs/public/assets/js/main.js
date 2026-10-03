import { state } from './modules/state.js';
import { initMap, searchAndNavigate } from './modules/map.js';
import { selectSchool } from './modules/ui.js';
import { filterSchools, clearSchoolSearch, resetAllFilters, showSchoolSuggestions, hideSchoolSuggestions, selectSchoolFromSuggestion } from './modules/search.js';
import { getCurrentLocation } from './modules/location.js';

// EXPOSE FUNCTIONS TO WINDOW FOR COMPATIBILITY
// These are used in inline onclick handlers in PHP templates
window.selectSchool = selectSchool;
window.clearSchoolSearch = clearSchoolSearch;
window.resetAllFilters = resetAllFilters;
window.getCurrentLocation = getCurrentLocation;
window.selectSchoolFromSuggestion = selectSchoolFromSuggestion;
window.resetDirectoryFilters = resetDirectoryFilters;
window.searchAndNavigate = searchAndNavigate; // Expose for search-to-location

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Map
    initMap();

    // Event Listeners for Search Input
    const searchSchoolInput = document.getElementById('searchSchool');
    if (searchSchoolInput) {
        searchSchoolInput.addEventListener('input', function (e) {
            const keyword = e.target.value.trim();
            state.currentSearchSchool = keyword;

            // Tampilkan/sembunyikan tombol clear
            const clearBtn = document.getElementById('clearSchoolSearch');
            if (clearBtn) clearBtn.style.display = keyword ? 'block' : 'none';

            // Tampilkan suggestions
            if (keyword.length >= 2) {
                showSchoolSuggestions(keyword);
            } else {
                hideSchoolSuggestions();
            }
            filterSchools();
        });

        // Add Enter key handler for search-to-location navigation
        searchSchoolInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = e.target.value.trim();
                if (keyword.length >= 2) {
                    hideSchoolSuggestions();
                    searchAndNavigate(keyword);
                }
            }
        });
    }

    // Toggle zones
    const showZonesCheckbox = document.getElementById('showZones');
    if (showZonesCheckbox) {
        showZonesCheckbox.addEventListener('change', function () {
            filterSchools();
        });
    }

    // Filter kecamatan
    const filterKecamatanSelect = document.getElementById('filterKecamatan');
    if (filterKecamatanSelect) {
        filterKecamatanSelect.addEventListener('change', function () {
            state.currentFilterKecamatan = this.value;
            filterSchools();
        });
    }

    // Close suggestions when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#searchSchool') && !e.target.closest('#schoolSuggestions')) {
            hideSchoolSuggestions();
        }
    });

    // Directory Filtering Logic
    const dirSearch = document.getElementById('dirSearch');
    const dirFilterKec = document.getElementById('dirFilterKec');

    if (dirSearch && dirFilterKec) {
        const filterDir = () => {
            const searchVal = dirSearch.value.toLowerCase();
            const kecVal = dirFilterKec.value.toLowerCase();
            const items = document.querySelectorAll('.directory-item');
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-nama');
                const kec = item.getAttribute('data-kecamatan');
                const matchesSearch = name.includes(searchVal);
                const matchesKec = kecVal === '' || kec === kecVal;

                if (matchesSearch && matchesKec) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            const dirCount = document.getElementById('dirCount');
            if (dirCount) dirCount.textContent = visibleCount;

            const dirEmpty = document.getElementById('dirEmpty');
            if (dirEmpty) {
                if (visibleCount === 0) {
                    dirEmpty.classList.remove('d-none');
                } else {
                    dirEmpty.classList.add('d-none');
                }
            }
        };

        dirSearch.addEventListener('input', filterDir);
        dirFilterKec.addEventListener('change', filterDir);
    }

    console.log('Sistem Zonasi SMA Padang loaded successfully!');
    if (window.sekolahData) {
        console.log('Total sekolah:', window.sekolahData.length);
    }
});

// Reset all filters in the directory section
export function resetDirectoryFilters() {
    const dirSearch = document.getElementById('dirSearch');
    const dirFilterKec = document.getElementById('dirFilterKec');
    if (dirSearch) dirSearch.value = '';
    if (dirFilterKec) dirFilterKec.value = '';

    // Trigger filter
    const event = new Event('input');
    if (dirSearch) dirSearch.dispatchEvent(event);
}
