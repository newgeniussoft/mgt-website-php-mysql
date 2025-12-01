<?php

/**
 * Base Middleware Class
 * 
 * Abstract class that all middleware must extend
 */
abstract class Middleware
{
    /**
     * Handle the middleware request
     * 
     * @param callable $next The next middleware in the chain
     * @return mixed
     */
    abstract public function handle($next);
}
