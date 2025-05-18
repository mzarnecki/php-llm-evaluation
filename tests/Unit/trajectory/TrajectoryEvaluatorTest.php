<?php

namespace test\tests\Unit\trajectory;

use LlmEvaluation\trajectory\TrajectoryEvaluator;
use PHPUnit\Framework\TestCase;

class TrajectoryEvaluatorTest extends TestCase
{
    public function testTrajectoryEvaluation()
    {
        $evaluator = new TrajectoryEvaluator([
            'factualAccuracy' => 2.0,
            'relevance' => 1.0,
            'coherence' => 1.0,
            'completeness' => 1.0,
            'harmlessness' => 1.5,
        ]);

        $evaluator->addTrajectory('task1', [
            [
                'prompt' => 'What is the capital of France?',
                'response' => 'The capital of France is Paris.',
            ],
            [
                'prompt' => 'What is the population of Paris?',
                'response' => 'Paris has a population of approximately 2.2 million people in the city proper.',
            ],
        ]);

        $evaluator->addGroundTruth('task1', [
            ['Paris', 'capital', 'France'],
            ['Paris', 'population', '2.2 million'],
        ]);

        $results = $evaluator->evaluateAll();
        $expected = [
            'task1' => [
                'trajectoryId' => 'task1',
                'stepScores' => [
                    [
                        'factualAccuracy' => 1.0,
                        'completeness' => 1.0,
                        'harmlessness' => 1.0,
                        'relevance' => 1.0,
                    ],
                    [
                        'factualAccuracy' => 1.0,
                        'completeness' => 1.0,
                        'harmlessness' => 1.0,
                        'relevance' => 1.0,
                    ],
                ],
                'metricScores' => [
                    'factualAccuracy' => 1.0,
                    'completeness' => 1.0,
                    'harmlessness' => 1.0,
                    'relevance' => 1.0,
                ],
                'overallScore' => 0.85,
                'interactionCount' => 2,
                'passed' => true,
            ],
        ];
        $this->assertEquals($expected, $results);
    }
}
