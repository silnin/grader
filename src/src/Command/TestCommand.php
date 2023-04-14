<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
Use \App\Domain\TestResultDigester;

#[AsCommand(
    name: 'app:test',
    description: 'Grading student tests',
)]
class TestCommand extends Command
{
    public function __construct(
        private LoggerInterface $logger,
        private TestResultDigester $digester
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        //@todo add argument for file path
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $examResult = $this->digester->digest('/app/sample/sample.xlsx');

        foreach ($examResult->grades as $grade) {
            $this->logger->info(
                sprintf(
                    '%s scores %s of %s (%s%%) and gets a grade of %s and therefor %s',
                    $grade->studentId,
                    $grade->score,
                    $grade->maxPossibleScore,
                    round(($grade->percentage * 100),2),
                    $grade->grade,
                    ($grade->grade >= 5.5) ? 'passes!' : 'fails :('
                )
            );
        }

        foreach ($examResult->questionResultsOfAllStudents[0] as $questionResult) {
            $this->logger->info(
                sprintf(
                    '%s has a pearson coefficient of %s and an average score of %s (out of %s)',
                    $questionResult->question->questionId,
                    round($questionResult->question->pearsonCoefficient, 2),
                    round($questionResult->question->getAverageScore(),2),
                    $questionResult->question->maxScore
                )
            );
        }
        
        return Command::SUCCESS;
    }
}
