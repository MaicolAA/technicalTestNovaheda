<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class MainController extends BaseController 
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;


    /**
     * Metodo estandar para retornar un mensaje
     * @param string $message
     * @return JsonResponse
     */
    public function message(string $message): JsonResponse
    {
        $content = [
            'status' => 200,
            'message' => $message
        ];

        return response()->json($content, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Metodo estandar para retornar un 200 y un body
     * @param array<mixed> $body
     * @param string $message
     * @return JsonResponse
     */
    public function ok(array $body, string $message = ''): JsonResponse
    {
        $content = [];

        if (!empty($message)) {
            $content['message'] = $message;
        }

        if (!is_null($body)) {
            $content['body'] = is_array($body) ? $body : $body->toArray();
        }

        return response()->json($content, 200);
    }

    /**
     * Metodo estandar para responde a la creación de una entidad con codigo 201 y cuerpo
     * @param array<mixed> $body
     * @param string $message
     * @return JsonResponse
     */
    public function created(array $body, string $message = ''): JsonResponse
    {
        $content = [];

        if (!empty($message)) {
            $content['message'] = $message;
        }

        if (!is_null($body)) {
            $content['body'] = is_array($body) ? $body : $body->toArray();
        }

        return response()->json($content, 201);
    }

    /**
     * Metodo estandar para responder errores (400, 404, 422)
     * @param string|array<mixed> $message
     * @param int $status
     * @return JsonResponse
     */
    public function error(string|array $message, int $status = 400): JsonResponse
    {
        $message = !is_array($message) ? [
            'status' => $status,
            'message' => $message
        ] : $message;

        return response()->json($message, $status);
    }

    /**
     * Metodos estandar para las los errores de las validaciones de entrada de los endpoints
     * @param string $message
     * @param ValidationException $exception
     * @return JsonResponse
     */
    public function validate(string $message, ValidationException $exception): JsonResponse
    {
        $content = [
            'message' => $message,
            'errors' => $exception->errors()
        ];

        return response()->json($content, $exception->status, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

}
