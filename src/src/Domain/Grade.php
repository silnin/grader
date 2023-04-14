<?php

namespace App\Domain;

class Grade
{
    public float $percentage = 0;

    public function __construct(
        public string $studentId,
        public float $score,
        public float $maxPossibleScore,
        public float $grade = 0
    ) {
        $this->percentage = $this->score / $this->maxPossibleScore;
        $this->calculateGrade();
    }

    /**
     * When the student scores 20% (or less) of the available points, he receives a 1.0
     * When a student scores 70% of the available points, he receives a 5.5 and passes the exam.
     * When a student scores 100% of the available points, he receives a 10.0
     * Otherwise calculate the grade gradually
     */
    private function calculateGrade(): void
    {

        if ($this->percentage <= 0.2) {
            $this->grade = 1.0;
            return;
        }

        if ($this->score == $this->maxPossibleScore) {
            $this->grade = 10.0;
            return;
        }

        // between 0.7 and 1.0 grows a line from 5.5 to 10.0
        if ($this->percentage > 0.7) {
            $this->grade = 5.5 + ($this->percentage - 0.7) * 4.5 / 0.3;
        }

        // between 0.2 and 0.7 grows a line from 1.0 to 5.5
        $this->grade = 1.0 + ($this->percentage - 0.2) * 4.5 / 0.5;
    }
}