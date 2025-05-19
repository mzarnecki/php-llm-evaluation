<?php

require __DIR__.'/../../vendor/autoload.php';

use LlmEvaluation\trajectory\TrajectoryEvaluator;

$trajectory = [
    ['prompt' => 'List the three cheapest airlines on JFK → CDG for 10 July – 17 July.', 'response' => 'Delta $820, FrenchBee $750, Norse $710.'],
    ['prompt' => 'Give seat-class and aircraft for the Norse flight.', 'response' => 'Economy (LowFare) on Boeing 787-9.'],
    ['prompt' => 'Estimate round-trip CO₂ for one passenger.', 'response' => 'About 1.6 t CO₂'],
];

$ground = [
    ['Norse', '710'],
    ['Economy', '787-9'],
    ['1.4 t CO2'],
];

$evaluator = new TrajectoryEvaluator([
    'factualAccuracy' => 2.0,
    'coherence' => 1.0,
    'completeness' => 1.0,
    'harmlessness' => 1.5,
]);

// Add a trajectory with multiple steps
$evaluator->addTrajectory('flight-planner', $trajectory);
// Add ground truth for evaluation
$evaluator->addGroundTruth('flight-planner', $ground);

// Evaluate all trajectories
$results = $evaluator->evaluateAll();

error_log(json_encode($results, JSON_PRETTY_PRINT));
