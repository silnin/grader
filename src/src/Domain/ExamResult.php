<?php

namespace App\Domain;

use App\Domain\Grade;

class ExamResult
{
    public array $grades = [];
    public array $allScores = [];

    public function __construct(
        public array $questionResultsOfAllStudents,
    ) {
        $this->gradeStudentResults($questionResultsOfAllStudents);
        $this->calculatePearsonCoefficientsPerQuestion($questionResultsOfAllStudents);
    }

    public function gradeStudentResults($questionResultsOfAllStudents): void
    {    
        foreach ($questionResultsOfAllStudents as $questionResultsOfStudent) {
            $maxPossibleScore = 0;
            $totalScore = 0;    
            foreach ($questionResultsOfStudent as $questionResult) {
                $maxPossibleScore += $questionResult->question->maxScore;
                $totalScore += $questionResult->score;
            }
            $this->allScores[] = $totalScore;
            $this->grades[] = new Grade($questionResultsOfStudent[0]->studentId, $totalScore, $maxPossibleScore);
        }
    }
    
    private function calculatePearsonCoefficientsPerQuestion($questionResultsOfAllStudents): void
    {
        foreach ($questionResultsOfAllStudents[0] as $questionResult) {
            $questionResult->question->calculatePearsonCoefficient($this->allScores);
        }
    }
}