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
 * Provides the Homepage Latest News cards block (2 items).
 */
#[Block(
  id: 'vox_homepage_news_block',
  admin_label: new TranslatableMarkup('Vox Homepage News Cards'),
  category: new TranslatableMarkup('Vox Custom'),
)]
class HomepageNewsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a HomepageNewsBlock instance.
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
      ->condition('type', 'news')
      ->condition('status', 1)
      ->sort('field_publish_date', 'DESC')
      ->range(0, 2)
      ->accessCheck(TRUE)
      ->execute();

    if (empty($nids)) {
      return [
        '#cache' => ['tags' => ['node_list:news']],
      ];
    }

    $nodes = $storage->loadMultiple($nids);
    $view_builder = $this->entityTypeManager->getViewBuilder('node');
    $items = [];

    foreach ($nodes as $node) {
      $items[] = $view_builder->view($node, 'card');
    }

    $cache_tags = ['node_list:news'];
    foreach ($nodes as $node) {
      $cache_tags = array_merge($cache_tags, $node->getCacheTags());
    }

    return [
      'news_cards' => $items,
      '#cache' => [
        'tags' => $cache_tags,
        'contexts' => ['user.permissions'],
      ],
    ];
  }

}
