/**
 * Lazy Load Phone Validation - Load only when phone field is focused
 */
jQuery(document).ready(function($) {
    
    console.log('WPWFW: Lazy phone validation script loaded');
    var phoneValidationLoaded = false;
    
    // Function to load phone validation assets dynamically
    function loadPhoneValidation() {
        if (phoneValidationLoaded) {
            return Promise.resolve();
        }
        
        if (typeof wpwfwLazy === 'undefined') {
            console.error('WPWFW: wpwfwLazy object not found - phone validation may not work correctly');
            return Promise.reject('wpwfwLazy not defined');
        }
        
        return new Promise(function(resolve, reject) {
            var assetsLoaded = 0;
            var totalAssets = 2; // CSS and JS
            
            function checkComplete() {
                assetsLoaded++;
                if (assetsLoaded === totalAssets) {
                    phoneValidationLoaded = true;
                    resolve();
                }
            }
            
            // Load intlTelInput CSS
            var cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = wpwfwLazy.intl_css;
            cssLink.onload = checkComplete;
            cssLink.onerror = function() {
                reject('Failed to load intlTelInput CSS');
            };
            document.head.appendChild(cssLink);
            
            // Load intlTelInput JS
            var script = document.createElement('script');
            script.src = wpwfwLazy.intl_js;
            script.onload = checkComplete;
            script.onerror = function() {
                reject('Failed to load intlTelInput JS');
            };
            document.head.appendChild(script);
        });
    }
    
    // Initialize lazy loading for phone fields
    $('#billing_phone').one('focus click', function() {
        console.log('WPWFW: Phone field focused, starting lazy loading');
        var $phoneField = $(this);
        
        // Show loading indicator
        $phoneField.parent().append('<span class="wpwfw-phone-loading">Loading validation...</span>');
        
        loadPhoneValidation().then(function() {
            console.log('WPWFW: Phone validation assets loaded successfully');
            // Remove loading indicator
            $('.wpwfw-phone-loading').remove();
            
            // Initialize phone validation
            if (window.intlTelInput) {
                $phoneField.parent().addClass('wpwfw-pv');
                
                // Use WooCommerce data from wpwfwLazy (localized in PHP)
                var defaultCountry = wpwfwLazy.defaultCountry || 'US';
                var allowedCountries = wpwfwLazy.allowedCountries || [];
                
                var phoneIntl = window.intlTelInput($phoneField[0], {
                    initialCountry: defaultCountry,
                    onlyCountries: allowedCountries,
                    utilsScript: wpwfwLazy.utils_js,
                });
                
                window.wpwfw_phoneIntl = phoneIntl;
                
                // Set up event handlers
                phoneIntl.promise.then(function() {
                    // Only set the formatted number if the field is empty or if we have a valid number
                    var currentValue = $phoneField.val();
                    if (!currentValue || phoneIntl.isValidNumber()) {
                      var formattedNumber = phoneIntl.getNumber().replace('+','');
                      if (formattedNumber) {
                        $phoneField.val( formattedNumber );
                      }
                    }
                });
                
                $phoneField.on('blur', function() {
                    // Only format the number if it's valid, otherwise keep user input
                    if ( phoneIntl.isValidNumber() ) {
                      var formattedNumber = phoneIntl.getNumber().replace( '+','' );
                      if (formattedNumber) {
                        $(this).val( formattedNumber );
                      }
                    }
                });
                
                $('.intl-tel-input').css('display', 'block');
                
                $(document).on('change', '#billing_country', function() {
                    phoneIntl.setCountry(this.value);
                });
                
                phoneIntl.setCountry($('#billing_country').val());
                
                // Trigger focus again to activate the field
                $phoneField.trigger('focus');
            }
        }).catch(function(error) {
            $('.wpwfw-phone-loading').remove();
            console.error('WPWFW: Failed to load phone validation', error);
        });
    });
    
});