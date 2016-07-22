<?php

namespace League\JsonGuard;

class Comparator
{
    const DEFAULT_SCALE = 10;

    /**
     * The number of digits after the decimal place which will be used in the comparison.
     * The scale will only be used if bccomp is available.  Otherwise your system precision
     * will be used.
     *
     * @var int
     */
    private static $scale = self::DEFAULT_SCALE;

    /**
     * Compare two arbitrary precision numbers.  This method will use bccomp for the comparison,
     * or fall back to using standard comparison operators if ext-bcmath is not available.
     *
     * @param string|double|int $leftOperand
     * @param string|double|int $rightOperand
     *
     * @return int Returns 0 if the two operands are equal, 1 if the left_operand is larger than the right_operand,
     * -1 otherwise.
     */
    public static function compare($leftOperand, $rightOperand)
    {
        if (function_exists('bccomp')) {
            return bccomp($leftOperand, $rightOperand, self::$scale);
        }

        if ($leftOperand === $rightOperand) {
            return 0;
        }

        return $leftOperand > $rightOperand ? 1 : -1;
    }

    /**
     * Set the number of digits after the decimal place which will be used in the comparison.
     *
     * @param int $value
     */
    public static function setScale($value)
    {
        self::$scale = $value;
    }
}
