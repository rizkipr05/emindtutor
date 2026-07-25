var $ = jQuery;

$(function($) {

  if ( !window.intlTelInput || !window.wpwfwCheckout ) {
    return;
  }

  if ( $( "#billing_phone" ).length ) {
    var defaultCountry = String( wpwfwCheckout.defaultCountry || 'us' ).toLowerCase();
    var allowedCountries = Array.isArray( wpwfwCheckout.allowedCountries ) ? wpwfwCheckout.allowedCountries.map( function( country ) {
      return String( country || '' ).toLowerCase();
    } ) : [];

    $( '#billing_phone_field' ).addClass( 'wpwfw-pv' );

    var wpwfw_phoneIntl = window.intlTelInput( document.querySelector("#billing_phone"), {
      initialCountry: defaultCountry,
      onlyCountries: allowedCountries,
      utilsScript: wpwfwCheckout.utilsScript,
    } );

    window.wpwfw_phoneIntl = wpwfw_phoneIntl;		

    // Simple approach - only format on form submission, not during typing
    $( '.intl-tel-input' ).css('display', 'block');
    
    // Set country based on billing country
    $( document ).on('change', '#billing_country', function(e) {
      var country = String( this.value || '' ).toLowerCase();
      if ( country ) {
        wpwfw_phoneIntl.setCountry( country );
      }
    });
    var currentCountry = String( $( '#billing_country' ).val() || '' ).toLowerCase();
    if ( currentCountry ) {
      wpwfw_phoneIntl.setCountry( currentCountry );
    }

    // Only format the phone number when the checkout form is submitted
    $('.woocommerce-checkout').on('checkout_place_order', function() {
      if ( wpwfw_phoneIntl.isValidNumber() ) {
        var formattedNumber = wpwfw_phoneIntl.getNumber().replace('+','');
        if (formattedNumber) {
          $( '#billing_phone' ).val( formattedNumber );
        }
      }
      return true; // Allow form submission to continue
    });

  }
});
