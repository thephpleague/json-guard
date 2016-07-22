<?php

namespace League\JsonGuard\Test;

use League\JsonGuard\Comparator;

class ComparatorTest extends \PHPUnit_Framework_TestCase
{
    public function compareData()
    {
        return [
            ['2', '1', 1],
            ['1', '2', -1],
            ['1', '1', 0],
            ['1.000001', '1.000002', -1, 6],
            ['1.00000000001', '1.00000000002', -1, 20],
        ];
    }

    /**
     * @dataProvider compareData
     */
    public function testCompare($leftOperand, $rightOperand, $expected, $scale = null)
    {
        if ($scale) {
            Comparator::setScale($scale);
        } else {
            Comparator::setScale(Comparator::DEFAULT_SCALE);
        }
        
        $this->assertSame($expected, Comparator::compare($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider compareData
     * @runInSeparateProcess
     */
    public function testCompareWhenBcCompIsNotAvailable($leftOperand, $rightOperand, $expected)
    {
        require __DIR__ . '/disable_bccomp.php';
        $this->assertSame($expected, Comparator::compare($leftOperand, $rightOperand));
    }
}
