CONTENTS OF THIS FILE
---------------------

 * summary
 * usage

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
