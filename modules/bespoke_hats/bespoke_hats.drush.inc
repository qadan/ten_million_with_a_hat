<?php

/**
 * @file
 * Drush hooks for Bespoke Hats.
 */

/**
 * Implements hook_drush_command().
 */
function bespoke_hats_drush_command() {
  return array(
    'bespoke-hats-ingest' => array(
      'aliases' => array('bhi'),
      'description' => dt('Randomly generates objects based on a hierarchy manifest passed in. Check the README for Bespoke Hats for details.'),
      'examples' => array(
        'Manifest ingest' => 'drush -u 1 bhi --manifest=/home/user/manifest.json',
      ),
      'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_LOGIN,
      'drupal dependencies' => array(
        'islandora',
        'islandora_basic_collection',
        'ten_million_with_a_hat',
        'bespoke_hats',
      ),
      'options' => array(
        'manifest' => array(
          'description' => dt('The full path to a JSON manifest that defines the structure of objects to ingest, and what types should be placed where.'),
          'required' => TRUE,
        ),
        'namespace' => array(
          'description' => dt('A namespace to give to the ingested objects. Defaults to "islandora".'),
          'required' => FALSE,
        ),
        'bucket_size' => array(
          'description' => dt('The number of items to ingest per iteration. Defaults to 1.'),
          'required' => FALSE,
        ),
      ),
    ),
  );
}

/**
 * Custom batch setting function for Bespoke Hats.
 */
function drush_bespoke_hats_ingest() {
  module_load_include('inc', 'ten_million_with_a_hat', 'ten_million_with_a_hat.drush');
  // XXX: The first three parameters are set here because
  // ten_million_with_a_hat_ingest_batch requires them; they will eventually be
  // overridden by bespoke_hats_prepare_sandbox. Opting to do this way instead
  // of rolling a custom batch or overhauling the existing one.
  batch_set(ten_million_with_a_hat_ingest_batch(
    0,
    array(
      'type' => 'exclude',
      'pids' => array('dummy:pid'),
    ),
    array(
      'type' => 'exclude',
      'pids' => array('dummy:pid'),
    ),
    drush_get_option('namespace', 'islandora'),
    drush_get_option('bucket_size', 1),
    array(
      'manifest' => drush_get_option('manifest'),
    )
  ));
  drush_backend_batch_process();
}
