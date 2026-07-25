var $ = jQuery;

$(function($) {

  if ( $( "#billing_phone" ) && $( "#billing_phone" ).length ) {

    $( '#billing_phone_field' ).addClass( 'wpwfw-pv' );

    var wpwfw_phoneIntl = window.intlTelInput( document.querySelector("#billing_phone"), {
      initialCountry: wpwfwPvData.defaultCountry,
      onlyCountries: wpwfwPvData.onlyCountries,
      utilsScript: wpwfwPvData.utilsScript,
    } );

    window.wpwfw_phoneIntl = wpwfw_phoneIntl;		

    wpwfw_phoneIntl.promise.then(function() {
      // Only set the formatted number if the field is empty or if we have a valid number
      var currentValue = $( '#billing_phone' ).val();
      if (!currentValue || wpwfw_phoneIntl.isValidNumber()) {
        var formattedNumber = wpwfw_phoneIntl.getNumber().replace('+','');
        if (formattedNumber) {
          $( '#billing_phone' ).val( formattedNumber );
        }
      }
    });		

    $( '#billing_phone' ).on( 'blur', function () {
        // Only format the number if it's valid, otherwise keep user input
        if ( wpwfw_phoneIntl.isValidNumber() ) {
          var formattedNumber = wpwfw_phoneIntl.getNumber().replace( '+','' );
          if (formattedNumber) {
            $( this ).val( formattedNumber );
          }
        }
    });

    $( '.intl-tel-input' ).css('display', 'block');
    $( document ).on('change', '#billing_country', function(e) {
      wpwfw_phoneIntl.setCountry( this.value );
    });
    wpwfw_phoneIntl.setCountry( $( '#billing_country' ).val() );

    // Frontend validation
    let formCheckout = $('.woocommerce-checkout').on(
      'checkout_place_order',
      function() {
        wpwpfw_validate_form( formCheckout );
      }
    )

    function wpwpfw_validate_form( form ) {

      let phone = wpwfw_validate_phone_number( wpwfw_phoneIntl );
      if ( $( '.wpwfw-pv input' ).length == 0) {
        return;
      }
  
      // Set appropriate error message
      console.log( phone );
  
    }
  
    function wpwfw_validate_phone_number( input ) {
      const phone = input;
      let result = {
        status: false,
        message: '`billing_phone` validation error',
      };
  
      if ( phone.isValidNumber() == true ) {
        result = {
          status: true,
          message: phone.getNumber(),
        }
      }
  
      else {
        let error_code = phone.getValidationError();
        result['message'] = error_code; // map
      }
  
      return result;
    }

  }
});