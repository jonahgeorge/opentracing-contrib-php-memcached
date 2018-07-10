[![Build Status](https://travis-ci.org/jonahgeorge/opentracing-contrib-php-memcached.svg?branch=master)](https://travis-ci.org/jonahgeorge/opentracing-contrib-php-memcached)

# PHP Memcached OpenTracing 

This package enables distributed tracing for the PHP Memcached library.

## Getting Started

```php
<?php

use OpenTracing\Memcached\TracingMemcacheClient;

$client = new TracingMemcacheClient();

$value = $client->get('test-key');
```

## License

[MIT License](./LICENSE).
