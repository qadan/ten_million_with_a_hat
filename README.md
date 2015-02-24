CONTENTS OF THIS FILE
---------------------

 * summary
 * usage
 * customization

SUMMARY
-------

Adds a drush script for ingesting many objects; say, ten million.

Checks all your collections for their policies, and then just starts ingesting
random stuff with whatever models their COLLECTION_POLICY says are valid.

USAGE
-----

- STEP 1: drush -u 1 ten-million-with-a-hat-ingest --objects=10000000
- STEP 2: wait for like a week
- STEP 3: actually some part of the stack will probably poop the bed before then (looking at you, fedora ಠ_ಠ).

CUSTOMIZATION
-------------

Two hooks are available, `hook_ten_million_with_a_hat_also_do_these_things` and `hook_ten_million_with_a_hat_also_say_these_things`, to modify each ingested object. Check ten_million_with_a_hat.api.php for more details.
