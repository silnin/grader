<?php

namespace App\Domain;

use Phpml\Math\Statistic\Correlation;

class Question
{
    public $scores = [];
    public float $pearsonCoefficient = 0;

    public function __construct(
        public string $questionId,
        public int $maxScore = 0
    ) {}

    public function getAverageScore(): float
    {
        return array_sum($this->scores) / count($this->scores);
    }

    public function calculatePearsonCoefficient(array $examScores): void
    {
        try {
            $this->pearsonCoefficient = Correlation::pearson($this->scores, $examScores);
        } catch (\DivisionByZeroError $e) {
            $this->pearsonCoefficient = 0;
        }
    }
}