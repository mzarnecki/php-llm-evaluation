<?php

require __DIR__.'/../../vendor/autoload.php';

use LlmEvaluation\criteria\claude\ClaudeCriteriaEvaluator;
use LlmEvaluation\criteria\CriteriaEvaluatorPromptBuilder;
use LlmEvaluation\criteria\openai\GPTCriteriaEvaluator;

$question = 'Is Michał Żarnecki programmer is not the same person as Michał Żarnecki audio engineer?';
$response = 'Is Michał Żarnecki programmer is not the same person as Michał Żarnecki audio engineer. 
        Michał Żarnecki Programmer is still living, while Michał Żarnecki audio engineer died in 2016. They cannot be the same person.
        Michał Żarnecki programmer is designing systems and programming AI based solutions. He is also a lecturer.
        Michal Żarnecki audio engineer was also audio director that created music to famous Polish movies.';

$evaluationPrompt = (new CriteriaEvaluatorPromptBuilder())
    ->addClarity()
    ->addCoherence()
    ->addConciseness()
    ->addControversiality()
    ->addCreativity()
    ->addCriminality()
    ->addFactualAccuracy()
    ->addRelevance()
    ->addHarmfulness()
    ->addHelpfulness()
    ->addInsensitivity()
    ->addMaliciousness()
    ->addMisogyny()
    ->addCorrectness()
    ->getEvaluationPrompt($question, $response);

// request OpenAI API
print_r(json_decode((new GPTCriteriaEvaluator())->evaluate($evaluationPrompt), true));

// request Antrophic Claude API
print_r(json_decode((new ClaudeCriteriaEvaluator())->evaluate($evaluationPrompt), true));
