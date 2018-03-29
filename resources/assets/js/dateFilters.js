+function($) {

    var cookie_name = 'activeDateFilters';

    /**
     * Check if arr1 is completely contained in arr2
     * @param arr1
     * @param arr2
     * @returns {boolean}
     */

    window.dateFilters = {
        'activeFilters' : {},

        'eventContainerIdentifier': '.list-item',

        'prepareHideFilter': function(name) {
            dateFilters.activeFilters[name].visible = false;
        },

        'prepareShowFilter': function(name) {
            dateFilters.activeFilters[name].visible = true;
        },

        'prepareToggleFilter': function (name) {
            if (true === dateFilters.activeFilters[name].visible) {
                dateFilters.prepareHideFilter(name);
            } else {
                dateFilters.prepareShowFilter(name);
            }
        },

        'prepareShowAll': function() {
            $.each(dateFilters.activeFilters, function(name) {
                dateFilters.prepareShowFilter(name);
            });
        },

        /**
         * Read the prepared filters and apply them to the current view.
         *
         * @param set_cookie Save the active filters to a cookie
         */
        'applyAllFilters': function(set_cookie = true) {
            // Show/Hide Toggle Buttons
            $.each(dateFilters.activeFilters, function (name, filter) {
                if (filter.visible === true) {
                    $('#toggle-' + filter.plural).addClass('btn-pressed');
                    $('#toggle-' + filter.plural).removeClass('btn-unpressed');
                } else {
                    $('#toggle-' + filter.plural).addClass('btn-unpressed');
                    $('#toggle-' + filter.plural).removeClass('btn-pressed');
                }
            });

            // Show/Hide elements in view
            $(dateFilters.eventContainerIdentifier).each(function (index, element) {
                var visible = true;
                var jEl = $(element);
                $.each(jEl.data('filters'), function (n, name) {
                    if (true !== dateFilters.activeFilters[name].visible) {
                        // Only be visible if all applicable filters are set to visible
                        visible = false;
                        return false; // break
                    }
                });

                if (visible) {
                    jEl.show();
                } else {
                    jEl.hide();
                }
            });

            if (true === set_cookie) {
                dateFilters.setCookie();
            }
        },

        'readCookie': function() {
            // Using $.extend will hopefully mitigate some incomplete cookies. It will also preserve settings not available in the current view (which might not be a preferred behaviour, but we are rolling with it for now)
            $.extend(dateFilters.activeFilters, Cookies.getJSON(cookie_name));
        },

        'setCookie': function() {
            Cookies.set(cookie_name, dateFilters.activeFilters);
        },

        /**
         * To be called in document.ready to attach functions to the buttons
         */
        'prepareButtons': function() {
            $.each(dateFilters.activeFilters, function(name, filter) {
                $('#toggle-'+filter.plural).click(function() {
                    dateFilters.prepareToggleFilter(name);
                    dateFilters.applyAllFilters();
                });
            });
            $('#toggle-all').click(function () {
                dateFilters.prepareShowAll();
                dateFilters.applyAllFilters();
            });
        }
    }
}(jQuery);
