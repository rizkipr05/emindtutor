jQuery( document ).ready( function( $ ) {

    function resetButton( message, timeout, isError ) {
        var $button = $( '#wpwfw-sample-button' );
        var $result = $( '#sample-button-result' );

        if ( $result.length ) {
            $result.remove();
        }

        if ( message ) {
            var $status = $( '<span id="sample-button-result"></span>' )
                .css( {
                    'background-color': isError ? '#df4144' : '#46b450',
                    'color': '#000',
                    'white-space': 'nowrap',
                    'border-radius': '6px',
                    'margin-left': '10px',
                    'font-size': '10px',
                    'padding': '5px 10px'
                } )
                .text( message );

            $button.after( $status );
        }

        setTimeout( function() {
            $button.html( 'Send' ).prop( 'disabled', false );
            $( '#sample-button-result' ).remove();
        }, timeout || 3000 );
    }

    $( '#wpwfw-sample-button' ).on( 'click', function(e) {
        e.preventDefault();

        var $button = $( this );
        var phone = $( '#wpwfw-sample-receipient' ).val();
        var msg = $( '#wpwfw-sample-msg' ).val();
        var subdomain = $( '.wpwfw_subdomain' ).val();
        var instance_id = $( '.wpwfw_instance_id' ).val();
        var access_token = $( '.wpwfw_access_token' ).val();

        phone = String( phone || '' ).replace(/[^0-9]/g, '');
        $button.html( 'Sending...' ).prop( 'disabled', true );

        if ( subdomain == '' || access_token == '' || instance_id == '' || phone == '' ) {
            resetButton( 'Please fill all required fields', 3000, true );
            return;
        }

        jQuery.ajax( {
            type: "POST",
            url: wpwfw_ajax.ajax_url,
            dataType: 'json',
            data: {
                action: 'wa_send_test_message',
                nonce: wpwfw_ajax.ajax_nonce,
                phone: phone,
                subdomain: subdomain,
                access_token: access_token,
                instance_id: instance_id,
                msg: msg,
            },
            success: function( response ) {
                var payload = response && response.data ? response.data : {};
                var message = payload.message || 'Message Sent';

                if ( response && response.success ) {
                    resetButton( message || 'Message Sent', 3000, false );
                    return;
                }

                resetButton( message || 'Internal error', 3000, true );
            },
            error: function( xhr ) {
                var message = 'Internal error';

                if ( xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message ) {
                    message = String( xhr.responseJSON.data.message );
                } else if ( xhr && xhr.responseText ) {
                    try {
                        var parsed = JSON.parse( xhr.responseText );
                        if ( parsed && parsed.data && parsed.data.message ) {
                            message = String( parsed.data.message );
                        }
                    } catch ( err ) {
                        // keep default message
                    }
                }

                resetButton( message, 3000, true );
            }

        } );
    });


} );
