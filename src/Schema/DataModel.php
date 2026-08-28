<?php

declare(strict_types=1);

namespace Kwork\Schema;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * Lightweight DTO base similar to Pydantic models used in the Python client.
 *
 * @phpstan-type DataArray array<string, mixed>
 */
abstract class DataModel
{
    /**
     * @param DataArray $data
     */
    public static function fromArray(array $data): static
    {
        $instance = new static();
        $instance->hydrate($data);

        return $instance;
    }

    /**
     * @param DataArray $data
     */
    protected function hydrate(array $data): void
    {
        $aliases = $this->aliases();
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $keys = [$name, $aliases[$name] ?? null];
            $value = null;
            $found = false;

            foreach ($keys as $key) {
                if ($key !== null && array_key_exists($key, $data)) {
                    $value = $data[$key];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                continue;
            }

            $type = $property->getType();
            if ($value !== null && $type instanceof ReflectionNamedType) {
                if ($type->isBuiltin()) {
                    $builtin = $type->getName();
                    if ($builtin === 'string' && !is_string($value)) {
                        continue;
                    }
                    if ($builtin === 'int' && !is_int($value)) {
                        continue;
                    }
                    if ($builtin === 'bool' && !is_bool($value)) {
                        continue;
                    }
                    if ($builtin === 'float' && !is_float($value) && !is_int($value)) {
                        continue;
                    }
                } else {
                    $className = $type->getName();
                    if (is_subclass_of($className, DataModel::class)) {
                        if (!is_array($value)) {
                            continue;
                        }
                        $value = $className::fromArray($value);
                    } elseif (enum_exists($className) && is_string($value)) {
                        $value = $className::from($value);
                    }
                }
            }

            if ($value !== null && $type instanceof ReflectionNamedType && $type->getName() === 'array') {
                $doc = $property->getDocComment() ?: '';
                if (preg_match('/@var\s+list<([^\s>|]+)>/', $doc, $matches) === 1) {
                    $itemClass = $this->resolveClassName($matches[1], $property->getDeclaringClass()->getName());
                    if (is_subclass_of($itemClass, DataModel::class) && is_array($value)) {
                        $value = array_map(
                            static fn (mixed $item): mixed => is_array($item) ? $itemClass::fromArray($item) : $item,
                            $value,
                        );
                    }
                }
            }

            $this->{$name} = $value;
        }
    }

    /**
     * @return array<string, string>
     */
    protected function aliases(): array
    {
        return [];
    }

    private function resolveClassName(string $className, string $declaringClass): string
    {
        if ($className !== '' && $className[0] === '\\') {
            return $className;
        }

        if (class_exists($className) || enum_exists($className)) {
            return $className;
        }

        $namespace = (new ReflectionClass($declaringClass))->getNamespaceName();
        $candidate = $namespace . '\\' . $className;

        if (class_exists($candidate) || enum_exists($candidate)) {
            return $candidate;
        }

        return $className;
    }

    /**
     * @return DataArray
     */
    public function toArray(): array
    {
        $result = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $value = $this->{$name};

            if ($value instanceof DataModel) {
                $value = $value->toArray();
            } elseif (is_array($value)) {
                $value = array_map(
                    static fn (mixed $item): mixed => $item instanceof DataModel ? $item->toArray() : $item,
                    $value,
                );
            } elseif ($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * @return DataArray
     */
    public function toArrayByAlias(): array
    {
        $result = [];
        $reflection = new ReflectionClass($this);
        $aliases = $this->aliases();

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $value = $this->{$name};
            $key = $aliases[$name] ?? $name;

            if ($value instanceof DataModel) {
                $value = $value->toArrayByAlias();
            } elseif (is_array($value)) {
                $value = array_map(
                    static fn (mixed $item): mixed => $item instanceof DataModel ? $item->toArrayByAlias() : $item,
                    $value,
                );
            } elseif ($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
