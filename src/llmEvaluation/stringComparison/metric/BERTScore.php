<?php

declare(strict_types=1);

namespace src\llmEvaluation\stringComparison\metric;

use src\llmEvaluation\EvaluationResults;
use GuzzleHttp\Client;
use RuntimeException;

/**
 * Pure‑PHP implementation of **BERTScore** (Zhang et al., 2020) that relies on
 * the Hugging Face Inference API to obtain token‑level BERT embeddings and then
 * computes precision, recall and F1 in PHP without any Python dependencies.
 *
 * ### Install
 * ```bash
 * composer require guzzlehttp/guzzle
 * ```
 *
 * ### Environment
 * ```bash
 * export HF_TOKEN="<your‑huggingface‑api‑token>"
 * ```
 * Pass the token to the constructor or set `HF_TOKEN` in env‑vars.
 *
 * ### Algorithm (default variant in the original paper)
 * 1. Call `feature-extraction` pipeline for *reference* and *candidate* →
 *    matrices **R**∈ℝ<sup>n×d</sup>, **C**∈ℝ<sup>m×d</sup>.
 * 2. Cosine‑similarity matrix **S** = normalised(**C**)·normalised(**R**)ᵀ.
 * 3. Precision = (1/m)·Σ<sub>i</sub> max<sub>j</sub> Sᵢⱼ.
 * 4. Recall    = (1/n)·Σ<sub>j</sub> max<sub>i</sub> Sᵢⱼ.
 * 5. F1        = 2·P·R / (P+R).
 *
 * No IDF weighting or rescaling is applied; add it if you need closer parity
 * with the reference implementation.
 */
final class BERTScore extends AbstractStringComparisonMetric
{
    private string $apiUrl;
    private string $apiToken;
    private Client $http;

    /**
     * @param string      $apiToken  HF personal access token ("read" scope)
     * @param string|null $model     HF model id with `feature-extraction`
     */
    public function __construct(?string $apiToken = null, string $model = 'bert-base-uncased')
    {
        $this->apiToken = $apiToken ?? getenv('HF_TOKEN') ?: '';
        if ($this->apiToken === '') {
            throw new RuntimeException('Hugging Face API token missing. Pass it to the constructor or set HF_TOKEN env‑var.');
        }
        $this->apiUrl = 'https://api-inference.huggingface.co/models/' . $model;
        $this->http   = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'timeout' => 60,
        ]);
    }

    public function getMetricName(): string
    {
        return 'BERTScore';
    }

    /**
     * The `$n` parameter is ignored—BERTScore works on sub‑token embeddings.
     */
    public function calculate(string $reference, string $candidate, int $n = 1): EvaluationResults
    {
        $refEmb  = $this->embed($reference); // [n][d]
        $candEmb = $this->embed($candidate); // [m][d]

        if ($refEmb === [] || $candEmb === []) {
            return new EvaluationResults($this->getMetricName(),  [
                'precision' => 0.0,
                'recall'    => 0.0,
                'note'      => 'Empty embedding; maybe text too short?',
            ]);
        }

        [$precision, $recall] = $this->precisionRecall($candEmb, $refEmb);
        $f1 = ($precision + $recall) > 0.0 ? 2 * $precision * $recall / ($precision + $recall) : 0.0;

        return new EvaluationResults($this->getMetricName(), [
            'f1' => $f1,
            'precision' => $precision,
            'recall'    => $recall,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /* Internal helpers                                                   */
    /* ------------------------------------------------------------------ */

    /**
     * Calls the HF feature‑extraction API and returns an array of token vectors.
     * @return float[][] Matrix shape = [tokens][hidden]
     */
    private function embed(string $text): array
    {
        $resp = $this->http->post($this->apiUrl, [
            'body' => json_encode([
                'inputs' => $text,
                'options' => ['wait_for_model' => true],
            ], JSON_THROW_ON_ERROR),
        ]);

        if ($resp->getStatusCode() !== 200) {
            throw new RuntimeException('HF API error: ' . $resp->getBody());
        }

        $data = json_decode((string) $resp->getBody(), true, 512, JSON_THROW_ON_ERROR);
        // Response structure: [1][tokens][hidden_size]
        return isset($data[0]) && is_array($data[0]) ? $data[0] : [];
    }

    /**
     * Computes precision and recall following BERTScore definition.
     * @param float[][] $candEmb [m][d]
     * @param float[][] $refEmb  [n][d]
     * @return array{0: float, 1: float} [precision, recall]
     */
    private function precisionRecall(array $candEmb, array $refEmb): array
    {
        $m = \count($candEmb);
        $n = \count($refEmb);

        // Precompute vector norms
        $candNorm = array_map(fn(array $v) => $this->norm($v), $candEmb);
        $refNorm  = array_map(fn(array $v) => $this->norm($v), $refEmb);

        $precisionSum = 0.0;
        for ($i = 0; $i < $m; $i++) {
            $best = -INF;
            for ($j = 0; $j < $n; $j++) {
                $sim = $this->dot($candEmb[$i], $refEmb[$j]) / ($candNorm[$i] * $refNorm[$j] + 1e-8);
                if ($sim > $best) {
                    $best = $sim;
                }
            }
            $precisionSum += $best;
        }
        $precision = $precisionSum / $m;

        $recallSum = 0.0;
        for ($j = 0; $j < $n; $j++) {
            $best = -INF;
            for ($i = 0; $i < $m; $i++) {
                $sim = $this->dot($refEmb[$j], $candEmb[$i]) / ($refNorm[$j] * $candNorm[$i] + 1e-8);
                if ($sim > $best) {
                    $best = $sim;
                }
            }
            $recallSum += $best;
        }
        $recall = $recallSum / $n;

        return [$precision, $recall];
    }

    private function dot(array $a, array $b): float
    {
        $sum = 0.0;
        $len = \min(\count($a), \count($b));
        for ($i = 0; $i < $len; $i++) {
            $sum += $a[$i] * $b[$i];
        }
        return $sum;
    }

    private function norm(array $v): float
    {
        $sum = 0.0;
        foreach ($v as $x) {
            $sum += $x * $x;
        }
        return \sqrt($sum);
    }
}