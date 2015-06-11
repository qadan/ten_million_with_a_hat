<?php

/**
 * @file
 * Describes the ten_million_with_a_hat_also_do_these_things hook.
 */

/**
 * Allows additional callbacks to execute during the batch process.
 *
 * Also allows for the return of messages from these callbacks. As all keys in
 * the hook array except for 'when' are optional, one can skip the callback
 * array and simply pass back a 'message' for display purposes during the
 * batch. It would not be much use to implement this hook without a 'callback'
 * or 'message' in the array, though, so probably don't do that.
 *
 * @return array
 *   An associative array of callback arrays to execute, containing:
 *   - 'when' (mandatory): one of three possible values - 'before_batch',
 *     'between_ingests', or 'after_batch', to designate when this should be
 *     run.
 *   - 'file': an array containing 'type', 'module' and 'path', as
 *     one would pass these parameters to module_load_include().
 *   - 'callback': the name or class/method array for call_user_func_array()
 *     to execute. Note that callbacks run using the between_ingests 'when'
 *     must accept the Fedora object currently being worked with as the first
 *     argument. If the callback returns a string, it will be used as a message
 *     for drush to print out, provided that the 'message' is not set in this
 *     hook array.
 *   - 'args': an array of arguments acceptable to call_user_func_array(). in
 *     the case of a between_ingests 'when' for the return value, this list will
 *     be appended to the Fedora object currently being worked with.
 *   - 'weight': a designated weight for the execution order of
 *     callbacks.
 *   - 'message': A message to show after the callback is executed.
 *     Overrides any return value from the callback.
 */
function hook_ten_million_with_a_hat_also_do_these_things() {
  // If we require a variable that won't change per-object (e.g., the PID of a
  // parent object), this should be calculated now and passed into the
  // appropriate 'args' array.
  $colour = hat_colour_get_colour('blue');
  return array(
    // Example 1: a callback occuring between ingests that comes from a file
    // inside an external module.
    array(
      'file' => array(
        'type' => 'inc',
        'module' => 'multi_hat_management',
        'path' => 'includes/utilities',
      ),
      'callback' => 'multi_hat_management_add_hat',
      'weight' => 5,
      'when' => 'between_ingests',
    ),
    // Example 2: a callback occuring between ingests that comes from the
    // module itself (requiring no 'file' array), takes a second argument, and
    // prints out a static message on completion.
    array(
      'callback' => 'hat_colour_change_hat_colour',
      'args' => array($colour),
      'weight' => 4,
      'when' => 'between_ingests',
      // It would be smarter to display this message as the return value of
      // hat_colour_change_hat_colour, as one could display a more detailed
      // message or change the contents of the message on failure. It is
      // nonetheless provided here as an example.
      'message' => 'The hat colour has been changed to blue.',
    ),
    // Example 3: a message that displays after all objects have been ingested.
    'Display a message!' => array(
      'message' => "All hats have been coloured $colour, and an additional hat has been applied per-hat.",
      'when' => 'after_batch',
    ),
  );
}

/**
 * An example of a callback between ingests.
 *
 * @param AbstractObject $object
 *   The object that was just ingested which we may want to manupilate or pull
 *   information from here.
 * @param $MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array.
 *   These are not passed in as an array, but as individual arguments, ordered
 *   as they were in the 'args' array.
 *
 * @return string|string[]|null
 *   The message to display, or an array of messages to display, or NULL to display no message.
 */
function callback_between_ingests(AbstractObject $object, $MULTIPLE_PARAMS) {
  $object->models[] = $model_passed_in_from_MULTIPLE_PARAMS;
  $return_array = array();
  foreach ($object->models as $model) {
    $return_array[] = "The object {$object->id} contains the content model $model.";
  }

  // Caching, for now, is the most useful way of maintaining changing params
  // between ingests, like the incremented index in this example.
  if (count($object->models) > 2) {
    $cache = cache_get('callback_index');
    $data = $cache->data;
    $object->label = "Object #" . $data;
    $return_array[] = "{$object->id} is object number $data to have been given more than two content models; this has been reflected in the label.";
    $data++;
    cache_set('callback_index', $data);
  }

  // Finally, return a string or array of strings to display a message, or
  // return nothing to skip message display for this callback. Either way,
  // hooks with a 'message' parameter will override this return value, so it is
  // recommended to just use one or the other.
  return $return_array;
}

/**
 * An example before_batch callback.
 *
 * @see callback_between_ingests().
 *
 * @param MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array.
 *
 * @return string|string[]|null
 *   The message to display, if any.
 */
function callback_before_batch($MULTIPLE_PARAMS) {
  // Before or after the batch tends to be a good place to set up or tear down
  // things that need to be maintained between ingests.
  cache_clear_all('callback_index', 'cache');
  $index = $starting_index_from_MULTIPLE_PARAMS;
  // Set the index. Return nothing, as we don't wish to display a message.
  cache_set('callback_index', $index);
}

/**
 * An example after_batch callback.
 *
 * @see callback_between_ingests().
 *
 * @param $MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array.
 *   
 * @return string|string[]|null
 *   The message or messages to display, if any.
 */
function callback_after_batch($MULTIPLE_PARAMS) {
  // Let's tear down the cache we established in callback_before_batch().
  $cache = cache_get('callback_index');
  cache_clear_all('callback_index', 'cache');
  return "The number of objects ingested with more than two content models was {$cache->data}."
}
