RdnTrailingSlash
================

The **RdnTrailingSlash** ZF2 module trims the trailing slash in a path and then redirects the request if it does not match an existing route.

## Installation

1. Use `composer` to require the `radnan/rdn-trailing-slash` package:

   ~~~bash
   $ composer require radnan/rdn-trailing-slash:1.*
   ~~~

2. Activate the module by including it in your `application.config.php` file:

   ~~~php
   <?php

   return array(
       'modules' => array(
           'RdnTrailingSlash',
           // ...
       ),
   );
   ~~~

That's it! The module will take care of the rest.
