/**
 *  Scripts for jc_intern.
 */

$(document).ready(function () {
    var cooldown = false;

    $('.slider-2d').click(function (e) {
        // Set a safety-cooldown of 500ms to disable unwanted double-clicks.
        // Check if slider is inactive.
        if ($(this).hasClass('inactive') || cooldown) {
            e.preventDefault();
            return false;
        }

        cooldown = true;
        setTimeout(function() {cooldown = false;}, 500);

        var functionName = window[$(this).data('function')];

        if($.isFunction(functionName)) {
            var checked = functionName(this);

            $(this).find('input[type="checkbox"]').prop('checked', checked);
        }
    });
});