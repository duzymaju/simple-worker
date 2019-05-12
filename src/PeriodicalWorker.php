<?php

namespace SimpleWorker;

/**
 * Periodical worker
 */
abstract class PeriodicalWorker implements WorkerInterface
{
    /** @var int */
    private $durationAvg = 0;

    /** @var int */
    private $durationOfBreak;

    /** @var float|null */
    private $iterationStartTime;

    /** @var int */
    private $partNo = 0;

    /** @var int */
    private $period;

    /**
     * Construct
     *
     * @param int $period          period
     * @param int $durationOfBreak duration of break
     */
    public function __construct($period, $durationOfBreak = 5)
    {
        $this->period = $period;
        $this->durationOfBreak = $durationOfBreak;
    }

    /**
     * Execute
     */
    public function execute()
    {
        while ($this->canIterate()) {
            $this->partNo++;
            $executeAnotherPart = $this->executeNextPart();
            if ($executeAnotherPart !== true) {
                break;
            }
        }
    }

    /**
     * Execute next part
     *
     * @return bool
     */
    protected abstract function executeNextPart();

    /**
     * Get part no
     *
     * @return int
     */
    protected function getPartNo()
    {
        return $this->partNo;
    }

    /**
     * Can iterate
     *
     * @return bool
     */
    private function canIterate()
    {
        if ($this->iterationStartTime) {
            $nextPartNo = $this->partNo + 1;
            $durationTime = microtime(true) - $this->iterationStartTime;
            $this->durationAvg = ($this->durationAvg * $this->partNo + $durationTime) / $nextPartNo;
            $probableDurationToTheEndOfNextIteration = $this->durationAvg * ($nextPartNo + 1);
            if ($probableDurationToTheEndOfNextIteration + $this->durationOfBreak >= $this->period) {
                return false;
            }
        }
        $this->iterationStartTime = microtime(true);

        return true;
    }
}
