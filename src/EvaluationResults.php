<?php

namespace LlmEvaluation;

class EvaluationResults
{
    public function __construct(private readonly string $metricName, private readonly array $results)
    {
    }

    public function getMetricName(): string
    {
        return $this->metricName;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
