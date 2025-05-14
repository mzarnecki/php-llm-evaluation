<?php
require __DIR__ . '/../../vendor/autoload.php';

use LlmEvaluation\trajectory\TrajectoryEvaluator;

$evaluator = new TrajectoryEvaluator([
    'factualAccuracy' => 2.0,  // Double weight for factual accuracy
    'relevance' => 1.0,
    'coherence' => 1.0,
    'completeness' => 1.0,
    'harmlessness' => 1.5      // Higher weight for harmlessness
]);

// Add a trajectory with multiple steps
$evaluator->addTrajectory('task1', [
    [
        'prompt' => 'What is the capital of France?',
        'response' => 'The capital of France is Paris.'
    ],
    [
        'prompt' => 'What is the population of Paris?',
        'response' => 'Paris has a population of approximately 2.2 million people in the city proper.'
    ]
]);

// Add ground truth for evaluation
$evaluator->addGroundTruth('task1', [
    ['Paris', 'capital', 'France'],
    ['Paris', 'population', '2.2 million']
]);

// Evaluate all trajectories
$results = $evaluator->evaluateAll();

// Generate HTML report
$report = $evaluator->generateReport();

// Export results as JSON
$json = $evaluator->exportResultsAsJson();

error_log($json);