# Ten Million with a Hat

## Introduction

Adds a drush script for ingesting many objects; say, ten million.

Checks all your collections for their policies, and then just starts ingesting
random stuff with whatever models their COLLECTION_POLICY says are valid.

## Usage

- STEP 1: drush -u 1 ten-million-with-a-hat-ingest --objects=10000000
- STEP 2: wait for like a week
- STEP 3: actually some part of the stack will probably poop the bed before then (looking at you, fedora ಠ_ಠ).

## Customization

A hook is available, `hook_ten_million_with_a_hat_also_do_these_things`, to modify each ingested object. Check ten_million_with_a_hat.api.php for more details.

## Jargon

Because the words "object created via the Ten Million with a Hat ingest process" are a bit long to type out completely, the word "hat" is used to describe these objects in all instances througout the module EXCEPT:

- the exact written phrase 'Ten Million with a Hat', and
- functions, variables, constants, etc. using the string `ten_million_with_a_hat`.

In these cases, 'hat' refers to the Tuque library.

## Troubleshooting/Issues

Having problems or solved a problem? Check out the Islandora google groups for a solution.

* [Islandora Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora)
* [Islandora Dev Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora-dev)

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden Inc.](https://github.com/discoverygarden)

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
