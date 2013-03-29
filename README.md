# fb-events-widget

An embeddable widget to display a feed of upcoming Facebook events. This project
is built with [Silex][1] and uses [Composer][2] for its dependencies.

## Setup

### Install Dependencies

    $ composer.phar install

### Cache Directory

Ensure the `cache/` directory is writable by your web server.

### Configuration

The `src/` directory includes a `config.php.dist` file, which should be copied
to `config.php` and customized with your Facebook application's ID and secret
key. Additionally, you may specify a Google Analytics ID.

  [1]: http://silex.sensiolabs.org/
  [2]: http://getcomposer.org/
