/**
 * Lazy Load Select2 - Load only when dropdown is clicked
 */
jQuery(document).ready(function($) {
    
    var select2Loaded = false;
    
    // Function to load Select2 dynamically
    function loadSelect2() {
        if (select2Loaded) {
            return Promise.resolve();
        }
        
        return new Promise(function(resolve, reject) {
            // Load Select2 CSS
            var cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css';
            document.head.appendChild(cssLink);
            
            // Load Select2 JS
            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js';
            script.onload = function() {
                select2Loaded = true;
                resolve();
            };
            script.onerror = function() {
                reject('Failed to load Select2');
            };
            document.head.appendChild(script);
        });
    }
    
    // Initialize lazy loading for select elements
    $('.wpwfw-lazy-select2').one('click focus', function() {
        var $select = $(this);
        
        loadSelect2().then(function() {
            $select.select2({
                width: '100%'
            });
            // Trigger the click/focus again to open the dropdown
            $select.trigger('click').trigger('focus');
        }).catch(function(error) {
            console.error('WPWFW: Failed to load Select2', error);
        });
    });
    
});