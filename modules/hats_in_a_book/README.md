## Hats in a Book

## Introduction

Adds any page objects created to a book object that is ingested before hats
start going in.

## Requirements

This module requires the following modules/libraries:

* [Islandora](https://github.com/Islandora/islandora)
* [Islandora Book Solution Pack](https://github.com/Islandora/islandora_solution_pack_book)

## Usage

As all objects created by Ten Million with a Hat are placed inside collections,
a collection will have to be created that accepts objects with the
islandora:pageCModel content model.

Consider using objs_for_hats or tiffs_for_page_hats to populate the pages with
content - ideally the latter, since it makes actual text-content pages, and it 
executes before the former. Use derivatives_for_hats to generate the necessary
page derivatives. Use the drush command:

`drush -u 1 tmhi --objects=(number of pages to put in your book) --restrict-cms=islandora:pageCModel`

to just generate a book.

## Installation

Install as usual, see [this](https://drupal.org/documentation/install/modules-themes/modules-7) for further information.

## Troubleshooting/Issues

Having problems or solved a problem? Check out the Islandora google groups for a solution.

* [Islandora Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora)
* [Islandora Dev Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora-dev)

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden Inc.](https://github.com/discoverygarden)

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
