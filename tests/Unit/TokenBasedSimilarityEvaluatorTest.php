<?php

declare(strict_types=1);

namespace tests\Unit;

use PHPUnit\Framework\TestCase;
use src\llmEvaluation\stringComparison\metric\BLEU;
use src\llmEvaluation\stringComparison\metric\METEOR;
use src\llmEvaluation\stringComparison\metric\ROUGE;
use src\llmEvaluation\stringComparison\StringComparisonEvaluator;

class TokenBasedSimilarityEvaluatorTest extends TestCase
{
    public function testCalculateRouge(): void
    {
        $reference = "that's the way cookie crumbles";
        $candidate = 'this is the way cookie is crashed';

        $results = $this->getSut()->calculateROUGE($reference, $candidate);
        $rougeScores = $results->getResults();

        $this->assertEquals([
            'precision' => 0.43,
            'recall' => 0.60,
            'f1' => 0.50,
        ],
            $rougeScores
        );
    }

    public function testCalculateBleu(): void
    {
        $reference = "that's the way cookie crumbles";
        $candidate = 'this is the way cookie is crashed';

        $results = $this->getSut()->calculateBleu($reference, $candidate);
        $score = $results->getResults();

        $this->assertEquals(0.43, $score['score']);
    }

    public function testCalculateMeteor(): void
    {
        $reference = "The quick brown fox jumps over the lazy dog";
        $candidate = 'The quick brown dog jumps over the lazy fox';

        $results = $this->getSut()->calculateMETEOR($reference, $candidate);
        $score = $results->getResults();

        $this->assertEquals(0.96, $score['score']);
    }

    private function getSut(): StringComparisonEvaluator
    {
        return new StringComparisonEvaluator(
            new BLEU(),
            new ROUGE(),
            new METEOR()
        );
    }
}
