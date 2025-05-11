<?php

namespace src\llmEvaluation\stringComparison\metric;

use src\llmEvaluation\EvaluationResults;

class BLEU extends AbstractStringComparisonMetric
{
    public function calculate(string $reference, string $candidate, $n = 1): EvaluationResults
    {
        $candidateWords = explode(' ', $candidate);
        $referenceWords = explode(' ', $reference);
        $candidateLength = count($candidateWords);
        $referenceLength = count($referenceWords);

        $nGramMatches = [];
        for ($i = 1; $i <= $n; $i++) {
            $candidateNGrams = $this->getNGrams($candidateWords, $i);
            $referenceNGrams = $this->getNGrams($referenceWords, $i);

            $matches = 0;
            foreach ($candidateNGrams as $ngram) {
                if (in_array($ngram, $referenceNGrams)) {
                    $matches++;
                }
            }
            $nGramMatches[$i] = $matches / max(count($candidateNGrams), 1);
        }

        $precision = array_product($nGramMatches);
        $brevityPenalty = ($candidateLength > $referenceLength)
            ? 1
            : exp(1 - ($referenceLength / max($candidateLength, 1)));
        $result = round($brevityPenalty * pow($precision, 1 / $n), 2);

        $results = new EvaluationResults(
            $this->getMetricName(),
            ['score' => $result]
        );

        return $results;
    }

    public function getMetricName(): string
    {
        return 'BLEU';
    }
}
