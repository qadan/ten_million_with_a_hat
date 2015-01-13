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
      'examples' => array('drush -u 1 ten-million-with-a-hat --objects=10000000'),
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
        'thumbnails' => array(
          'description' => dt('Whether to generate thumbnails (true/false; defaults to false).'),
          'required' => FALSE,
        ),
      ),
    ),
  );
}

function drush_ten_million_with_a_hat_ingest() {
  $thumbnails = drush_get_option('thumbnails', FALSE);
  if (strtolower($thumbnails) === 'true') {
    $thumbnails = TRUE;
  }
  batch_set(ten_million_with_a_hat_ingest_batch(drush_get_option('objects'), $thumbnails));
  drush_backend_batch_process();
}
