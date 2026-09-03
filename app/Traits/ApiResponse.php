<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param array|string|null $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param int $code
     * @param array|string|null $errors
     * @return JsonResponse
     */
    protected function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a JSON response with pagination.
     *
     * @param LengthAwarePaginator $paginator
     * @param string $message
     * @return JsonResponse
     */
    protected function paginate(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem()
            ]
        ]);
    }

    /**
     * Return a JSON response with resource.
     *
     * @param JsonResource $resource
     * @param string $message
     * @return JsonResponse
     */
    protected function resource(JsonResource $resource, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource
        ]);
    }

    /**
     * Return a JSON response with resource collection.
     *
     * @param ResourceCollection $collection
     * @param string $message
     * @return JsonResponse
     */
    protected function collection(ResourceCollection $collection, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection
        ]);
    }
}
