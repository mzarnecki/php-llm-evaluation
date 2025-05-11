<?php

namespace src\llmEvaluation;

class EvaluationResults
{
    private string $metricName;

    private array $results;

    public function __construct(string $metricName, array $results)
    {
        $this->metricName = $metricName;
        $this->results = $results;
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
