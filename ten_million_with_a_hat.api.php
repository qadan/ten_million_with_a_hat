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
  $colour = hat_colour_get_colour('blue');
  return array(
    'Put on a second hat!' => array(
      'file' => array(
        'type' => 'inc',
        'module' => 'add_second_hat',
        'path' => 'includes/utilities',
      ),
      'callback' => 'add_second_hat_put_on_second_hat',
      'weight' => 5,
      'when' => 'between_ingests',
      'message' => 'More hats!',
    ),
    'Change the hat colour to blue!' => array(
      // This theoretical callback is part of the .module and requires no file
      // to load.
      'callback' => 'hat_colour_change_colour',
      // This theoretical callback also requires an extra argument.
      'args' => array($colour),
      // Plus, this theoretical callback should be run before the one above.
      'weight' => 4,
      // This should be done for every new object.
      'when' => 'between_ingests',
    ),
    'Display a message!' => array(
      // We can display a message by just using 'message' without 'callback'.
      'message' => "All hats have been coloured $colour, and an additional hat has been applied per-hat.",
      // This message will display once, after the batch is complete.
      'when' => 'after_batch',
    ),
  );
}

/**
 * Just an example of what the object manipulation callbacks should look like.
 *
 * @param AbstractObject $object
 *   The object being worked with.
 * @param string $new_colour
 *   The new colour to use.
 *
 * @return string
 *   The message to display.
 */
function hat_colour_change_colour(AbstractObject $object, $new_colour) {
  $object->colour = $new_colour;
  return "Hat colour is now $new_colour.";
}
