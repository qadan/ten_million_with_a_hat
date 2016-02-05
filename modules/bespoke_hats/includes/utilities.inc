<?php

/**
 * @file
 * Most of the actual functionality for bespoke_hats.
 */

/**
 * Loads the manifest JSON and use it to modify the ingest parameters.
 *
 * Provides $sandbox['manifest'] to keep track of the manifest passed in.
 *
 * If anything goes wrong attempting to load the manifest (No manifest given,
 * file doesn't exist, or invalid JSON), $sandbox['manifest'] will be set to
 * NULL.
 *
 * @param array $sandbox
 *   The batch sandbox, passed in by reference.
 *
 * @return string
 *   A string containing a message representing the results of the function.
 */
function bespoke_hats_prepare_sandbox(&$sandbox) {
  $manifest_path = isset($sandbox['manifest']) ? $sandbox['manifest'] : NULL;
  // Load the JSON, handle errors.
  if (is_null($manifest_path)) {
    $sandbox['manifest'] = NULL;
    return "No --manifest path was given.";
  }
  $manifest = file_get_contents($manifest_path);
  if (!$manifest) {
    $sandbox['manifest'] = NULL;
    return "Could not read manifest at $manifest_path.";
  }
  $manifest = json_decode($manifest, TRUE);
  if (is_null($manifest)) {
    $sandbox['manifest'] = NULL;
    return "Could not decode manifest JSON.";
  }

  // Edit the ingest parameters to the manifest-provided one.
  $sandbox['manifest'] = array();
  // Our total should be the combined value of all model counts in the manifest;
  // ignore any 'total' that may or may not have been passed in.
  $sandbox['total'] = 0;
  $sandbox['models'] = array();
  foreach ($manifest as $collection) {
    $reformatted_collection_set = array(
      'models' => array(),
      'name' => isset($collection['name']) ? $collection['name'] : NULL,
      'parent_pid' => $collection['parent_pid'],
    );
    foreach ($collection['models'] as $model => $total) {
      // More useful array for the sandbox updating thingy so we can remove
      // models and collections as we go by seeing if we've hit the total.
      $reformatted_collection_set['models'][$model] = array(
        'current' => 0,
        'total' => $total,
      );
      // Update the count as we go.
      $sandbox['total'] += $total;
      // Update the models array as we go.
      if (!isset($sandbox['models'][$model])) {
        $sandbox['models'][$model] = array();
      }
      $sandbox['models'][$model][] = $collection['pid'];
    }
    $sandbox['manifest'][$collection['pid']] = $reformatted_collection_set;
  }

  return "The ingest parameters have been modified to match the manifest passed in.";
}

/**
 * Ingests the parent collections defined by the manifest.
 *
 * @param array $sandbox
 *   The batch sandbox, passed in by reference.
 *
 * @return string
 *   A string defining a message to display.
 */
function bespoke_hats_ingest_parents(&$sandbox) {
  if (is_array($sandbox['manifest'])) {
    module_load_include('inc', 'islandora_basic_collection', 'includes/collection_policy');
    $connection = islandora_get_tuque_connection();
    $repository = $connection->repository;
    // Ingest each found parent.
    foreach ($sandbox['manifest'] as $pid => $parameters) {
      $object = islandora_object_load($pid);
      $ingest = FALSE;
      if (!$object) {
        $object = $repository->constructObject($pid);
        $ingest = TRUE;
      }
      if (!is_null($parameters['name'])) {
        $object->label = $parameters['name'];
      }
      $object->models = 'islandora:collectionCModel';
      $policy = CollectionPolicy::emptyPolicy();
      foreach ($parameters['models'] as $model => $properties) {
        $model = islandora_object_load($model);
        if ($model) {
          $policy->addContentModel($model->id, $model->label, $sandbox['namespace']);
        }
      }
      $datastream = $object->constructDatastream('COLLECTION_POLICY');
      $datastream->setContentFromString($policy->getXML());
      $datastream->mimetype = 'application/xml';
      $datastream->label = 'Collection Policy';
      $object->ingestDatastream($datastream);
      $object->relationships->add(FEDORA_RELS_EXT_URI, 'isMemberOfCollection', $parameters['parent_pid']);
      if ($ingest) {
        $repository->ingestObject($object);
      }
    }

    return "Ingested all parent collections defined by the manifest.";
  }
}

/**
 * Update the sandbox after an object has been ingested.
 *
 * @param AbstractObject $object
 *   The object that was ingested.
 * @param array $sandbox
 *   The batch sandbox, passed in by reference.
 */
function bespoke_hats_update_sandbox(AbstractObject $object, &$sandbox) {
  if (is_array($sandbox['manifest'])) {
    // Get the spot to update.
    $model = $object->models;
    $model = reset($model);
    // This may not be set anymore.
    if ($sandbox['models'][$model]) {
      $collections = $object->relationships->get(FEDORA_RELS_EXT_URI, 'isMemberOfCollection');
      $collection = reset($collections);
      $collection = $collection['object']['value'];
      // Recurse through parents in case there were multiple.
      while (!in_array($collection, $sandbox['models'][$model]) || !$collection) {
        $collection = reset($collections);
        if ($collection) {
          $collection = $collection['object']['value'];
        }
      }

      // Check if we need to remove the collection from the models list, and if
      // we need to remove the model from the sandbox list.
      if ($collection) {
        $sandbox['manifest'][$collection]['models'][$model]['current']++;
        if ($sandbox['manifest'][$collection]['models'][$model]['current'] == $sandbox['manifest'][$collection]['models'][$model]['total']) {
          $sandbox['models'][$model] = array_diff($sandbox['models'][$model], array($collection));
          if (empty($sandbox['models'][$model])) {
            $sandbox['models'] = array_diff_key($sandbox['models'], array($model => array()));
          }
        }
      }
    }
  }
}
