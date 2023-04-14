<?php

namespace App\Domain;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Psr\Log\LoggerInterface;
use App\Domain\QuestionResult;
use App\Domain\Question;

class TestResultDigester
{
    const HEADER_ROW_QUESTION_NUMNER = 0;
    const HEADER_ROW_MAX_SCORE = 1;
    const HEADER_COLUMN_STUDENT_ID = 0;

    public function __construct(
        private LoggerInterface $logger,
        private ExcelReaderFactory $excelReaderFactory
    ) {}

    private function getWorkSheetData(string $filePath): array 
    {
        $excelReader = $this->excelReaderFactory->createForPath($filePath);
        $spreadsheet = $excelReader->load($filePath);
        return $spreadsheet->getActiveSheet()->toArray();
    }

    public function digest(string $filePath): ExamResult
    {
        $worksheet = $this->getWorkSheetData($filePath);

        if (!is_array($worksheet) || count($worksheet) < 1) {
            throw new \Exception('The test result spreadsheet could not be loaded or is empty');
        }

        $this->logger->info(
            sprintf(
                'Read test result spreadsheet, containing %s test results consisting of %s questions',
                count($worksheet),
                count($worksheet[0])
            )
        );

        // read in the data from the worksheet in representable objects
        $scoresOfAllStudents = $this->digestWorkSheetData($worksheet);

        return new ExamResult($scoresOfAllStudents);
    }

    /**
     * @param array $worksheet - multidimensional array where columns are questions and rows are student answers
     * @return Question[] - array of Question objects
     * 
     * NOTE: the first row of the worksheet is a header showing the question numbers
     *       the second row of the worksheet is a header showing the maximum possible question score
     *       the first column of the worksheet is a header showing the student ID
     */
    private function digestWorkSheetData(array $worksheet): array
    {
        $questions = [];
        $scoresOfAllStudents = [];

        foreach ($worksheet as $rowNumber => $row) {

            if ($rowNumber === TestResultDigester::HEADER_ROW_QUESTION_NUMNER) {
                // create questions
                $questions = $this->createQuestions($row);
                continue;
            }
            if ($rowNumber === TestResultDigester::HEADER_ROW_MAX_SCORE) {
                // add max score to created questions
                $this->addMaxScoreToQuestions($row, $questions);
                continue;
            }

            // digest student answers for each question
            $scoresOfAllStudents[] = $this->digestScoresOfStudent($row, $questions);
        }

        return $scoresOfAllStudents;
    }

    private function createQuestions(array $questionNumbers): array
    {
        $questions = [];

        foreach ($questionNumbers as $column => $questionNumber) {
            // the first column is a header, so we skip it
            if ($column === 0) {
                continue;
            }
            $questions[] = new Question($questionNumber);
        }

        return $questions;
    }

    private function addMaxScoreToQuestions(
        array $questionMaxScores, 
        array $questions
    ): void
    {
        foreach ($questionMaxScores as $column => $questionMaxScore) {
            // the first column is a header, so we skip it
            if ($column === 0) {
                continue;
            }

            // -1 because the first column is a header so the number of questions is 1 less than the number of columns
            $questions[$column - 1]->maxScore = intval($questionMaxScore);
        }
    }

    /**
     * @param array $scoresOfStudent - array where the keys are the question numbers and the values are the corresponding student scores
     * @param array $questions - array of Question objects
     * @return QuestionResult[]
     */
    private function digestScoresOfStudent(
        array $scoresOfThisStudent, 
        array $questions
    ): array
    {
        $studentId = $scoresOfThisStudent[TestResultDigester::HEADER_COLUMN_STUDENT_ID];
        $scores = [];

        foreach ($scoresOfThisStudent as $column => $studentScore) {
            if ($column === TestResultDigester::HEADER_COLUMN_STUDENT_ID) continue;

            $sanitizedStudentScore = $this->convertCommaSeperatedStringToFloat($studentScore);
            // -1 because the first column is a header so the number of questions is 1 less than the number of columns
            $associatedQuestion = $questions[$column -1];
            $associatedQuestion->scores[] = $sanitizedStudentScore;
            $scores[] = new QuestionResult($associatedQuestion, $sanitizedStudentScore, $studentId);
        }

        return $scores;
    }

    private function convertCommaSeperatedStringToFloat(string $string) : float
    {
        return floatval(str_replace(',', '.', $string));
    }
}