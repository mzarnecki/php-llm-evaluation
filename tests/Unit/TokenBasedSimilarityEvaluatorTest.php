<?php

declare(strict_types=1);

namespace tests\Unit;

use PHPUnit\Framework\TestCase;
use src\llmEvaluation\stringComparison\StringComparisonEvaluator;

class TokenBasedSimilarityEvaluatorTest extends TestCase
{
    public function testCalculateRouge(): void
    {
        $reference = "that's the way cookie crumbles";
        $candidate = 'this is the way cookie is crashed';

        $rougeScores = (new StringComparisonEvaluator())->calculateROUGE($reference, $candidate);

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

        $bleuScore = (new StringComparisonEvaluator())->calculateBleu($reference, $candidate, 1);

        $this->assertEquals(0.43, $bleuScore);
    }
}
