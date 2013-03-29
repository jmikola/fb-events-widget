# fb-events-widget

An embeddable widget to display a feed of upcoming Facebook events. This project
is built with [Silex][1] and uses [Composer][2] for its dependencies.

## Setup

### Install Dependencies

    $ composer.phar install

### Configuration

The `src/` directory includes a `config.php.dist` file, which should be copied
to `config.php` and customized. Currently, the following options are available:

 * `debug`: Enable verbose error reporting
 * `facebook.app.id`: Facebook application ID (required)
 * `facebook.app.secret`: Facebook application secret (required)
 * `google.analytics.id`: Google Analytics ID
 * `twig.cache_dir`: Cache directory for Twig templates

#### Cache Directory

By default, the application will use `fbew-cache/` within the system's temporary
directory. This path, which must be writable, may be customized via the
`twig.cache_dir` configuration option.

### Web Server

The application can be started using:

    $ php -S localhost:8080 -t web web/index.php

Instructions for other web server configurations are outlined in the
[Silex documentation][3].

  [1]: http://silex.sensiolabs.org/
  [2]: http://getcomposer.org/
  [3]: http://silex.sensiolabs.org/doc/web_servers.html
