<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use ReflectionClass;

class StateCaster implements CastsAttributes
{
    private static array $stateMapping = [];

    private string $baseStateClass;

    public function __construct(string $baseStateClass)
    {
        $this->baseStateClass = $baseStateClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        $mapping = $this->getStateMapping();

        $stateClassName = $mapping[$value];

        /** @var \Spatie\ModelStates\StateConfig $stateConfig */
        $stateConfig = $model->getStateConfig($key);

        return new $stateClassName(
            $model,
            $stateConfig
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param \Spatie\ModelStates\State|string $value
     * @param array $attributes
     *
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_subclass_of($value, $this->baseStateClass)) {
            $mapping = $this->getStateMapping();

            $value = $mapping[$value];
        }

        return $value::getMorphClass();
    }

    private function getStateMapping(): array
    {
        if (! isset(self::$stateMapping[$this->baseStateClass])) {
            self::$stateMapping[$this->baseStateClass] = $this->resolveStateMapping();
        }

        return self::$stateMapping[$this->baseStateClass];
    }

    private function resolveStateMapping(): array
    {
        $reflection = new ReflectionClass($this->baseStateClass);

        ['dirname' => $directory] = pathinfo($reflection->getFileName());

        $files = scandir($directory);

        unset($files[0], $files[1]);

        $namespace = $reflection->getNamespaceName();

        $resolvedStates = [];

        foreach ($files as $file) {
            ['filename' => $className] = pathinfo($file);

            /** @var \Spatie\ModelStates\State|mixed $stateClass */
            $stateClass = $namespace . '\\' . $className;

            if (! is_subclass_of($stateClass, $this->baseStateClass)) {
                continue;
            }

            $resolvedStates[$stateClass::getMorphClass()] = $stateClass;
        }

        return $resolvedStates;
    }
}
