jQuery( document ).ready( function( $ ) {

    $( '#wpwfw-sample-button' ).on( 'click', function(e) {
        let phone = $( '#wpwfw-sample-receipient' ).val();
        let msg = $( '#wpwfw-sample-msg' ).val();
        let subdomain = $( '.wpwfw_subdomain' ).val();
        let instance_id = $( '.wpwfw_instance_id' ).val();
        let access_token = $( '.wpwfw_access_token' ).val();

        phone = phone.replace(/[^0-9]/g, '');
        // console.log( 'Sending sample message to ' + phone + ' (' + msg +') via [subdomain: ' + subdomain + ', access_token: ' + access_token + ', instance_id: ' + instance_id + ']' );
        
        $( '#wpwfw-sample-button' ).html( 'Sending...' ).prop( 'disabled', true );

        if ( subdomain == '' || access_token == '' || instance_id == '' || phone == '' ) {
            console.log( 'Insufficient parameters' );
            $( '#wpwfw-sample-button' ).html( 'Error' );
            $( '#wpwfw-sample-button' ).after('<span id="sample-button-result" style="background-color: #df4144;color: #000;white-space: nowrap;border-radius: 6px;margin-left: 10px;font-size:10px;padding: 5px 10px;">Please fill all required fields</span>');
            setTimeout( ()=> {
                $( '#wpwfw-sample-button' ).html( 'Send' ).prop( 'disabled', false );
                $('#sample-button-result').remove();
            }, 3000 );   
            e.preventDefault();
            return;
        }
        
        jQuery.ajax( {
            type: "POST",
            url: wpwfw_ajax.ajax_url,
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

                console.log('Success: ' + response );
                $( '#wpwfw-sample-button' ).html( 'Message Sent' );
                setTimeout( ()=> {
                    $( '#wpwfw-sample-button' ).html( 'Send' ).prop( 'disabled', false );
                }, 3000 );    

            },
            error: function( error ) {

                console.log('Error: ' + error.responseText );
                try {
                    response = JSON.parse( error.responseText );
                } catch (e) {
                    response = {};
                }
                
                let error_msg = 'Internal error';
                if ( 'message' in response.data ) {
                    // Sanitize the error message to prevent XSS
                    error_msg = $('<div>').text(response.data.message.toString()).html();
                    error_msg = error_msg.charAt(0).toUpperCase() + error_msg.slice(1);
                }
                $( '#wpwfw-sample-button' ).html( 'Error' );
                // Create element safely without direct HTML injection
                var errorSpan = $('<span id="sample-button-result">').css({
                    'background-color': '#df4144',
                    'color': '#000',
                    'white-space': 'nowrap',
                    'border-radius': '6px',
                    'margin-left': '10px',
                    'font-size': '10px',
                    'padding': '5px 10px'
                }).text(error_msg);
                $( '#wpwfw-sample-button' ).after(errorSpan);
                setTimeout( ()=> {
                    $( '#wpwfw-sample-button' ).html( 'Send' ).prop( 'disabled', false );
                    $('#sample-button-result').remove();
                }, 3000 ); 

            }

        } );
        e.preventDefault();
        return;
    });


} );