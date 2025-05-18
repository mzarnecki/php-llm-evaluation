<?php

namespace LlmEvaluation;

class EvaluationResults
{
    /**
     * @param  (float|string)[]  $results
     */
    public function __construct(private readonly string $metricName, private readonly array $results)
    {
    }

    public function getMetricName(): string
    {
        return $this->metricName;
    }

    /**
     * @return (float|string)[]
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
