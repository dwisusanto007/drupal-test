<?php

declare(strict_types=1);

namespace Drupal\vox_blocks\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
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
      'banner_title' => 'Welcome',
      'banner_subtitle' => 'Stay updated with the latest events and news.',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      'banner_title' => [
        '#markup' => $this->t($this->configuration['banner_title']),
      ],
      'banner_subtitle' => [
        '#markup' => $this->t($this->configuration['banner_subtitle']),
      ],
      '#cache' => [
        'max-age' => 3600,
      ],
    ];
  }

}
