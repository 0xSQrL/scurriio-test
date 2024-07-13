<?php

namespace Scurriio\Test;

class Benchmark{
    /**
     * @var int[]
     */
    private array $startTime;
    public int $elapsed;
    public float $seconds;

    public function start(){
        $this->startTime = hrtime(false);
    }

    public function end(){
        $endTime = hrtime(false);

        $elapsed = ($endTime[0] - $this->startTime[0]) * 1_000_000_000;
        $elapsed += $endTime[1] - $this->startTime[1];

        $this->seconds = $elapsed / 1_000_000_000.0;
    }
}