<?php

namespace ShahBurhan\OWA;

use ShahBurhan\OWA\Exceptions\ActionNotFoundException;

abstract class Action
{
    /**
     * Available objects for dynamic resolve
     *
     * @var array
     */
    public static $objects   = [];

    /**
     * Default namespace for all objects
     *
     * @var string
     */
    public static $namespace = null;

    /**
     * Default directory for all objects
     *
     * @var string
     */
    public static $directory = null;

    /**
     * Abstract method to be implemented by child Action
     * which is later called to process all actions
     *
     * @param array ...$args
     * @return Collection|Model|array
     */
    abstract public function take(...$args);

    /**
     * Dynamically resolve an Object and associated Action
     *
     * @param string $name
     * @param array $arguments
     * @return Collection|Model|array
     */
    public static function __callStatic($name, $arguments)
    {
        static::$namespace = config('owa.namespace');
        static::$directory = config('owa.directory');

        static::setAvailableObjects();
        return static::isValidAction(static::getActionClass($name), $arguments);
    }

    /**
     * Set available objects for dynamic resolution
     *
     * @return void
     */
    public static function setAvailableObjects()
    {
        static::$objects = array_map(function ($element) {
            return str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $element);
        }, glob(dirname(__DIR__, 4) . '/' . static::$directory . '/*', GLOB_ONLYDIR));
    }

    /**
     * Validate if an action class exists
     *
     * @param string $class
     * @param array $arguments
     * @return boolean
     */
    public static function isValidAction($class, $arguments)
    {
        $hasActionSuffix = false;

        if (class_exists($class) || $hasActionSuffix = class_exists($class . 'Action')) {
            $class = ($hasActionSuffix) ? $class . 'Action' : $class;

            return static::action(new $class, $arguments);
        } else {
            throw ActionNotFoundException::missingClass($class);
        }
    }

    /**
     * Call the take method for a valid action object and pass along the arguments
     *
     * @param Action $action
     * @param array $arguments
     * @return Collection|Model|array
     */
    public static function action(Action $action, $arguments)
    {
        return $action->take($arguments);
    }

    /**
     * Get the complete namespace for an action class
     *
     * @param string $name
     * @return string
     */
    public static function getActionClass($name)
    {
        $class = static::$namespace . "\{object}\Actions\{action}";

        list($object, $action) = static::resolveObjectAction($name);

        return str_replace(['{object}', '{action}'], [$object, $action], $class);
    }

    /**
     * Resolve possible object & action for dynamic calls
     *
     * @param array $components
     * @return array
     */
    public static function resolveObjectAction($name)
    {
        $components = preg_split('/(?=[A-Z])/', $name);

        $componentCount = count($components);

        if ($componentCount < 3) {
            throw ActionNotFoundException::invalidNameSyntax(implode('', $components));
        } elseif ($componentCount > 3) {
            array_shift($components);
            //Find possible matches
            $possibleMatches = static::addPossibilities($components, $componentCount - 2);

            return static::resolvePossibilities($possibleMatches);
        } else {
            return [$components[1], $components[2]];
        }
    }

    /**
     * Find a match from possible resolves
     *
     * @param array $possibleMatches
     * @return array
     */
    public static function resolvePossibilities($possibleMatches)
    {
        $ObjectAction = [];

        foreach ($possibleMatches[0] as $k => $possibleObject) {
            $ObjectAction = [$possibleObject, $possibleMatches[1][$k]];

            $class = static::$namespace . '\\' . $possibleObject . "\Actions\\" . $possibleMatches[1][$k];

            if (class_exists($class) || class_exists($class . 'Action')) {
                return $ObjectAction;
            }
        }

        return $ObjectAction;
    }

    /**
     * Find possible resolutions for dynamic action call
     *
     * @param array $components
     * @param int $limit
     * @return array
     */
    public static function addPossibilities($components, $limit)
    {
        $i               = 0;
        $matchString     = implode('_', array_values(static::$objects));
        $possibleObjects = [];
        $possibleActions = [];

        while ($i < $limit) {
            $possibleObject  = implode('', $possibleObjects) . $components[$i];

            if (strpos($matchString, $possibleObject) > -1) {
                $possibleObjects[] = $possibleObject;
                $possibleActions[] = implode('', array_slice($components, $i + 1));
            }
            $i++;
        }

        return [$possibleObjects, $possibleActions];
    }
}
