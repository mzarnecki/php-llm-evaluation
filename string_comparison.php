<?php

use LlmEvaluation\stringComparison\StringComparisonEvaluator;

require __DIR__ . '/vendor/autoload.php';

$tokenSimilarityEvaluator = new StringComparisonEvaluator();
$reference = "that's the way cookie crumbles";
$candidate = 'this is the way cookie is crashed';

$results = [
    'ROUGE' => $tokenSimilarityEvaluator->calculateROUGE($reference, $candidate),
    'BLEU' => $tokenSimilarityEvaluator->calculateBLEU($reference, $candidate),
    'METEOR' => $tokenSimilarityEvaluator->calculateMETEOR($reference, $candidate),
];

echo print_r($results);