<?php

declare (strict_types=1);
namespace Rector\Core\Configuration;

use ReflectionClass;
use ReflectionMethod;
use RectorPrefix20220323\Symfony\Component\DependencyInjection\Definition;
use RectorPrefix20220323\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator;
final class ValueObjectInliner
{
    /**
     * @param object|object[] $object
     * @return mixed[]|\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator
     * @noRector \Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector
     */
    public static function inline($object)
    {
        if (\is_object($object)) {
            return self::inlineSingle($object);
        }
        return self::inlineMany($object);
    }
    /**
     * @param ReflectionClass<object> $reflectionClass
     * @return mixed[]
     * @param object $object
     */
    public static function resolveArgumentValues(\ReflectionClass $reflectionClass, $object) : array
    {
        $argumentValues = [];
        $constructorReflectionMethod = $reflectionClass->getConstructor();
        if (!$constructorReflectionMethod instanceof \ReflectionMethod) {
            // value object without constructor
            return [];
        }
        foreach ($constructorReflectionMethod->getParameters() as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();
            $propertyReflection = $reflectionClass->getProperty($parameterName);
            $propertyReflection->setAccessible(\true);
            $resolvedValue = $propertyReflection->getValue($object);
            $resolvedValue = self::inlineNestedArrayObjects($resolvedValue);
            $argumentValues[] = \is_object($resolvedValue) ? self::inlineSingle($resolvedValue) : $resolvedValue;
        }
        return $argumentValues;
    }
    /**
     * @param object[] $objects
     * @return InlineServiceConfigurator[]
     */
    private static function inlineMany(array $objects) : array
    {
        $inlineServices = [];
        foreach ($objects as $object) {
            $inlineServices[] = self::inlineSingle($object);
        }
        return $inlineServices;
    }
    /**
     * @param object $object
     */
    private static function inlineSingle($object) : \RectorPrefix20220323\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator
    {
        $reflectionClass = new \ReflectionClass($object);
        $className = $reflectionClass->getName();
        $argumentValues = self::resolveArgumentValues($reflectionClass, $object);
        $inlineServiceConfigurator = new \RectorPrefix20220323\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator(new \RectorPrefix20220323\Symfony\Component\DependencyInjection\Definition($className));
        if ($argumentValues !== []) {
            $inlineServiceConfigurator->args($argumentValues);
        }
        return $inlineServiceConfigurator;
    }
    /**
     * @param mixed|mixed[] $resolvedValue
     * @return mixed|mixed[]
     */
    private static function inlineNestedArrayObjects($resolvedValue)
    {
        if (\is_array($resolvedValue)) {
            foreach ($resolvedValue as $key => $value) {
                if (\is_object($value)) {
                    $resolvedValue[$key] = self::inline($value);
                }
            }
        }
        return $resolvedValue;
    }
}
