<?php

class ErrorHandler
{
    //*Throwable est l'interface de base pour tout objet qui peut être jeté grâce à la déclaration throw, ceci inclus Error et Exception.
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }

    public static function handleError(
        int    $errno,
        string $errstr,
        string $errfile,
        int    $errline
    ): bool
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

    }
}










