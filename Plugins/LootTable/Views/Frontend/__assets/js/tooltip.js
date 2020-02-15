(function () {
    if (typeof Oforge !== 'undefined') {
        Oforge.register({
            name: 'tooltip',
            selector: '[data-tippy-content]',
            init: function () {
                tippy('[data-tippy-content]');
            }
        });
    } else {
        console.warn('Oforge is not defined. Module cannot be registered.');
    }
})();
