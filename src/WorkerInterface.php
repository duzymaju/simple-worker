<?php

namespace SimpleWorker;

/**
 * Worker interface
 */
interface WorkerInterface
{
    /**
     * Execute
     */
    public function execute();
}
