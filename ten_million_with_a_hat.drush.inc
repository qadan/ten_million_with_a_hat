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
      'description' => dt('Randomly generates n objects based on the existing collections in your repository, with properties based on any enabled modules implementing the ten_million_with_a_hat object manipulation hook.'),
      'examples' => array(
        'Ingest ten million things' => 'drush -u 1 ten-million-with-a-hat-ingest --objects=10000000',
        'Exclude both the collection and compound content models' => 'drush -u 1 tmhi --objects=100 --exclude=islandora:collectionCModel,islandora:compoundCModel',
        'Restrict to only use the basic and large image content models' => 'drush -u 1 tmhi --objects=100 --restrict=islandora:sp_basic_image,islandora:sp_large_image_cmodel',
        'Ingest citation objects with the "ir" namespace' => 'drush -u 1 tmhi --objects=100 --restrict=ir:citationCModel --namespace=ir',
      ),
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
          'description' => dt('A comma-separated list of PIDs of solution packs to exclude. Defaults to islandora:collectionCModel, unless "restrict" is set.'),
          'required' => FALSE,
        ),
        'restrict' => array(
          'description' => dt('A comma-separated list of PIDs of solution packs to limit ingested objects to. Overrides "exclude".'),
          'required' => FALSE,
        ),
        'namespace' => array(
          'description' => dt('A namespace to give to the ingested objects. Defaults to "islandora".'),
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
  // Fun times figuring out restrictions and exclusions!
  $restrict = drush_get_option('restrict', NULL);
  if (!is_null($restrict)) {
    $restrict = explode(',', $restrict);
  }
  if (!is_array($restrict)) {
    $exclude = drush_get_option('exclude', array('islandora:collectionCModel'));
    if (!is_array($exclude)) {
      $exclude = explode(',', $exclude);
    }
  }
  else {
    $exclude = NULL;
  }
  batch_set(ten_million_with_a_hat_ingest_batch(drush_get_option('objects'), $exclude, $restrict, drush_get_option('namespace', 'islandora')));
  drush_backend_batch_process();
}
