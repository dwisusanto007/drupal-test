<?php

declare(strict_types=1);

namespace Drupal\vox_blocks\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a full-width Banner block for reuse across all pages.
 */
#[Block(
  id: 'vox_banner_block',
  admin_label: new TranslatableMarkup('Vox Banner'),
  category: new TranslatableMarkup('Vox Custom'),
)]
class BannerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'banner_title'    => 'Welcome to Vox Teneo',
      'banner_subtitle' => 'We create effective solutions.',
      'banner_image'    => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form = parent::blockForm($form, $form_state);

    $form['banner_title'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Banner title'),
      '#default_value' => $this->configuration['banner_title'],
      '#required'      => TRUE,
    ];

    $form['banner_subtitle'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Banner subtitle'),
      '#default_value' => $this->configuration['banner_subtitle'],
    ];

    $form['banner_image'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Background image URL'),
      '#description'   => $this->t('Absolute URL to the background image, e.g. /sites/default/files/banner.jpg'),
      '#default_value' => $this->configuration['banner_image'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['banner_title']    = $form_state->getValue('banner_title');
    $this->configuration['banner_subtitle'] = $form_state->getValue('banner_subtitle');
    $this->configuration['banner_image']    = $form_state->getValue('banner_image');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      'banner_title' => [
        '#markup' => $this->configuration['banner_title'],
      ],
      'banner_subtitle' => [
        '#markup' => $this->configuration['banner_subtitle'],
      ],
      'banner_image' => [
        '#markup' => $this->configuration['banner_image'],
      ],
      '#cache' => [
        'max-age' => 3600,
      ],
    ];
  }

}
