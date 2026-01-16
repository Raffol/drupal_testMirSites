<?php
/**
 * @file
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {
  /**
   * Gets & returns all RSVPs for all nodes
   *
   * @return array|null
   */

  protected function load() {
    try{
      $database = \Drupal::database();
      $select_query = $database->select('rsvplist', 'r');

      //join the user table, so we can get the entry creator's username
      $select_query->join('users_field_data', 'u', 'r.uid = u.uid');

      //join th node table, so we can get the event's name
      $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

      //select these specific fields for the output
      $select_query->addField('u', 'name', 'username');
      $select_query->addField('n', 'title');
      $select_query->addField('r', 'mail');

      $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);

      return $entries;
    }
    catch (\Exception $e){
      //display a user-friendly error
      \Drupal::messenger()->addStatus(
        t('unable to access the database at this time')
      );
      return NULL;
    }
  }
  /**
   * creates the RSVPList report page
   * @return array
   */
  public function report() {
    $content = [];
    $content['message'] = [
      '#markup'=>t('below is a list of all event RSVPs
                including username & etc.'),
    ];
    $headers = [
      t('Username'),
      t('Event'),
      t('Email'),
    ];
    $table_rows = $this->load();

    //create the render array for rendering an html table
    $content['table'] = [
      '#type'=>'table',
      '#header'=>$headers,
      '#rows'=>$table_rows,
      '#empty'=>t('no entries available'),
    ];
    //do not cache this page
    $content['#cache']['#max-age'] = 0;

    return $content;
  }
}
