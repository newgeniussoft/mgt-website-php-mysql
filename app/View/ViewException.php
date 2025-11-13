<?php

namespace App\View;

use Exception;

class ViewException extends Exception
{
    /**
     * Create a new view exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create a view not found exception.
     *
     * @param string $view
     * @return static
     */
    public static function viewNotFound($view)
    {
        return new static("View [{$view}] not found.");
    }

    /**
     * Create a view compilation exception.
     *
     * @param string $view
     * @param string $error
     * @return static
     */
    public static function compilationError($view, $error)
    {
        return new static("Error compiling view [{$view}]: {$error}");
    }

    /**
     * Create a view data exception.
     *
     * @param string $view
     * @param string $variable
     * @return static
     */
    public static function undefinedVariable($view, $variable)
    {
        return new static("Undefined variable [{$variable}] in view [{$view}].");
    }

    /**
     * Create a view path exception.
     *
     * @param string $path
     * @return static
     */
    public static function invalidPath($path)
    {
        return new static("Invalid view path: {$path}");
    }

    /**
     * Create a view engine exception.
     *
     * @param string $message
     * @return static
     */
    public static function engineError($message)
    {
        return new static("View engine error: {$message}");
    }
}
