<?php

/**
 * Middleware Handler
 * 
 * Manages the middleware chain execution
 */
class MiddlewareHandler
{
    /**
     * @var array List of middleware to execute
     */
    private $middlewares = [];
    
    /**
     * Add middleware to the chain
     * 
     * @param Middleware $middleware Middleware instance
     * @return $this
     */
    public function add($middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
    
    /**
     * Execute the middleware chain
     * 
     * @param callable $final The final callback to execute after all middleware
     * @return mixed
     */
    public function run($final)
    {
        $next = $final;
        
        // Build the middleware chain in reverse order
        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = function() use ($middleware, $next) {
                return $middleware->handle($next);
            };
        }
        
        // Execute the middleware chain
        return $next();
    }
}
