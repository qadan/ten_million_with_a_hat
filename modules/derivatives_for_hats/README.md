# Derivatives for Hats

## Introduction

Generates derivatives for each object created provided that an OBJ datastream is present when the `derivatives_for_hats_ten_million_with_a_hat_also_do_these_things` hook fires.

## Requirements

This module does not depend on any other modules that create OBJs to ensure the ability to create OBJ datastreams through any means possible. A module that provides OBJs, such as `objs_for_hats` or `tiffs_for_page_hats`, should be enabled in conjunction with this module.

This module does, however, require the following module/libraries:

* [Islandora](https://github.com/Islandora/islandora)

## Notes

A couple of notes from a development perspective:

- This module almost precisely recreates `islandora_do_batch_derivatives`; this is intentional, it allows the module to create derivatives after each object ingest (as opposed to in bulk after all objects are created), allowing better communication from Drush to the command line, as well as more accurate reporting of ingest times.
- This module is weighted at 51 for `between_ingests` hooks. Consider weighing hooks that provide OBJ datastreams between ingests at 50 or above (for example, `objs_for_hats` is weighted at 50).

## Installation

Install as usual, see [this](https://drupal.org/documentation/install/modules-themes/modules-7) for further information.

## Configuration

None.

## Troubleshooting/Issues

Having problems or solved a problem? Check out the Islandora google groups for a solution.

* [Islandora Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora)
* [Islandora Dev Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora-dev)

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden Inc.](https://github.com/discoverygarden)

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
