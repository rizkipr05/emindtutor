// Common utilities shared across admin and frontend
var WPWFW_Common = {
    
    // Format phone number
    formatPhoneNumber: function(phone) {
        return String(phone || '').replace(/[^0-9]/g, '');
    },
    
    // Show notification
    showNotification: function(message, type) {
        type = type || 'info';
        var notification = jQuery('<div></div>')
            .addClass('wpwfw-notification')
            .addClass('wpwfw-notification-' + type)
            .text(String(message || ''));
        
        jQuery('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(function() {
                jQuery(this).remove();
            });
        }, 5000);
    },
    
    // Debounce function
    debounce: function(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
};
