/**
 * TradePress Pro Admin Scripts
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * TradePress Pro Admin Object
     */
    var TradePressProAdmin = {

        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Add any event handlers here
            $(document).on('click', '.tradepress-pro-prompt', this.handleProPromptClick);
        },

        /**
         * Handle Pro prompt click
         */
        handleProPromptClick: function(e) {
            e.preventDefault();
            
            // Redirect to license page or show upgrade modal
            if (confirm('This is a Pro feature. Would you like to activate your license?')) {
                window.location.href = ajaxurl.replace('admin-ajax.php', 'admin.php?page=tradepress-pro-license');
            }
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        TradePressProAdmin.init();
    });

})(jQuery);
