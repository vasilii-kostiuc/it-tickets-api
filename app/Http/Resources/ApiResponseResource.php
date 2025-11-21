<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => $this->resource['success'] ?? true,
            'errors' => $this->resource['errors'] ?? null,
            'message' => $this->resource['message'] ?? null,
            'data' => $this->resource['data'] ?? null,
        ];
    }

    public static function success($data = null, string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $statusCode);
    }

    /**
     * Статический метод для ответа с ошибкой
     */
    public static function error(array $errors = null, string $message = null, int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Статический метод для пагинированного ответа
     * Pagination info в meta (JSON:API стандарт)
     */
    public static function paginated(LengthAwarePaginator $paginator, string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'errors' => null,
        ], $statusCode);
    }

}
