<?php
/**
 * PHP type helper package
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.0.0
 * @since 1.4.0
 */

namespace darkfriend\devhelpers;


class TypeHelper
{
    /**
     * Return value to strict type
     * @param mixed $value
     * @return bool|int|string|null
     */
    public static function toStrictType($value)
    {
        if ($value === null) {
            return null;
        }

        if (\is_numeric($value)) {
            $num = \filter_var($value, FILTER_VALIDATE_INT | FILTER_VALIDATE_FLOAT);
            if ($num === false) {
                return (string)$value;
            }

            if ((int)$num == (float)$num) {
                return (int)$num;
            }

            return $num;
        }

        if ((bool)$value == $value) {
            switch ($value) {
                case 'true':
                    $value = true;
                    break;
                case 'false':
                    $value = false;
                    break;
            }
            return $value;
        }

        return (string)$value;
    }
}