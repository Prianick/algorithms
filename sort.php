<?php

require_once "BinaryHeap.php";

$isQuite = false;
if (isset($argv[1])) {
    $isQuite = $argv[1] == 'quite';
}

function isSortCorrect($data)
{
    for ($i = 0; $i + 1 < count($data); $i++) {
        if ($data[$i] > $data[$i + 1]) {
            return false;
        }
    }
    return true;
}

function swap(&$data, $i1, $i2)
{
    $tmp = $data[$i2];
    $data[$i2] = $data[$i1];
    $data[$i1] = $tmp;
}

function getData()
{
    $filePath = 'data.txt';
    $fid = fopen($filePath, 'r');
    $data = [];
    while ($line = fgets($fid)) {
        $data[] = (int)trim($line);
    }
    return $data;
}

$bubbleSort = function (array $data) {
    for ($i = 0; $i + 1 < count($data); $i++) {
        for ($j = 0; $j + 1 < count($data) - $i; $j++) {
            if ($data[$j] > $data[$j + 1]) {
                swap($data, $j, $j + 1);
            }
        }
    }

    return $data;
};

$shakerSort = function (array $data) {
    $left = 0;
    $right = count($data) - 1;
    while ($left <= $right) {
        for ($i = $right; $i > $left; $i--) {
            if ($data[$i - 1] > $data[$i]) {
                swap($data, $i - 1, $i);
            }
        }
        ++$left;
        for ($i = $left; $i < $right; $i++) {
            if ($data[$i] > $data[$i + 1]) {
                swap($data, $i, $i + 1);
            }
        }
        --$right;
    }
    return $data;
};

$checkAlgorithm = function ($func) use ($isQuite) {
    $startTime = microtime(true);
    $sortedData = $func(getData());
    $endTime = microtime(true);
    if (!$isQuite) {
        print_r($sortedData);
    }

    $time = $endTime - $startTime;
    echo "time: $time \n";
    echo "is sort correcting: " . (int)isSortCorrect($sortedData) . "\n";
};

$combSort = function (array $data) {
    $k = 1.247;
    $step = count($data) - 1;
    while ($step >= 1) {
        for ($i = 0; $i + $step < count($data); $i++) {
            if ($data[$i] > $data[$i + $step]) {
                swap($data, $i, $i + $step);
            }
        }
        $step /= $k;
    }

    for ($i = 0; $i + 1 < count($data); $i++) {
        for ($j = 0; $j + 1 < count($data) - $i; $j++) {
            if ($data[$j] > $data[$j + 1]) {
                swap($data, $j, $j + 1);
            }
        }
    }

    return $data;
};

$insertionSort = function (array $data) {
    for ($i = 0; $i < count($data); $i++) {
        $x = $data[$i];
        $j = $i;
        while ($j > 0 && $data[$j - 1] > $x) {
            $data[$j] = $data[$j - 1];
            $j--;
        }
        $data[$j] = $x;
    }
    return $data;
};

$selectionSort = function (array $data) {
    for ($i = 0; $i < count($data) - 1; $i++) {
        $minI = $i;
        for ($j = $i + 1; $j < count($data); $j++) {
            if ($data[$j] < $data[$minI]) {
                $minI = $j;
            }
        }
        if ($minI != $i) {
            swap($data, $i, $minI);
        }
    }
    return $data;
};

// there is a problem with memory
$quickSort1 = function ($data) {

    function _quickSort($data)
    {
        if (count($data) <= 1) {
            return $data;
        }

        $pivot = $data[0];
        $left = [];
        $right = [];
        for ($i = 0; $i < count($data) - 1; $i++) {
            if ($data[$i] < $pivot) {
                $left[] = $data[$i];
            } else {
                $right[] = $data[$i];
            }
        }
        return array_merge(_quickSort($left), [$pivot], _quickSort($right));
    }

    return _quickSort($data);
};

$quickSort2 = function ($data) {
    function partition(&$data, $li, $ri)
    {
        $pivot = $data[(int)($ri + $li) / 2];
        while ($li <= $ri) {
            while ($data[$li] < $pivot) {
                $li++;
            }
            while ($data[$ri] > $pivot) {
                $ri--;
            }
            if ($li <= $ri) {
                swap($data, $li, $ri);
                $li++;
                $ri--;
            }
        }
        return $li;
    }

    function quickSort(&$data, $li, $ri)
    {
        $index = partition($data, $li, $ri);
        if ($li < $index - 1) {
            quickSort($data, $li, $index - 1);
        }
        if ($index < $ri) {
            quickSort($data, $index, $ri);
        }
    }

    quickSort($data, 0, count($data) - 1);

    return $data;
};

$mergeSort = function ($data) {

    function mergeSortImp(&$data, &$buffer, $l, $r)
    {
        if ($l < $r) {
            $m = (int)(($l + $r) / 2);
            mergeSortImp($data, $buffer, $l, $m);
            mergeSortImp($data, $buffer, $m + 1, $r);

            $k = $l;
            for ($i = $l, $j = $m + 1; $i <= $m || $j <= $r;) {
                if ($j > $r || ($i <= $m && $data[$i] < $data[$j])) {
                    $buffer[$k] = $data[$i];
                    $i++;
                } else {
                    $buffer[$k] = $data[$j];
                    $j++;
                }
                $k++;
            }
            for ($i = $l; $i <= $r; $i++) {
                $data[$i] = $buffer[$i];
            }
        }
    }

    $buffer = [];
    mergeSortImp($data, $buffer, 0, count($data) - 1);

    return $data;
};

$heapSort = function ($data) {
    $heap = new BinaryHeap($data);
    for ($i = count($data) - 1; $i >= 0; $i--) {
        $data[$i] = $heap->getMax();
    }
    return $data;
};

$phpSort = function (array $data) {
    sort($data, SORT_NUMERIC);
    return $data;
};

echo "\nbubble sort: \n";
$checkAlgorithm($bubbleSort);
echo "\nshaker sort: \n";
$checkAlgorithm($shakerSort);
echo "\ncomb sort: \n";
$checkAlgorithm($combSort);
echo "\ninsertion sort: \n";
$checkAlgorithm($insertionSort);
echo "\nselection sort: \n";
$checkAlgorithm($selectionSort);
echo "\nquick sort: \n";
$checkAlgorithm($quickSort2);
echo "\nmerge sort: \n";
$checkAlgorithm($mergeSort);
echo "\nheap sort: \n";
$checkAlgorithm($heapSort);
echo "\nphp sort: \n";
$checkAlgorithm($phpSort);

