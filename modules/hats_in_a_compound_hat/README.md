# Hats in a Compound Hat

## Introduction

This little guy takes all of your ingested objects and adds them to a single compound, created before ingest begins.

## Requirements

This module requires the following modules/libraries:

* [Islandora](https://github.com/Islandora/islandora)

## Installation

Install as usual, see [this](https://drupal.org/documentation/install/modules-themes/modules-7) for further information.

## Configuration

It's probably recommended to pass `--exclude-cms=islandora:collectionCModel,islandora:compoundCModel` or something equivalent so that you don't get a weird inception-esque set of members of things that can have members and constituents and blah blah.

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
