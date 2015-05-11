OVERVIEW
--------

Adds any page objects created to a book object that is ingested before hats
start going in.

USAGE
-----

Consider using objs_for_hats or tiffs_for_page_hats to populate the pages with
content - ideally the latter, since it executes before the former. Use
derivatives_for_hats to generate the necessary page derivatives. Use the drush
command:

`drush -u 1 tmhi --objects=(number of pages to put in your book) --restrict-cms=islandora:pageCModel`

to just generate a book.
