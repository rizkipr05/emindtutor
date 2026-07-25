jQuery(document).ready(function($) {
    
    // Dashboard-specific functionality
    if (typeof wpwfwDashboard === 'undefined') {
        return;
    }
    
    // Bulk actions handling
    $('.wpwfw-bulk-action').on('click', function(e) {
        e.preventDefault();
        
        var action = $(this).data('action');
        var selectedOrders = [];
        
        $('.wpwfw-order-checkbox:checked').each(function() {
            selectedOrders.push($(this).val());
        });
        
        if (selectedOrders.length === 0) {
            alert('Please select at least one order.');
            return;
        }
        
        // Confirm bulk action
        if (!confirm('Are you sure you want to perform this action on ' + selectedOrders.length + ' orders?')) {
            return;
        }
        
        // Perform bulk action via AJAX
        $.ajax({
            url: wpwfwDashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'wpwfw_bulk_action',
                bulk_action: action,
                order_ids: selectedOrders,
                nonce: wpwfwDashboard.nonce
            },
            beforeSend: function() {
                $('.wpwfw-bulk-action').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Reload page or update UI
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('An error occurred while processing the bulk action.');
            },
            complete: function() {
                $('.wpwfw-bulk-action').prop('disabled', false);
            }
        });
    });
    
    // Select all checkbox functionality
    $('#wpwfw-select-all').on('change', function() {
        $('.wpwfw-order-checkbox').prop('checked', $(this).is(':checked'));
    });
    
    // Update select all checkbox when individual checkboxes change
    $('.wpwfw-order-checkbox').on('change', function() {
        var totalCheckboxes = $('.wpwfw-order-checkbox').length;
        var checkedCheckboxes = $('.wpwfw-order-checkbox:checked').length;
        $('#wpwfw-select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // Cancel single queue action
    $(document).on('click', '.wpwfw-cancel-queue', function(e){
        e.preventDefault();
        var $btn = $(this);
        var queueId = $btn.data('queue-id');
        if(!queueId) return;
        if(!confirm('Cancel this queued message?')) return;
        var originalText = $btn.html();
        $btn.prop('disabled', true).html('Cancelling...');
        $.post(wpwfwDashboard.ajax_url, {
            action: 'cancel_message_queue',
            actionId: queueId,
            isAjax: "true",
            nonce: typeof wpwfwDashboard !== 'undefined' ? wpwfwDashboard.nonce : ''
        }, function(resp){
            if(resp.success){
                $btn.html('Cancelled!');
                setTimeout(function(){ location.reload(); }, 900);
            }else{
                $btn.html('Cancel Failed');
                setTimeout(function(){
                    $btn.prop('disabled', false).html(originalText);
                }, 1500);
            }
        });
    });
    
    // Bulk clear all queues
    $(document).on('click', '.wpwfw-clear-all-queues', function(e){
        e.preventDefault();
        var $btn = $(this);
        if(!confirm('Cancel all queued messages?')) return;
        var originalText = $btn.html();
        $btn.prop('disabled', true).html('Cancelling...');
        $.post(wpwfwDashboard.ajax_url, {
            action: 'cancel_all_message_queues',
            isAjax: "true",
            nonce: typeof wpwfwDashboard !== 'undefined' ? wpwfwDashboard.nonce : ''
        }, function(resp){
            if(resp.success){
                $btn.html('All Cancelled!');
                setTimeout(function(){ location.reload(); }, 900);
            }else{
                $btn.html('Cancel Failed');
                setTimeout(function(){
                    $btn.prop('disabled', false).html(originalText);
                }, 1500);
            }
        });
    });
    
});