<?php

/**
 * @file
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class EnablerService{
  protected $database_connection;

  public function __construct(Connection $connection) {
    $this->database_connection=$connection;
  }

  public function isEnabled(Node &$node) {
    if($node->isNew()){
      return FALSE;
    }
    try {
      $select = $this->database_connection->select('rsvplist_enabled', 're');
      $select -> fields('re', ['nid']);
      $select -> condition('nid', $node->id());
      $results = $select->execute();

      return !(empty($results->fetchCol()));
    }
    catch (\Exception $e){
      \Drupal::messenger()->addError(
        t('unable to determine RSVP settings at this time. pls try again'),
      );
      return NULL;
    }
  }
}
