CONTENTS OF THIS FILE
---------------------

*summary
*usage

SUMMARY
-------

This little guy takes all of your ingested objects and adds them to a single compound, created before ingest begins.

USAGE
-----

Pass the `-v` flag to grab the PID of the compound object when the drush script starts. Otherwise, you'll have to check the watchdog logs for it.

It's probably recommended to pass `--exclude-cms=islandora:collectionCModel,islandora:compoundCModel` or something equivalent so that you don't get a weird inception-esque set of members of things that can have members and constituents and blah blah.
