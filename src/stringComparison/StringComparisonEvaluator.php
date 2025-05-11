<?php

namespace LlmEvaluation\stringComparison;

use LlmEvaluation\EvaluationResults;
use LlmEvaluation\stringComparison\metric\BLEU;
use LlmEvaluation\stringComparison\metric\METEOR;
use LlmEvaluation\stringComparison\metric\ROUGE;

class StringComparisonEvaluator
{
    private readonly BLEU $bleu;

    private readonly ROUGE $rouge;

    private readonly METEOR $meteor;

    public function __construct()
    {
        $this->bleu = new BLEU();
        $this->rouge = new ROUGE();
        $this->meteor = new METEOR();
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
