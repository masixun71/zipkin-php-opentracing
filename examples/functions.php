<?php

use Zipkin\Endpoint;
use Zipkin\Reporters\Http\CurlFactory;
use Zipkin\Samplers\BinarySampler;
use Zipkin\TracingBuilder;

function build_tracer($serviceName, $ipv4, $port = null)
{
    $endpoint = Endpoint::create($serviceName, $ipv4, null, $port);
    $logger = new \Psr\Log\NullLogger();
    $clientFactory = CurlFactory::create();
    $reporter = new Zipkin\Reporters\Http($clientFactory, $logger);
    $sampler = BinarySampler::createAsAlwaysSample();
    $tracing = TracingBuilder::create()
        ->havingLocalEndpoint($endpoint)
        ->havingSampler($sampler)
        ->havingReporter($reporter)
        ->build();

    return new ZipkinOpenTracing\Tracer($tracing);
}
