/**
 * @file
 * Behaviors for the Demo Pop-up.
 */
/* eslint-disable max-len */

(function (Drupal) {
  /**
   * Setup and attach the Demo Popup.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.demoPopup = {
    attach: function () {

      // Update demo btn to open Bootstrap modal.
      const demo_btn = document.querySelector('[href="#exampleModal"]');
      demo_btn.setAttribute('data-bs-toggle', 'modal');
      demo_btn.setAttribute('data-bs-target', '#exampleModal');
    }
  };
})(Drupal);
