<?php

/*
 * This file is part of exsyst/mini-enum.
 *
 * Copyright (C) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\MiniEnum;

abstract class FlagsEnum extends Enum
{
    public static function getValue(string $name): ?int
    {
        $name = \array_map('trim', \explode(',', $name));
        $values = static::getValues();
        $value = 0;
        foreach ($name as $part) {
            if (!isset($values[$part])) {
                return null;
            }
            $value |= $values[$part];
        }

        return $value;
    }

    public static function getName(int $value): ?string
    {
        $name = self::getNames($value);

        return 0 === \count($name) ? null : \implode(', ', $name);
    }

    /**
     * Return all names inside an array.
     *
     * @param int $value
     *
     * @return array
     */
    public static function getNames(int $value): array
    {
        $name = \array_search($value, static::getValues());
        if (false !== $name) {
            return [$name];
        }
        $values = static::getSingleBitValues();
        $name = [];
        foreach ($values as $k => $v) {
            if (0 != ($value & $v)) {
                $name[] = $k;
                $value &= ~$v;
            }
        }

        return 0 != $value ? [] : $name;
    }

    public static function getSingleBitValues(): array
    {
        static $values = [];
        if (null === $values[static::class]) {
            $values[static::class] = \array_filter(static::getValues(), function ($value) {
                // true for single-1 values, false for 0 or multiple-1 values
                return 0 != $value && 0 == ($value & ($value - 1));
            });
        }

        return $values[static::class];
    }
}
