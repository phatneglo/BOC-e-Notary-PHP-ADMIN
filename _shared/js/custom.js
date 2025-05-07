window.addEventListener('load', function() {
    // Load CSS files
    loadjs([
        "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css",
        "https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css",
        "https://unpkg.com/leaflet-geosearch@3.8.0/dist/geosearch.css"
    ], 'map-editor-css');

    // Load JavaScript files
    loadjs([
        "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js",
        "https://unpkg.com/leaflet-geosearch@3.8.0/dist/geosearch.umd.js"
    ], 'leaflet', {
        success: function() {
            // Load map-browser.js after Leaflet is loaded
            loadjs(["/_shared/js/map-browser.js"], 'map-browser', {
                success: function() {

                }
            });
        }
    });
});


// Check if the current path includes '/login'
if (window.location.pathname.includes('/login')) {
    // Extract the system abbreviation (e.g., 'EMS', 'UAC') from the URL
    const systemAbbreviation = window.location.pathname.split('/')[1].toLowerCase();

    // Check if dark mode is enabled
    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';

    // Determine the appropriate logo path
    const logoPath = isDarkMode
        ? `../_shared/images/title-logo/${systemAbbreviation}-logo-light.png`
        : `../_shared/images/title-logo/${systemAbbreviation}-logo.png`;

    // Find the logo container
    const logoDiv = document.querySelector('.login-box-msg');

    if (logoDiv) {
        // Create the image element
        const img = document.createElement('img');
        img.src = logoPath; // Set the logo path dynamically
        img.className = 'col-md-10 py-4 d-block mx-auto w-75';
        img.alt = `${systemAbbreviation.toUpperCase()} Logo`;

        // Prepend the image to the logo container
        logoDiv.prepend(img);
    }
}
