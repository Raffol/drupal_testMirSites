<?php

$site_config = \Drupal::config('system.site'); //site info
$site_name = $site_config->get('name');
$site_slogan = $site_config->get('slogan');
$site_email = $site_config->get('email');


/*if (\Drupal::currentUser()->isAuthenticated()){
  !?
}
if (\Drupal::currentUser()->isAnonymous()){
  !!!
}*/

$current_system_path = \Drupal::service('path.current')->getPath(); //получение системного адреса
$current_path_alias = \Drupal::service('path.alias_manager')->
getAliasByPath($current_system_path); //получение синонима
