<?php

declare(strict_types=1);

namespace Vjik\UnpackingVsForeachBench;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use SplFixedArray;

/**
 * @Iterations(10)
 * @Revs(1000)
 */
abstract class ArrayBench
{
    private array $data = [];
    private array $from = [];
    private SplFixedArray $fromSpl;
    private $to;

    public function setData(): void
    {
        $this->from = $this->generateArray($this->getCountElementsFrom());
        $this->fromSpl = SplFixedArray::fromArray($this->from, true);
        $this->data = $this->generateArray($this->getCountElementsTo());
        $this->to = $this->from;
    }

    abstract protected function getCountElementsFrom(): int;

    abstract protected function getCountElementsTo(): int;

    private function generateArray(int $count): array
    {
        $data = [];
        for ($i = 1; $i <= $count; $i++) {
            $data[] = (string)$i;
        }
        return $data;
    }

    /**
     * @BeforeMethods("setData")
     */
    public function benchUnpacking(): void
    {
        $this->to = $this->from;
        $this->to = [...$this->to, ...$this->data];
    }

    /**
     * @BeforeMethods("setData")
     */
    public function benchForeach(): void
    {
        $this->to = $this->from;
        foreach ($this->data as $column) {
            $this->to[] = $column;
        }
    }

    /**
     * @BeforeMethods("setData")
     */
    public function benchArrayMerge(): void
    {
        $this->to = $this->from;
        $this->to = \array_merge($this->to, $this->data);
    }

    /**
     * @BeforeMethods("setData")
     */
    public function benchFixedArray(): void
    {
        $this->to = clone $this->fromSpl;

        $oldSize = count($this->to);
        $this->to->setSize($oldSize + count($this->data));
        foreach ($this->data as $column) {
            $this->to[$oldSize++] = $column;
        }
    }
}
