<?php

declare(strict_types=1);

namespace Drupal\vox_blocks\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a "Related Event/News" sidebar block for detail pages.
 *
 * Shows 1 latest Event + 1 latest News, excluding the current node.
 * Place in the 'sidebar' region with page visibility on /node/*.
 */
#[Block(
  id: 'vox_related_content_block',
  admin_label: new TranslatableMarkup('Vox Related Event/News'),
  category: new TranslatableMarkup('Vox Custom'),
)]
class RelatedContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * Constructs a RelatedContentBlock instance.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    mixed $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    RouteMatchInterface $route_match,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
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
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $storage = $this->entityTypeManager->getStorage('node');
    $view_builder = $this->entityTypeManager->getViewBuilder('node');

    // Exclude the current node from results.
    $current_node = $this->routeMatch->getParameter('node');
    $current_nid = $current_node?->id();

    $cache_tags = ['node_list:event', 'node_list:news'];
    $cards = [];

    // Latest 1 Event (exclude current node).
    $event_query = $storage->getQuery()
      ->condition('type', 'event')
      ->condition('status', 1)
      ->sort('field_event_date', 'DESC')
      ->range(0, 1)
      ->accessCheck(TRUE);

    if ($current_nid) {
      $event_query->condition('nid', $current_nid, '<>');
    }

    $event_nids = $event_query->execute();
    if ($event_nids) {
      $event_node = $storage->load(reset($event_nids));
      $cards[] = $view_builder->view($event_node, 'card');
      $cache_tags = array_merge($cache_tags, $event_node->getCacheTags());
    }

    // Latest 1 News (exclude current node).
    $news_query = $storage->getQuery()
      ->condition('type', 'news')
      ->condition('status', 1)
      ->sort('field_publish_date', 'DESC')
      ->range(0, 1)
      ->accessCheck(TRUE);

    if ($current_nid) {
      $news_query->condition('nid', $current_nid, '<>');
    }

    $news_nids = $news_query->execute();
    if ($news_nids) {
      $news_node = $storage->load(reset($news_nids));
      $cards[] = $view_builder->view($news_node, 'card');
      $cache_tags = array_merge($cache_tags, $news_node->getCacheTags());
    }

    if (empty($cards)) {
      return ['#cache' => ['tags' => $cache_tags]];
    }

    return [
      'cards' => $cards,
      '#cache' => [
        'tags'     => $cache_tags,
        'contexts' => ['url.path', 'user.permissions'],
      ],
    ];
  }

}
