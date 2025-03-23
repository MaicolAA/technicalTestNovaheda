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
