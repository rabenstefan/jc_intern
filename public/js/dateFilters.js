+function($) {
    /*var filters = [
        {'singular': 'birthday', 'plural': 'birthdays'},
        {'singular': 'gig', 'plural': 'gigs'},
        {'singular': 'rehearsal', 'plural': 'rehearsals'}
    ];*/


    window.dateFilters = {
        'availableFilters': [],

        'hideByType': function (singular, plural) {
            $('.event.event-' + singular).parent('.row.list-item').hide(); //For List View
            $('.fc-event.event-' + singular).hide(); // For Calendar View
            $('#toggle-' + plural).removeClass('btn-pressed');
            Cookies.set('EventFilterActive_' + singular, 'hidden');
        },

        'showByType': function (singular, plural) {
            $('.event.event-' + singular).parent('.row.list-item').show();
            $('.fc-event.event-' + singular).show();
            $('#toggle-' + plural).addClass('btn-pressed');
            Cookies.set('EventFilterActive_' + singular, 'visible');
        },

        'showAll': function () {
            $.each(dateFilters.availableFilters, function(n, filter){
                dateFilters.showByType(filter.singular, filter.plural);
            });
        },

        'toggleByType': function(singular, plural) {
            // We check hasClass and not the Cookie, to not confuse people who use multiple tabs/windows.
            if ($('#toggle-' + plural).hasClass('btn-pressed')) {
                // Previously shown. Hide!
                dateFilters.hideByType(singular, plural);
            } else {
                // Hidden. Show!
                dateFilters.showByType(singular, plural);
            }
        },

        'applyFromCookie' : function(singular, plural) {
            var visibility = Cookies.get('EventFilterActive_'+singular);
            if (!(undefined === visibility)) {
                if ('hidden' === visibility) {
                    dateFilters.hideByType(singular, plural);
                } else if ('visible'  === visibility) {
                    dateFilters.showByType(singular, plural);
                }
            }
        },

        'applyAllFromCookie': function() {
            $.each(dateFilters.availableFilters, function(n, filter){
                dateFilters.applyFromCookie(filter.singular, filter.plural);
            });
        },

        'prepareButtons': function() {

            $.each(dateFilters.availableFilters, function(n, filter){
                $('#toggle-'+filter.plural).click(function() {
                    dateFilters.toggleByType(filter.singular, filter.plural);
                });
            });
            $('#toggle-all').click(function () {
                dateFilters.showAll();
            });
        }
    }
}(jQuery);
