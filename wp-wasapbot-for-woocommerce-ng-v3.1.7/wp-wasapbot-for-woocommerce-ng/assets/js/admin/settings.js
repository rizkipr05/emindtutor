jQuery(document).ready(function($) {
    
    // Initialize Select2 dropdowns
    if (typeof $.fn.select2 !== 'undefined') {
        $('.wpwfw-select2').select2({
            width: '100%'
        });
    }
    
    // Settings form handling
    $('.wpwfw-settings-form').on('submit', function(e) {
        // Add any settings-specific form validation here
        console.log('Settings form submitted');
    });
    
    // Tab switching functionality
    $('.wpwfw-tab-nav').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        
        // Remove active class from all tabs and content
        $('.wpwfw-tab-nav').removeClass('active');
        $('.wpwfw-tab-content').removeClass('active');
        
        // Add active class to clicked tab and corresponding content
        $(this).addClass('active');
        $(target).addClass('active');
    });
    
});