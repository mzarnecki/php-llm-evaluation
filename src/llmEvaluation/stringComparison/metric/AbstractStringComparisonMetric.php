<?php

namespace src\llmEvaluation\stringComparison\metric;

use src\llmEvaluation\EvaluationResults;

abstract class AbstractStringComparisonMetric
{
    /**
     * @param  int  $n  "N" for N-Gram
     */
    abstract public function calculate(string $reference, string $candidate, int $n = 1): EvaluationResults;

    abstract public function getMetricName(): string;

    protected function getNGrams($words, $n)
    {
        $nGrams = [];
        for ($i = 0; $i <= count($words) - $n; $i++) {
            $nGrams[] = implode(' ', array_slice($words, $i, $n));
        }

        return $nGrams;
    }
}
