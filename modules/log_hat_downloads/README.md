# Log Hat Downloads

## Introduction

Logs a random number of downloads on each OBJ datastream created on a hat.

## Requirements

This module does not depend on any other modules that create OBJs to ensure the ability to create OBJ datastreams through any means possible. A module that provides OBJs, such as `objs_for_hats` or `tiffs_for_page_hats`, should be enabled in conjunction with this module.

This module does, however, require the following module/libraries:

* [Islandora](https://github.com/Islandora/islandora)
* [Islandora Usage Stats](https://github.com/discoverygarden/islandora_usage_stats)

## Notes

- This module is weighted at 51 for `between_ingests` hooks. Consider weighing hooks that provide OBJ datastreams between ingests at 50 or above (for example, `objs_for_hats` is weighted at 50).

## Installation

Install as usual, see [this](https://drupal.org/documentation/install/modules-themes/modules-7) for further information.

## Configuration

None.

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
