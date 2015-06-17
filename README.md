# Ten Million with a Hat

## Introduction

Adds a drush script for ingesting many objects; say, ten million.

Checks all your collections for their policies, and then just starts ingesting
random stuff with whatever models their COLLECTION_POLICY says are valid.

## Usage

- STEP 1: drush -u 1 ten-million-with-a-hat-ingest --objects=10000000
- STEP 2: wait for like a week
- STEP 3: actually some part of the stack will probably poop the bed before then (looking at you, fedora ಠ_ಠ).

Some notes on the Drush options: by default, the islandora:collectionCModel is excluded. You can override this by passing an empty string in to `--exclude-collections`. In addition, under normal circumstances, if a content model has no associated collections, it is skipped over during the ingest process. This can be overridden by using a `--restrict-cms` list that includes that includes that particular content model; it will be added to islandora:dummy_collection. Note that this collection will not be ingested.

## Customization

A hook is available, `hook_ten_million_with_a_hat_also_do_these_things`, to modify each ingested object. Check ten_million_with_a_hat.api.php for more details. Also take a look at the 'modules' folder provided by this repository for example implementations that may be appropriate to your use case.

Some additional modules that implement this hook are maintained in private repositories, as they work with Islandora extensions that are either privately maintained or otherwise inappropriate to place in a public repository. Contact [QA Dan](daitken@discoverygarden.ca) for details.

## Jargon

Because the words "object created via the Ten Million with a Hat ingest process" are a bit long to type out repeatedly in various formss and contexts, the word 'hat' or 'hats' is used to describe these objects in all instances througout the module EXCEPT:

- the exact written phrase 'Ten Million with a Hat', and
- functions, variables, constants, etc. using the string `ten_million_with_a_hat`.

In these cases, 'hat' refers to the Tuque library.

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
