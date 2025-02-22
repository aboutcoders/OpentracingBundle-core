<?php

declare(strict_types=1);

namespace Auxmoney\OpentracingBundle\Tests\Mock;

use OpenTracing\Mock\MockSpan;
use OpenTracing\Mock\MockTracer as OriginalMockTracer;
use OpenTracing\NoopSpanContext;
use OpenTracing\Scope;
use OpenTracing\ScopeManager;
use OpenTracing\Span;
use OpenTracing\SpanContext;
use OpenTracing\Tracer;
use const OpenTracing\Formats\TEXT_MAP;

class MockTracer implements Tracer
{
    private $mockTracer;

    public function __construct()
    {
        $actualSpanContext = null;

        $extractor = function ($carrier) use (&$actualCarrier) {
            $actualCarrier = $carrier;
            return new NoopSpanContext();
        };

        $this->mockTracer = new OriginalMockTracer([], [TEXT_MAP => $extractor]);
    }

    public function getScopeManager(): ScopeManager
    {
        return $this->mockTracer->getScopeManager();
    }

    public function getActiveSpan(): ?Span
    {
        return $this->mockTracer->getActiveSpan();
    }

    public function startActiveSpan($operationName, $options = []): Scope
    {
        return $this->mockTracer->startActiveSpan($operationName, $options);
    }

    public function startSpan($operationName, $options = []): Span
    {
        return $this->mockTracer->startSpan($operationName, $options);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function inject(SpanContext $spanContext, $format, &$carrier): void
    {
        $carrier['made_up_header'] = '1:2:3:4';
    }

    public function extract($format, $carrier): ?SpanContext
    {
        $val = $this->mockTracer->extract($format, $carrier);

        return $this->mockTracer->extract($format, $carrier);
    }

    public function flush(): void
    {
        $this->mockTracer->flush();
    }

    /**
     * @return MockSpan[]
     */
    public function getSpans(): array
    {
        return $this->mockTracer->getSpans();
    }
}
