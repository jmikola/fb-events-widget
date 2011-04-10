# fb-events-widget

An embeddable widget to display a feed of upcoming Facebook events. This project
is built with [Silex][1].

## Setup

### Submodule Initialization

    $ git submodule update --init --recursive

### Configuration

The `src/` directory includes a `parameters.ini.dist` file, which should be
copied to `parameters.ini` and populated with your Facebook application's ID and
secret key.

  [1]: http://silex-project.org/
