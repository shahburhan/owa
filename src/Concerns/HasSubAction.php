<?php

namespace ShahBurhan\OWA\Concerns;

/**
 * Example map 
 * Case 1.
 * protected $actionMap = [
 *      0 => "methodNameForNoArgAction",
 *      1 => "methodNameForActionWithOneArg",
 * ]
 * Case 2.
 * protected $actionMap = [
 *      0 => [
 *              "methodNameForNoArgAction" => [
 *                              1 => 'segmentOne'
 *                          ], 
 *              "AnotherMethodNameForNoArgAction" => [
 *                              1 => 'segmentOneAnother'
 *                          ]
 *            ],
 *      1 => "methodNameForActionWithOneArg",
 * ]
 * 
 */
trait HasSubAction
{
    /**
     * Call a sub action based on the argument map
     *
     * @param array $args
     * @return void
     */
    public function subAction(array $args)
    {
        $args = array_shift($args);

        return call_user_func_array([$this, $this->getMethodName(count($args))], $args);
    }

    /**
     * Get the name of the action method based based on the argument map
     *
     * @param integer $count
     * @return string
     */
    protected function getMethodName(int $count)
    {
        $map = $method = $this->actionMap[$count];

        if (is_array($map)) {
            foreach ($map as $method => $segmentKeyValue) {
                $position = array_key_first($segmentKeyValue);
                $segment  = array_pop($segmentKeyValue);

                if ($this->hasSegment($segment, $position)) {
                    break;
                }
            }
        }

        return $method;
    }

    /**
     * Check if a mapped segment matches the first url segment
     *
     * @param string $segment
     * @return boolean
     */
    public function hasSegment(string $segment, int $position)
    {
        return request()->segment($position) == $segment;
    }

    /**
     * Automatically take action on class using HasSubAction
     *
     * @param array ...$args
     * @return void
     */
    public function take(...$args)
    {
        return $this->subAction($args);
    }
}
