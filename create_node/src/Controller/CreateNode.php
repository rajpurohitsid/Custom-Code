<?php

namespace Drupal\create_node\Controller;

use Drupal\node\NodeInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\redirect\Entity\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\taxonomy\Entity\Term;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for export json.
 */
class CreateNode extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function data() {
  	// return ['#markup' => $this->t("Hello World!")];
    $json_array = array(
      'data' => array()
    );
    $nids = \Drupal::entityQuery('node')->condition('type','event')->execute();
    dd($nids);
    $nodes =  Node::loadMultiple($nids);
    foreach ($nodes as $node) {
      $json_array['data'][] = array(
        'type' => $node->get('type')->target_id,
        'id' => $node->get('nid')->value,
        'attributes' => array(
          'title' =>  $node->get('title')->value,
          'content' => $node->get('body')->value,
        ),
      );
    }
    return new JsonResponse($json_array);
  }
}