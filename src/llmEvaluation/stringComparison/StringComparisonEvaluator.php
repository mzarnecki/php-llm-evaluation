<?php

namespace src\llmEvaluation\stringComparison;

use src\llmEvaluation\EvaluationResults;
use src\llmEvaluation\stringComparison\metric\BLEU;
use src\llmEvaluation\stringComparison\metric\METEOR;
use src\llmEvaluation\stringComparison\metric\ROUGE;

class StringComparisonEvaluator
{
    public function __construct(
        private readonly BLEU $bleu,
        private readonly ROUGE $rouge,
        private readonly METEOR $meteor
    ) {
    }

    public function calculateBLEU(string $reference, string $candidate, int $n = 1): EvaluationResults
    {
        return $this->bleu->calculate($reference, $candidate, $n);
    }

    public function calculateROUGE(string $reference, string $candidate, int $n = 1): EvaluationResults
    {
        return $this->rouge->calculate($reference, $candidate, $n);
    }

    public function calculateMETEOR(string $reference, string $candidate, int $n = 1): EvaluationResults
    {
        return $this->meteor->calculate($reference, $candidate, $n);
    }
}
