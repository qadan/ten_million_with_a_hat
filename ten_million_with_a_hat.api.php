<?php

/**
 * @file
 * Describes the ten_million_with_a_hat_also_do_these_things hook.
 */

/**
 * Allows additional callbacks to execute after each object has been ingested.
 *
 * Note that this callback hook gets called before any object is ingested. If
 * you need any tasks to be run before the batch process begins, or need any
 * static variables to be generated and passed to the entire batch process,
 * consider doing it here.
 *
 * @return array
 *   An associative array of callback arrays to execute, containing:
 *   - 'file' (optional): an array containing 'type', 'module' and 'path', as
 *   one would pass these parameters to module_load_include().
 *   - 'callback': the name or class/method array for call_user_func_array()
 *   to execute. Note that the callback must accept the Fedora object currently
 *   being worked with as the first argument, and must return that same object.
 *   - 'args' (optional): an array of arguments acceptable to
 *   call_user_func_array(). Added to the beginning of this will be the Fedora
 *   object being worked with.
 *   - 'weight' (optional): a designated weight for the execution order of
 *   callbacks.
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
    ),
    'Change the hat colour to blue!' => array(
      // This theoretical callback is part of the .module and requires no file
      // to load.
      'callback' => 'hat_colour_change_colour',
      // This theoretical callback also requires an extra argument.
      'args' => array($colour),
      // Plus, this theoretical callback should be run before the one above.
      'weight' => 4,
    ),
  );
}

/**
 * Just an example of what the callbacks should look like.
 *
 * @param AbstractObject $object
 *   The object being worked with.
 * @param string $new_colour
 *   The new colour to use.
 */
function hat_colour_change_colour(AbstractObject $object, $new_colour) {
  $object->colour = $new_colour;
}

/**
 * Our theoretical hat colour getter.
 *
 * @param string $colour
 *   It's a colour.
 *
 * @return string
 *   A hat colour valid for use with colour-compatible objects.
 */
function hat_colour_get_colour($colour) {
  // Do some rainbow magic here.
  return $colour
}
