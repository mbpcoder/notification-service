<?php

namespace App\Data\Enums;


use ReflectionEnum;

trait EnumOperationsTrait
{
    public static function isEnum(): bool
    {
        return (new ReflectionEnum(self::class))->isEnum();
    }

    public static function isBackedEnum(): bool
    {
        return (new ReflectionEnum(self::class))->isBacked();
    }

    public static function getBackingType(): \ReflectionType|null
    {
        return (new ReflectionEnum(self::class))->getBackingType();
    }

    public static function isImplementsInterface(string $interfaceName): bool
    {
        return (new ReflectionEnum(self::class))->implementsInterface($interfaceName);
    }

    public static function getList(): array
    {
        return self::isBackedEnum() ? array_column(self::cases(), 'value') : self::cases();
    }

    public function trans(): string
    {
        return trans('enums.' . self::isBackedEnum() ? $this->value : $this->name);
    }

    public static function isValidCase(string $case): bool
    {
        return !is_null(self::tryFrom($case));
    }
}
