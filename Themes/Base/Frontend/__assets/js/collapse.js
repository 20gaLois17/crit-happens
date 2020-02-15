/**
 * Adds a simple collapse functionality.
 *
 * Usage: Collapse triggers will have the 'data-collapse' attribute. ( <element data-collapse='' > )
 * The next element on the same level as the trigger will be toggled with the class 'collapsed'.
 *
 * Optional: if 'data-collapse' is set to a value, triggers will only be applied if screen-width < value.
 *
 * Extension: If the collapse trigger element has an attribute data-collapse-target and that target contains a css class
 * then all elements with that class will be triggered
 *
 * Extension 2: The state of open containers is kept in session storage and gets restored on page load
 */

(function () {
    if (typeof Oforge !== 'undefined') {
        Oforge.register({
            name: 'collapse',
            selector: '[data-collapse]',
            init: function () {
                const triggers = document.querySelectorAll('[data-collapse]');

                // check if this is specified viewport
                triggers.forEach(function (trigger) {
                    if (sessionStorage.getItem(trigger.dataset.collapseTarget)) {
                        trigger.classList.toggle('active');
                        let targets = document.querySelectorAll(trigger.dataset.collapseTarget);
                        targets.forEach(function (target) {
                            target.classList.toggle('collapsed');
                        });
                    }
                    let clientWidth = document.documentElement.clientWidth;
                    let triggerWidth = parseInt(trigger.dataset.collapse);
                    if (Number.isNaN(triggerWidth) || clientWidth < triggerWidth) {
                        trigger.addEventListener('click', function () {
                            if (this.hasAttribute('data-collapse-target')) {
                                trigger.classList.toggle('active');
                                let targets = document.querySelectorAll(trigger.dataset.collapseTarget);
                                targets.forEach(function (target) {
                                    target.classList.toggle('collapsed');
                                });
                                if (sessionStorage.getItem(trigger.dataset.collapseTarget)) {
                                    sessionStorage.removeItem(trigger.dataset.collapseTarget)
                                } else {
                                    sessionStorage.setItem(trigger.dataset.collapseTarget, "1");
                                }
                            } else {
                                this.classList.toggle('active');
                                this.nextElementSibling.classList.toggle('collapsed');
                            }
                        });
                    }
                });
            }
        });
    } else {
        console.warn('Oforge is not defined. Module cannot be registered.');
    }
})();
