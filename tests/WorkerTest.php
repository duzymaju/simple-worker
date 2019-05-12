<?php

use PHPUnit\Framework\TestCase;
use SimpleWorker\PeriodicalWorker;

final class WorkerTest extends TestCase
{
    /** Test executing as many parts as possible within time period */
    public function testExecutingAsManyPartsAsPossibleWithinTimePeriod()
    {
        $worker = new TestWorker(3, 0.5, 5);
        $worker->execute();
        $this->assertEquals(2, $worker->getPartNo());
    }

    /** Test executing one part only no matter how long time period is */
    public function testExecutingOnePartOnlyNoMatterHowLongTimePeriodIs()
    {
        $worker = new TestWorker(5, 0, 1);
        $worker->execute();
        $this->assertEquals(1, $worker->getPartNo());
    }
}

class TestWorker extends PeriodicalWorker
{
    private $partsToExecute;

    public function __construct($period, $durationOfBreak, $partsToExecute)
    {
        parent::__construct($period, $durationOfBreak);
        $this->partsToExecute = $partsToExecute;
    }

    public function getPartNo()
    {
        return parent::getPartNo();
    }

    protected function executeNextPart()
    {
        sleep(1);

        return $this->getPartNo() < $this->partsToExecute;
    }
}
