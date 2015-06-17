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
 *   - 'sandbox': a boolean representing whether or not the batch 
 *     $context['sandbox'] array should be appended to the list of callback
 *     arguments by reference. Consider only using this if you actually require
 *     the batch sandbox so that variables aren't being passed around
 *     unnecessarily. If set, then the last argument your module accepts MUST
 *     be the sandbox, passed in by reference. Defaults to FALSE.
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
    // module itself (requiring no 'file' array), takes a second argument, uses
    // the batch sandbox, and prints out a static message on completion.
    array(
      'callback' => 'hat_colour_change_hat_colour',
      'args' => array($colour),
      'weight' => 4,
      'when' => 'between_ingests',
      'sandbox' => TRUE,
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
 * An example of a callback between ingests using the sandbox.
 *
 * @param AbstractObject $object
 *   The object that was just ingested which we may want to manupilate or pull
 *   information from here.
 * @param $MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array.
 *   These are not passed in as an array, but as individual arguments, ordered
 *   as they were in the 'args' array. In the below example, only one argument
 *   is passed in - a content model, represented by 
 *   $model_passed_in_from_MULTIPLE_PARAMS.
 * @param array $sandbox
 *   The batch $context['sandbox'] array, passed in by reference.
 *
 * @return string|string[]|null
 *   The message to display, or an array of messages to display, or NULL to display no message.
 */
function callback_between_ingests(AbstractObject $object, $MULTIPLE_PARAMS, &$sandbox) {
  $object->models[] = $model_passed_in_from_MULTIPLE_PARAMS;
  $return_array = array();
  foreach ($object->models as $model) {
    $return_array[] = "The object {$object->id} contains the content model $model.";
  }

  // Caching, for now, is the most useful way of maintaining changing params
  // between ingests, like the incremented index in this example.
  if (count($object->models) > 2) {
    $object->label = "Object #" . $sandbox['sample_callback_index'];
    $return_array[] = "{$object->id} is object number {$sandbox['sample_callback_index']} to have been given more than two content models; this has been reflected in the label.";
    $sandbox['sample_callback_index']++;
  }

  // Finally, return a string or array of strings to display a message, or
  // return nothing to skip message display for this callback. Either way,
  // hooks with a 'message' parameter will override this return value, so it is
  // recommended to just use one or the other.
  return $return_array;
}

/**
 * An example before_batch callback using the sandbox.
 *
 * @see callback_between_ingests().
 *
 * @param $MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array. In
 *   this example, only one is set - a starting index integer, represented by
 *   $starting_index_from_MULTIPLE_PARAMS.
 * @param array $sandbox
 *   The batch $context['sandbox'] array, passed in by reference.
 *
 * @return string|string[]|null
 *   The message to display, if any.
 */
function callback_before_batch($MULTIPLE_PARAMS, &$sandbox) {
  // Set the index to the sandbox. Return nothing, as we don't wish to display
  // a message.
  $sandbox['sample_callback_index'] = $index_set_from_MULTIPLE_PARAMS;
}

/**
 * An example after_batch callback requiring no sandbox access.
 *
 * @see callback_between_ingests().
 *
 * @param $MULTIPLE_PARAMS
 *   Parameters passed in from the also_do_these_things hook's 'args' array. In
 *   this example, the parameter passed in is a variable we want to give to
 *   variable_set(), which could be useful in reconfiguring a modified Drupal
 *   environment.
 *   
 * @return string|string[]|null
 *   The message or messages to display, if any.
 */
function callback_after_batch($MULTIPLE_PARAMS) {
  // We could actually just set the 'callback' in the hook to 'variable_set' and
  // then provide the below message with 'message'; this just serves as a demo.
  variable_set('sample_drupal_variable', $value_passed_in_from_MULTIPLE_PARAMS);
  return "The variable sample_drupal_variable has been set to $value_passed_in_from_MULTIPLE_PARAMS.";
}
