<?php

namespace ShahBurhan\OWA\Exceptions;

use Exception;
use Throwable;

class ActionNotFoundException extends Exception
{
    /**
     * Create a new ActionNotFound instance
     *
     * @param string $class
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct($class, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
    public static function missingClass($class)
    {

        $action = explode('\\', $class);

        $message = end($action) . " not found. Please check for wrong spelling";

        return new static($class, $message);
    }
    public static function invalidNameSyntax($class)
    {

        $action = explode('\\', $class);

        $message = "Invalid syntax for action name {" . end($action) . "}. Please use ObjectAction convention";

        return new static($class, $message);
    }
}
