<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Zipkin\Samplers\BinarySampler;

return [
    'default' => \Hyperf\Support\env('TRACER_DRIVER', 'noop'),
    'enable'  => [
        'guzzle'    => \Hyperf\Support\env('TRACER_ENABLE_GUZZLE', false),
        'redis'     => \Hyperf\Support\env('TRACER_ENABLE_REDIS', false),
        'db'        => \Hyperf\Support\env('TRACER_ENABLE_DB', false),
        'method'    => \Hyperf\Support\env('TRACER_ENABLE_METHOD', true),
        'exception' => \Hyperf\Support\env('TRACER_ENABLE_EXCEPTION', false)
    ],
    'tracer'  => [
        'noop' => [
            'driver' => \Crayoon\HyperfGrpc\Tracer\TracerFactory::class
        ],
        'zipkin' => [
            'driver'  => Hyperf\Tracer\Adapter\ZipkinTracerFactory::class,
            'app'     => [
                'name' => \Hyperf\Support\env('APP_NAME', 'skeleton'),
                // Hyperf will detect the system info automatically as the value if ipv4, ipv6, port is null
                'ipv4' => '127.0.0.1',
                'ipv6' => null,
                'port' => 9501,
            ],
            'options' => [
                'endpoint_url' => \Hyperf\Support\env('ZIPKIN_ENDPOINT_URL', 'http://localhost:9411/api/v2/spans'),
                'timeout'      => \Hyperf\Support\env('ZIPKIN_TIMEOUT', 1),
            ],
            'sampler' => BinarySampler::createAsAlwaysSample(),
        ],
        'jaeger' => [
            'driver'  => Hyperf\Tracer\Adapter\JaegerTracerFactory::class,
            'name'    => \Hyperf\Support\env('JAEGER_NAME', 'skeleton'),
            'options' => [
                /*
                 * You can uncomment the sampler lines to use custom strategy.
                 *
                 * For more available configurations,
                 * @see https://github.com/jonahgeorge/jaeger-client-php
                 */
                'sampler'       => [
                    'type'  => \Jaeger\SAMPLER_TYPE_CONST,
                    'param' => true,
                ],
                'local_agent'   => [
                    'reporting_host' => \Hyperf\Support\env('JAEGER_REPORTING_HOST', 'jaeger'),
                    'reporting_port' => \Hyperf\Support\env('JAEGER_REPORTING_PORT', 14268),
                ],
                'dispatch_mode' => \Jaeger\Config::JAEGER_OVER_BINARY_HTTP
            ],
        ],
    ],
    'tags'    => [
        'http_client' => [
            'http.url'         => 'http.url',
            'http.method'      => 'http.method',
            'http.status_code' => 'http.status_code',
        ],
        'redis'       => [
            'arguments' => 'arguments',
            'result'    => 'result',
        ],
        'db'          => [
            'db.query'      => 'db.query',
            'db.statement'  => 'db.statement',
            'db.query_time' => 'db.query_time',
        ],
        'exception'   => [
            'class'       => 'exception.class',
            'code'        => 'exception.code',
            'message'     => 'exception.message',
            'stack_trace' => 'exception.stack_trace',
        ],
        'request'     => [
            'path'   => 'request.path',
            'method' => 'request.method',
            'header' => 'request.header',
        ],
        'coroutine'   => [
            'id' => 'coroutine.id',
        ],
        'response'    => [
            'status_code' => 'response.status_code',
        ],
    ],
];
