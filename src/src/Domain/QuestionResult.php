<?php

namespace App\Domain;

class QuestionResult
{
    public function __construct(
        public Question $question,
        public float $score,
        public string $studentId
    ) {}
}