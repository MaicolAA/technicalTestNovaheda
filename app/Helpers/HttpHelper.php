<?php

if (!function_exists('getStatusCode')) {
    /**
     * Obtiene el código de estado HTTP para la excepción.
     *
     * @param  \Exception  $exception
     * @return int
     */
    function getStatusCode($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500; 
    }
}