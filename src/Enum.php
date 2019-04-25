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

abstract class Enum
{
    private function __construct()
    {
    }

    public static function isValid(int $value): bool
    {
        return null !== static::getName($value);
    }

    public static function getValue(string $name): ?int
    {
        if (\is_numeric($name)) {
            return (int) $name;
        }

        $values = static::getValues();
        if (!isset($values[$name])) {
            return null;
        }

        return $values[$name];
    }

    public static function getName(int $value): ?string
    {
        $name = \array_search($value, static::getValues());
        if (false === $name) {
            return null;
        }

        return $name;
    }

    public static function getValues(): array
    {
        static $values = null;
        if (null === $values) {
            $clazz = new \ReflectionClass(static::class);
            $values = $clazz->getConstants();
        }

        return $values;
    }
}
