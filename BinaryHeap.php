<?php


class BinaryHeap
{
    private $list;

    public function __construct(array $src)
    {
        $this->list = $src;
        for ($i = count($this->list) / 2; $i >= 0; $i--) {
            $this->heapify($i);
        }
    }

    public function add($value)
    {
        $this->list[] = $value;
        $i = count($this->list);
        $parent = ($i - 1) / 2;
        while ($i > 0 && $this->list[$parent] < $this->list[$i]) {
            $tmp = $this->list[$i];
            $this->list[$i] = $this->list[$parent];
            $this->list[$parent] = $tmp;
            $i = $parent;
            $parent = ($i - 1) / 2;
        }
    }

    public function heapify($i)
    {
        while (true) {
            $leftChild = 2 * $i + 1;
            $rightChild = 2 * $i + 2;
            $largest = $i;
            $heapSize = count($this->list);
            if ($leftChild < $heapSize && $this->list[$leftChild] > $this->list[$largest]) {
                $largest = $leftChild;
            }
            if ($rightChild < $heapSize && $this->list[$rightChild] > $this->list[$largest]) {
                $largest = $rightChild;
            }
            if ($largest == $i) {
                break;
            }
            $tmp = $this->list[$i];
            $this->list[$i] = $this->list[$largest];
            $this->list[$largest] = $tmp;
            $i = $largest;
        }
    }

    public function getMax()
    {
        $max = $this->list[0];
        $this->list[0] = $this->list[count($this->list) - 1];
        unset($this->list[count($this->list) - 1]);
        $this->heapify(0);
        return $max;
    }
}