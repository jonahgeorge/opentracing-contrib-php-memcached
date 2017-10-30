<?php

namespace OpenTracing\Memcached;

use OpenTracing\GlobalTracer;
use OpenTracing\Span;
use OpenTracing\Tracer;
use Memcached;

use const OpenTracing\Ext\Tags\COMPONENT;
use const OpenTracing\Ext\Tags\DATABASE_TYPE;
use const OpenTracing\Ext\Tags\DATABASE_STATEMENT;
use const OpenTracing\Ext\Tags\SPAN_KIND;
use const OpenTracing\Ext\Tags\SPAN_KIND_RPC_CLIENT;

class TracingMemcacheClient extends Memcached
{
    /** @var Tracer */
    private $tracer;

    /** @var Span */
    private $parentSpan;

    /** @var Memcached */
    private $memcache;

    /** @var string */
    private $tracePrefix = 'Memcache';

    public function __construct(
        string $persistentId = null,
        Tracer $tracer = null,
        Span $span = null
    )
    {
        parent::__construct($persistentId);
        $this->tracer = $tracer ?? GlobalTracer::get();
        $this->parentSpan = $span;
    }

    public function withSpan(string $operationName, $callable)
    {
        $span = $this->tracer->startSpan(
            $this->getOperationName($operationName),
            [
                'child_of' => $this->parentSpan,
                'tags' => [
                    COMPONENT => 'memcached',
                    DATABASE_TYPE => 'memcache',
                    DATABASE_STATEMENT => $operationName,
                    SPAN_KIND => SPAN_KIND_RPC_CLIENT,
                ],
            ]
        );

        $response = $callable();

        $span->finish();

        return $response;
    }

    private function getOperationName(string $operationName)
    {
        return $this->tracePrefix . '/' . $operationName;
    }

    public function get($key, callable $cache_cb = null, &$cas_token = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $cache_cb, $cas_token) {
            return parent::get($key, $cache_cb, $cas_token);
        });
    }

    public function getByKey($server_key, $key, callable $cache_cb = null, &$cas_token = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $cache_cb, $cas_token) {
            return parent::getByKey($server_key, $key, $cache_cb, $cas_token);
        });
    }

    public function getMulti(array $keys, array &$cas_tokens = null, $flags = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($keys, $cas_tokens, $flags) {
            return parent::getMulti($keys, $cas_tokens, $flags);
        });
    }

    public function getMultiByKey($server_key, array $keys, &$cas_tokens = null, $flags = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $keys, $cas_tokens, $flags) {
            return parent::getMultiByKey($server_key, $keys, $cas_tokens, $flags);
        });
    }

    public function getDelayed(array $keys, $with_cas = null, callable $value_cb = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($keys, $with_cas, $value_cb) {
            return parent::getDelayed($keys, $with_cas, $value_cb);
        });
    }

    public function getDelayedByKey($server_key, array $keys, $with_cas = null, callable $value_cb = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $keys, $with_cas, $value_cb) {
            return parent::getDelayedByKey($server_key, $keys, $with_cas, $value_cb);
        });
    }

    public function fetch()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::fetch();
        });
    }

    public function fetchAll()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::fetchAll();
        });
    }

    public function set($key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $value, $expiration) {
            return parent::set($key, $value, $expiration);
        });
    }

    public function setByKey($server_key, $key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $value, $expiration) {
            return parent::setByKey($server_key, $key, $value, $expiration);
        });
    }

    public function touch($key, $expiration)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $expiration) {
            return parent::touch($key, $expiration);
        });
    }

    public function touchByKey($server_key, $key, $expiration)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $expiration) {
            return parent::touchByKey($server_key, $key, $expiration);
        });
    }

    public function setMulti(array $items, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($items, $expiration) {
            return parent::setMulti($items, $expiration);
        });
    }

    public function setMultiByKey($server_key, array $items, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $items, $expiration) {
            return parent::setMultiByKey($server_key, $items, $expiration);
        });
    }

    public function cas($cas_token, $key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($cas_token, $key, $value, $expiration) {
            return parent::cas($cas_token, $key, $value, $expiration);
        });
    }

    public function casByKey($cas_token, $server_key, $key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($cas_token, $server_key, $key, $value, $expiration) {
            return parent::casByKey($cas_token, $server_key, $key, $value, $expiration);
        });
    }

    public function add($key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $value, $expiration) {
            return parent::add($key, $value, $expiration);
        });
    }

    public function addByKey($server_key, $key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $value, $expiration) {
            return parent::addByKey($server_key, $key, $value, $expiration);
        });
    }

    public function append($key, $value)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $value) {
            return parent::append($key, $value);
        });
    }

    public function appendByKey($server_key, $key, $value)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $value) {
            return parent::appendByKey($server_key, $key, $value);
        });
    }

    public function prepend($key, $value)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $value) {
            return parent::prepend($key, $value);
        });
    }

    public function prependByKey($server_key, $key, $value)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $value) {
            return parent::prependByKey($server_key, $key, $value);
        });
    }

    public function replace($key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $value, $expiration) {
            return parent::replace($key, $value, $expiration);
        });
    }

    public function replaceByKey($server_key, $key, $value, $expiration = null)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $value, $expiration) {
            return parent::replaceByKey($server_key, $key, $value, $expiration);
        });

    }

    public function delete($key, $time = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $time) {
            return parent::delete($key, $time);
        });
    }

    public function deleteMulti(array $keys, $time = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($keys, $time) {
            return parent::deleteMulti($keys, $time);
        });
    }

    public function deleteByKey($server_key, $key, $time = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $time) {
            return parent::deleteByKey($server_key, $key, $time);
        });
    }

    public function deleteMultiByKey($server_key, array $keys, $time = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $keys, $time) {
            return parent::deleteMultiByKey($server_key, $keys, $time);
        });
    }

    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $offset, $initial_value, $expiry) {
            return parent::increment($key, $offset, $initial_value, $expiry);
        });

    }

    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($key, $offset, $initial_value, $expiry) {
            return parent::decrement($key, $offset, $initial_value, $expiry);
        });

    }

    public function incrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $offset, $initial_value, $expiry) {
            return parent::incrementByKey($server_key, $key, $offset, $initial_value, $expiry);
        });

    }

    public function decrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key, $key, $offset, $initial_value, $expiry) {
            return parent::decrementByKey($server_key, $key, $offset, $initial_value, $expiry);
        });

    }

    public function getServerByKey($server_key)
    {
        return $this->withSpan(__FUNCTION__, function () use ($server_key) {
            return parent::getServerByKey($server_key);
        });
    }

    public function resetServerList()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::resetServerList();
        });
    }

    public function quit()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::quit();
        });
    }

    public function getStats()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::getStats();
        });
    }

    public function getVersion()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::getVersion();
        });
    }

    public function getAllKeys()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::getAllKeys();
        });
    }

    public function flush($delay = 0)
    {
        return $this->withSpan(__FUNCTION__, function () use ($delay) {
            return parent::flush($delay);
        });
    }

    public function isPersistent()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::isPersistent();
        });
    }

    public function isPristine()
    {
        return $this->withSpan(__FUNCTION__, function () {
            return parent::isPristine();
        });
    }
}
