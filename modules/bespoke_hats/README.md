# Bespoke Hats

## Introduction

Adds the option of supplying a JSON manifest to define parts of the batch ingest structure.

## Usage

Check `drush help bhi` for details.

Use the parameter `--manifest` to supply the location of a manifest file that can be loaded as JSON. This must be an absolute path to a file accessible by Drush - no aliases or tildes or things like that, since Drush doesn't know what that means.

The JSON manifest should contain an array of one or more objects, each of which contain the following parameters:

`pid`: The PID of a collection to ingest (or simply add objects to).
`name`: (optional) The label to give that collection.
`parent_pid`: The PID of the collection's parent.
`models`: An object containing one or more content model PIDs mapped to the number of that type of object that should be ingested into this collection.

The result should look something like this:

```json
[
  {
    "pid": "collection:1",
    "name": "Collection 1",
    "parent_pid": "islandora:root",
    "models": {
      "islandora:sp_basic_image": 1000,
      "islandora:sp_large_image": 500
    }
  },
  {
    "pid": "collection:2",
    "name": "Collection 2",
    "parent_pid": "collection:1",
    "models": {
      "islandora:sp-audioCModel": 250,
      "islandora:sp_videoCModel": 400
    }
  }
]
```

*NOTE*: using existing collections in the JSON can overwrite their content models, labels and parent relationships. Use caution here.

Who am I kidding, this is supposed to be test data. Don't use this on production servers, kids.

## Customization

Check the README.md for `ten_million_with_a_hat` for customization details.

## Troubleshooting/Issues

Having problems or solved a problem? Contact [discoverygarden](http://support.discoverygarden.ca).

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden](http://www.discoverygarden.ca)
* [Daniel Aitken](daitken@discoverygarden.ca)

## Development

If you would like to contribute to this module, please check out our helpful
[Documentation for Developers](https://github.com/Islandora/islandora/wiki#wiki-documentation-for-developers)
info, [Developers](http://islandora.ca/developers) section on Islandora.ca and
contact [discoverygarden](http://support.discoverygarden.ca).

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
