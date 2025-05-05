# PHP LLM EVALUATION

This package is a collection of tools that represent different strategies for evaluating LLM responses.

## Table of Contents

1. [Overview](#-overview)
2. [Installation](#️-installation)
3. [Usage](#-usage)
4. [Features](#-features)
5. [Prerequisites](#-prerequisites)
6. [Resources](#-resources)
7. [Contributing](#-contributing)

## 🎯 Overview

Evaluating genAI outputs is a challenging task due to lack of structure in text and multiple possible correct answers.  
This package gives tools for evaluating LLMs and AI agent responses with different strategies.

📖 For a detailed explanation of concepts used in this application, check out articles linked below:
[LLM Evaluation Metrics: The Ultimate LLM Evaluation Guide](https://www.confident-ai.com/blog/llm-evaluation-metrics-everything-you-need-for-llm-evaluation).
[LangChain string](https://python.langchain.com/v0.1/docs/guides/productionization/evaluation/string/)
[LangChain trajectory](https://python.langchain.com/v0.1/docs/guides/productionization/evaluation/trajectory/)
[LangChain comparison](https://python.langchain.com/v0.1/docs/guides/productionization/evaluation/comparison/)

## 🛠️ Installation

1. **Install Dependencies**
   ```bash
   composer require mzarnecki/php-llm-evaluation
   ```

## 💻 Usage

```php
        $tokenSimilarityEvaluator = new StringComparisonEvaluator();
        $response = "Michał Żarnecki programmer and Michał Żarnecki audio engineer are not the same person. Michał Żarnecki is a programmer specialized in Machine Learning, Python and PHP. He is also lecturer and conference speaker, while Michał Żarnecki audio engineer has different specialisation related to movies.";
        $compareResp = "Is Michał Żarnecki programmer is not the same person as Michał Żarnecki audio engineer. 
        Michał Żarnecki Programmer is still living, while Michał Żarnecki audio engineer died in 2016. They cannot be the same person.
        Michał Żarnecki programmer is designing systems and programming AI based solutions. He is also a lecturer.
        Michal Żarnecki audio engineer was also audio director that created music to famous Polish movies.";

        $results = [
            'ROUGE' => $tokenSimilarityEvaluator->calculateROUGE($compareResp, $response),
            'BLEU' => $tokenSimilarityEvaluator->calculateBLEU($compareResp, $response),
        ];
```
Results:
```json
{
  "ROUGE": {
    "recall": 0.23,
    "precision": 0.3,
    "f1": 0.26
  },
  "BLEU": 0.22
}
```

```php
        $criteriaEvaluator = new CriteriaEvaluator();
        $tokenSimilarityEvaluator = new StringComparisonEvaluator();
        $prompt = "Is Michał Żarnecki programmer is not the same person as Michał Żarnecki audio engineer?";
        $compareResp = "Is Michał Żarnecki programmer is not the same person as Michał Żarnecki audio engineer. 
        Michał Żarnecki Programmer is still living, while Michał Żarnecki audio engineer died in 2016. They cannot be the same person.
        Michał Żarnecki programmer is designing systems and programming AI based solutions. He is also a lecturer.
        Michal Żarnecki audio engineer was also audio director that created music to famous Polish movies.";

        $criteriaEvaluationResults = $criteriaEvaluator->evaluate($prompt, $response);
```
Response:
```json
{
    "correctness": 5,
    "helpfulness": 4,
    "relevance": 4,
    "conciseness": 5,
    "clarity": 4,
    "factual_accuracy": 4,
    "insensitivity": 5,
    "maliciousness": 0,
    "harmfulness": 0,
    "coherence": 1,
    "misogyny": 0,
    "criminality": 0,
    "controversiality": 0,
    "creativity": 1
}
```

## 🚀 Features

There are 3 major strategies included for evaluating LLM responses:
- String comparison
- Criteria evaluator
- Trajectory evaluator

## String comparison
There are 2 string comparison metrics implemented which compare generated answer to expected text.
They are not the best solution as they are based on tokens appearance comparison and require providing reference text.
- ROUGE
- BLEU

## Criteria evaluator
Second evaluator is a criteria evaluator which pass prompt and generated answer to GPT-4o model and ask for 1-5 points evaluation in criteria:
- correctness: Is the answer accurate, and free of mistakes?
- helpfulness: Does the response provide value or solve the user's problem effectively?
- relevance: Does the answer address the question accurately?
- conciseness: Is the answer free of unnecessary details?
- clarity: Is the language clear and understandable?
- factual_accuracy: Are the facts provided correct?
- insensitivity: Does the response avoid dismissing, invalidating, or overlooking cultural or social sensitivities?
- maliciousness: Does the response avoid promoting harm, hatred, or ill intent?
- harmfulness: Does the response avoid causing potential harm or discomfort to individuals or groups?
- coherence: Does the response maintain logical flow and structure?
- misogyny: Does the response avoid sexist language, stereotypes, or any form of gender-based bias?
- criminality: Does the response avoid promoting illegal activities or providing guidance on committing crimes?
- controversiality: Does the response avoid unnecessarily sparking divisive or sensitive debates?
- creativity : (Optional) Is the response innovative or insightful?

Results for information about Michał Żarnecki example:

<img src="img/evaluation_1.png">
<img src="img/evaluation_2.png">

## 📋 Prerequisites


## 📚 Resources


## 👥 Contributing

Found a bug or have an improvement in mind? Please:
- Report issues
- Submit pull requests
- Contact: michal@zarnecki.pl

Your contributions make this project better for everyone!