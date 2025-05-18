<?php

namespace test\tests\Unit\criteria;

use LlmEvaluation\criteria\CriteriaEvaluatorPromptBuilder;
use PHPUnit\Framework\TestCase;

class CriteriaEvaluatorPromptBuilderTest extends TestCase
{
    public function testBuildingCorrectCriteriaEvaluationPrompt()
    {
        $question = 'Does “Ruby on Rails”, the web framework, have anything to do with Ruby Rails, the country singer?';

        $answer = <<<'TEXT'
    No — they are completely unrelated.
    
    • **Ruby on Rails** (often just “Rails”) is an open-source web-application framework written in the Ruby programming language.
    • **Ruby Rails** (born Ruby Jane Smith, 1999) is an American country & bluegrass fiddler and singer-songwriter.
    
    Aside from sharing the word “Ruby”, the software project and the musician work in entirely different domains.
    TEXT;
        $evaluationPrompt = (new CriteriaEvaluatorPromptBuilder())
            ->addCorrectness()
            ->addHelpfulness()
            ->addRelevance()
            ->getEvaluationPrompt($question, $answer);

        expect($evaluationPrompt)
            ->toBe("You are a helpful assistant that evaluates the quality of an answer based on the following criteria:
correctness: Is the answer accurate, and free of mistakes?
helpfulness: Does the response provide value or solve the user's problem effectively?
relevance: Does the answer address the question accurately?

Score each category above in range 0–5. Use only integer value for each category 
        
        Here is the question: Does “Ruby on Rails”, the web framework, have anything to do with Ruby Rails, the country singer?
       
       
        Here is the answer: No — they are completely unrelated.

• **Ruby on Rails** (often just “Rails”) is an open-source web-application framework written in the Ruby programming language.
• **Ruby Rails** (born Ruby Jane Smith, 1999) is an American country & bluegrass fiddler and singer-songwriter.

Aside from sharing the word “Ruby”, the software project and the musician work in entirely different domains.
        
        
        Output a JSON object with criteria as keys.
        Example output should look like this:
        {
correctness: 3,
helpfulness: 3,
relevance: 3
}
            
        Don't include any additional explanation, just JSON with criteria scores.");
    }
}
