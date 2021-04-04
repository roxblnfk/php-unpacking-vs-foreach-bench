<?php

declare(strict_types=1);

namespace Vjik\UnpackingVsForeachBench;

/**
 * @Revs(100)
 */
final class To500Add500Bench extends ArrayBench
{
    protected function getCountElementsFrom(): int
    {
        return 5000;
    }

    protected function getCountElementsTo(): int
    {
        return 5000;
    }
}
