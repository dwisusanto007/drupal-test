/**
 * @file
 * Mytheme JavaScript behaviors.
 */

(function (Drupal) {

  'use strict';

  /**
   * Mytheme base behavior.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.mytheme = {
    attach: function (context, settings) {
      // Mobile navigation toggle.
      const nav = context.querySelector('.layout-header__nav');
      const header = context.querySelector('.layout-header');

      if (header && nav && !header.dataset.mythemeInit) {
        header.dataset.mythemeInit = '1';

        // Create toggle button for mobile.
        const toggle = document.createElement('button');
        toggle.className = 'layout-header__nav-toggle';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-controls', 'primary-nav');
        toggle.textContent = Drupal.t('Menu');

        nav.id = 'primary-nav';
        header.querySelector('.layout-header__inner').appendChild(toggle);

        toggle.addEventListener('click', function () {
          const expanded = toggle.getAttribute('aria-expanded') === 'true';
          toggle.setAttribute('aria-expanded', String(!expanded));
          nav.classList.toggle('is-open', !expanded);
        });
      }
    }
  };

})(Drupal);
