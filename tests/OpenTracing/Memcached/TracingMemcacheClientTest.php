<?php

namespace OpenTracing\Memcached;

use PHPUnit\Framework\TestCase;
use OpenTracing\Scope as ScopeInterface;
use OpenTracing\Tracer as TracerInterface;
use Memcached;

class TracingMemcacheClientTest extends TestCase
{
    function testWithSpan()
    {
        // Given
        $scope = $this->createMock(ScopeInterface::class);
        $tracer = $this->createMock(TracerInterface::class);

        $tracer->method('startActiveSpan')->willReturn($scope);

        $client = new TracingMemcacheClient('', $tracer);

        // Then
        $tracer->expects($this->once())->method('startActiveSpan');

        // When
        $client->withSpan('test-operation', function() {
            // Do something
        });
    }
}
