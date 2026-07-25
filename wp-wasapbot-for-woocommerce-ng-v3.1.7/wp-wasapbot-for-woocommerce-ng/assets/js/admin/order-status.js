jQuery(document).ready(function($) {
    
    // Check if we're on an order page
    if (typeof wpwfwOrder === 'undefined') {
        return;
    }
    
    // Function to check processing status
    function checkProcessingStatus(orderId) {
        $.ajax({
            url: wpwfwOrder.ajax_url,
            type: 'POST',
            data: {
                action: 'wpwfw_check_processing_status',
                order_id: orderId,
                nonce: wpwfwOrder.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.is_processing) {
                        // Show sending indicator
                        showProcessingIndicator(orderId);
                        // Check again after interval
                        setTimeout(function() {
                            checkProcessingStatus(orderId);
                        }, wpwfwOrder.poll_interval);
                    } else {
                        // Hide sending indicator
                        hideProcessingIndicator(orderId);
                    }
                }
            },
            error: function() {
                // Hide sending indicator on error
                hideProcessingIndicator(orderId);
            }
        });
    }
    
    // Show sending indicator
    function showProcessingIndicator(orderId) {
        var indicator = $('.wpwfw-processing-indicator-' + orderId);
        if (indicator.length === 0) {
            var targetElement = $('#wpwfw-order-meta-' + orderId);
            // Fallback to any order meta container if specific element doesn't exist
            if (targetElement.length === 0) {
                targetElement = $('.wpwfw-order-meta[data-order-id="' + orderId + '"]');
            }
            // Final fallback to page body if no specific container found
            if (targetElement.length === 0) {
                targetElement = $('body');
            }
            
            if (targetElement.length > 0) {
                var spinnerHtml = '<span class="wpwfw-processing-indicator wpwfw-processing-indicator-' + orderId + '">' +
                                 '<i class="fas fa-spinner wpwfw-spinner"></i> Sending...' +
                                 '</span>';
                targetElement.append(spinnerHtml);
            }
        }
    }
    
    // Hide sending indicator
    function hideProcessingIndicator(orderId) {
        $('.wpwfw-processing-indicator-' + orderId).remove();
    }
    
    // Initialize status checking for orders
    $('.wpwfw-order-meta').each(function() {
        var orderId = $(this).data('order-id');
        if (orderId) {
            // Initial check
            checkProcessingStatus(orderId);
        }
    });
    
});