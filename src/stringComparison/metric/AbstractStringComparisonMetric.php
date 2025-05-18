<?php

namespace LlmEvaluation\stringComparison\metric;

use LlmEvaluation\EvaluationResults;

abstract class AbstractStringComparisonMetric
{
    /**
     * @param  int  $n  "N" for N-Gram
     */
    abstract public function calculate(string $reference, string $candidate, int $n = 1): EvaluationResults;

    /**
     * @param  string[]  $words
     * @return string[]
     */
    protected function getNGrams(array $words, int $n): array
    {
        $nGrams = [];
        $wordsCount = count($words);
        for ($i = 0; $i <= $wordsCount - $n; $i++) {
            $nGrams[] = implode(' ', array_slice($words, $i, $n));
        }

        return $nGrams;
    }
}
