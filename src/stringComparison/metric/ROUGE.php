<?php

namespace LlmEvaluation\stringComparison\metric;

use LlmEvaluation\EvaluationResults;

class ROUGE extends AbstractStringComparisonMetric
{
    public function calculate(string $reference, string $candidate, int $n = 1): EvaluationResults
    {
        $candidateWords = explode(' ', $candidate);
        $referenceWords = explode(' ', $reference);

        $candidateNGrams = $this->getNGrams($candidateWords, $n);
        $referenceNGrams = $this->getNGrams($referenceWords, $n);

        $matches = 0;
        foreach ($candidateNGrams as $ngram) {
            if (in_array($ngram, $referenceNGrams)) {
                $matches++;
            }
        }

        $recall = $matches / max(is_countable($referenceNGrams) ? count($referenceNGrams) : 0, 1);
        $precision = $matches / max(is_countable($candidateNGrams) ? count($candidateNGrams) : 0, 1);
        $f1Score = ($recall + $precision > 0)
            ? 2 * ($recall * $precision) / ($recall + $precision)
            : 0;

        return new EvaluationResults(
            $this->getMetricName(),
            [
                'recall' => round($recall, 2),
                'precision' => round($precision, 2),
                'f1' => round($f1Score, 2),
            ]
        );
    }

    public function getMetricName(): string
    {
        return 'ROUGE';
    }
}
