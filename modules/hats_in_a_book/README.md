## Hats in a Book

## Introduction

Adds any page objects created to a book object that is ingested before hats start going in.

## Requirements

This module requires the following modules/libraries:

* [Islandora](https://github.com/Islandora/islandora)
* [Islandora Book Solution Pack](https://github.com/Islandora/islandora_solution_pack_book)

## Usage

Consider using objs_for_hats or tiffs_for_page_hats to populate the pages with content - ideally the latter, since it makes actual text-content pages, and it executes before the former. Use derivatives_for_hats to generate the necessary page derivatives. Use the drush command:

`drush -u 1 tmhi --objects=(number of pages to put in your book) --restrict-cms=islandora:pageCModel`

to just generate a book with pages in it. If no collections are configured to accept page objects, pages will be placed in islandora:dummy_collection.

## Installation

Install as usual, see [this](https://drupal.org/documentation/install/modules-themes/modules-7) for further information.

## Troubleshooting/Issues

Having problems or solved a problem? Contact [discoverygarden](http://support.discoverygarden.ca).

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden](http://www.discoverygarden.ca)

## Development

If you would like to contribute to this module, please check out our helpful
[Documentation for Developers](https://github.com/Islandora/islandora/wiki#wiki-documentation-for-developers)
info, [Developers](http://islandora.ca/developers) section on Islandora.ca and
contact [discoverygarden](http://support.discoverygarden.ca).

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
