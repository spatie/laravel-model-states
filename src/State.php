<?php

namespace Spatie\State;

use ReflectionClass;
use Spatie\State\Exceptions\InvalidState;

abstract class State
{
    /**
     * Static cache for generated state maps.
     *
     * @var array
     *
     * @see State::resolveStateMapping
     */
    private static $generatedMapping = [];

    /**
     * Create a state object based on a value (classname or name),
     * and optionally provide its constructor arguments.
     *
     * @param string $name
     * @param mixed ...$args
     *
     * @return \Spatie\State\State
     */
    public static function find(string $name, ...$args): State
    {
        $stateClass = static::resolveStateClass($name);

        if (! is_subclass_of($stateClass, static::class)) {
            throw InvalidState::make($name, static::class);
        }

        return new $stateClass(...$args);
    }

    /**
     * The value that will be saved in the database.
     *
     * @return string
     */
    public static function getMorphClass(): string
    {
        return static::resolveStateName(static::class);
    }

    /**
     * Resolve the state class based on a value, for example a stored value in the database.
     *
     * @param string $name
     *
     * @return string
     */
    public static function resolveStateClass(string $name): string
    {
        foreach (static::resolveStateMapping() as $stateClass) {
            if (! class_exists($stateClass)) {
                continue;
            }

            if (($stateClass::$name ?? null) === $name) {
                return $stateClass;
            }
        }

        return $name;
    }

    /**
     * Resolve the name of the state, which is the value that will be saved in the database.
     *
     * Possible names are:
     *
     *    - The classname, is no explicit name is provided
     *    - A name provided in the state class as a public static property:
     *      `public static $name = 'dummy'`
     *
     * @param $state
     *
     * @return string|null
     */
    public static function resolveStateName($state): ?string
    {
        if ($state === null) {
            return null;
        }

        if ($state instanceof State) {
            $stateClass = get_class($state);
        } else {
            $stateClass = static::resolveStateClass($state);
        }

        if (class_exists($stateClass) && isset($stateClass::$name)) {
            return $stateClass::$name;
        }

        return $stateClass;
    }

    /**
     * Determine if the current state is one of an arbitrary number of other states.
     * This can be either a classname or a name.
     *
     * @param string ...$stateClasses
     *
     * @return bool
     */
    public function isOneOf(string ...$statesNames): bool
    {
        foreach ($statesNames as $statesName) {
            if ($this->equals($statesName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the current state equals another.
     * This can be either a classname or a name.
     *
     * @param \Spatie\State\State $stateName
     *
     * @return bool
     */
    public function equals(string $stateName): bool
    {
        $className = self::resolveStateClass($stateName);

        return $className === get_class($this);
    }

    public function __toString(): string
    {
        return static::getMorphClass();
    }

    /**
     * This method is used to find all available implementations of a given abstract state class.
     * Finding all implementations can be done in two ways:
     *
     *    - The developer can define his own mapping directly in abstract state classes
     *      via the `protected $states = []` property
     *    - If no specific mapping was provided, the same directory where the abstract state class lives
     *      is scanned, and all concrete state classes extending the abstract state class will be provided.
     *
     * @return array
     */
    private static function resolveStateMapping(): array
    {
        if (isset(static::$states)) {
            return static::$states;
        }

        if (isset(self::$generatedMapping[static::class])) {
            return self::$generatedMapping[static::class];
        }

        $reflection = new ReflectionClass(static::class);

        ['dirname' => $directory] = pathinfo($reflection->getFileName());

        $files = scandir($directory);

        unset($files[0], $files[1]);

        $namespace = $reflection->getNamespaceName();

        $resolvedStates = [];

        foreach ($files as $file) {
            ['filename' => $className] = pathinfo($file);

            $stateClass = $namespace . '\\' . $className;

            if (! is_subclass_of($stateClass, static::class)) {
                continue;
            }

            $resolvedStates[] = $stateClass;
        }

        self::$generatedMapping[static::class] = $resolvedStates;

        return self::$generatedMapping[static::class];
    }
}
