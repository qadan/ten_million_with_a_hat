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
        'Exclude both the collection and compound content models' => 'drush -u 1 tmhi --objects=100 --exclude-cms=islandora:collectionCModel,islandora:compoundCModel',
        'Restrict to only use the basic and large image content models' => 'drush -u 1 tmhi --objects=100 --restrict-cms=islandora:sp_basic_image,islandora:sp_large_image_cmodel',
        'Ingest citation objects with the "ir" namespace' => 'drush -u 1 tmhi --objects=100 --restrict-cms=ir:citationCModel --namespace=ir',
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
        'exclude-cms' => array(
          'description' => dt('A comma-separated list of PIDs of solution packs to exclude. Defaults to islandora:collectionCModel, unless "restrict-cms" is set.'),
          'required' => FALSE,
        ),
        'restrict-cms' => array(
          'description' => dt('A comma-separated list of PIDs of solution packs to limit ingested objects to. Overrides "exclude-cms". Also forces content model inclusion; any restricted content models which appear in no collections will be given islandora:dummy_collection as their parent. The dummy collection will not be ingested.'),
          'required' => FALSE,
        ),
        'exclude-collections' => array(
          'description' => dt('A comma-separated list of PIDs of collections to exclude. Defaults to none, though any collections containing no valid content models will automatically be excluded.'),
          'required' => FALSE,
        ),
        'restrict-collections' => array(
          'description' => dt('A comma-separated list of PIDs of collections to limit ingest to. Overrides "exclude-collections".'),
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
  $cm_set = drush_ten_million_with_a_hat_get_restriction_or_exclusion_array('cms');
  $collection_set = drush_ten_million_with_a_hat_get_restriction_or_exclusion_array('collections');
  batch_set(ten_million_with_a_hat_ingest_batch(drush_get_option('objects'), $cm_set, $collection_set, drush_get_option('namespace', 'islandora')));
  drush_backend_batch_process();
}

/**
 * Figure out restriction and exclusion for collections and content models.
 *
 * @param string $type
 *   One of two types ('cms' or 'collections').
 *
 * @return array|null
 *   Null if not set. Otherwise, an associative array with two keys:
 *   'type' - a string containing the type of operation being done ('exclude'
 *   or 'restrict')
 *   'pids' - an array of PIDs being excluded or restricted.
 */
function drush_ten_million_with_a_hat_get_restriction_or_exclusion_array($type) {
  $restrict = drush_get_option("restrict-$type", NULL);
  if (!is_null($restrict)) {
    $restrict = explode(',', $restrict);
  }
  if (is_array($restrict)) {
    return array(
      'type' => 'restrict',
      'pids' => $restrict,
    );
  }

  $exclude = drush_get_option("exclude-$type", "exclude-$type" === "exclude-cms" ? array('islandora:collectionCModel') : array());
  if (!is_array($exclude)) {
    $exclude = explode(',', $exclude);
  }
  return array(
    'type' => 'exclude',
    'pids' => $exclude,
  );
}
