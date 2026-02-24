<?php

declare(strict_types=1);

namespace Drupal\vox_blocks\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the Homepage Latest Event card block.
 */
#[Block(
  id: 'vox_homepage_event_block',
  admin_label: new TranslatableMarkup('Vox Homepage Event Card'),
  category: new TranslatableMarkup('Vox Custom'),
)]
class HomepageEventBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a HomepageEventBlock instance.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    mixed $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $storage = $this->entityTypeManager->getStorage('node');

    $nids = $storage->getQuery()
      ->condition('type', 'event')
      ->condition('status', 1)
      ->sort('field_event_date', 'DESC')
      ->range(0, 1)
      ->accessCheck(TRUE)
      ->execute();

    if (empty($nids)) {
      return [
        '#cache' => ['tags' => ['node_list:event']],
      ];
    }

    $node = $storage->load(reset($nids));
    $view_builder = $this->entityTypeManager->getViewBuilder('node');

    return [
      'event_card' => $view_builder->view($node, 'card'),
      '#cache' => [
        'tags' => array_merge($node->getCacheTags(), ['node_list:event']),
        'contexts' => ['user.permissions'],
      ],
    ];
  }

}
