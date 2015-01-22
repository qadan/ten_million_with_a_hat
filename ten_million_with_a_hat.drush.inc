<?php

/**
 * @file
 * Droosh.
 */

/**
 * Implements hook_drush_command().
 */
function ten_million_with_a_hat_drush_command() {
  return array(
    'ten-million-with-a-hat-ingest' => array(
      'aliases' => array('tmhi'),
      'description' => dt('Randomly generates n objects based on the existing collections in your repository.'),
      'examples' => array('drush -u 1 ten-million-with-a-hat --objects=10000000 --exclude=islandora:collectionCModel,islandora:compoundCModel'),
      'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_LOGIN,
      'drupal dependencies' => array(
        'islandora',
        'islandora_basic_collection',
      ),
      'options' => array(
        'objects' => array(
          'description' => dt('The number of objects to create.'),
          'required' => TRUE,
        ),
        'exclude' => array(
          'description' => dt('A comma-separated list of PIDs of solution packs to exclude. Defaults to islandora:collectionCModel.'),
          'required' => FALSE,
        ),
      ),
    ),
  );
}

/**
 * Imgest method for the ten-million-with-a-hat-ingest command.
 */
function drush_ten_million_with_a_hat_ingest() {
  $exclude = drush_get_option('exclude', array('islandora:collectionCModel'));
  if (!is_array($exclude)) {
    $exclude = explode(',', $exclude);
  }
  batch_set(ten_million_with_a_hat_ingest_batch(drush_get_option('objects'), $exclude));
  drush_backend_batch_process();
}
