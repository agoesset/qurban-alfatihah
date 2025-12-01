<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QurbanException extends Exception
{
    protected int $statusCode = 500;
    protected array $errorData = [];

    public function __construct(string $message = '', int $statusCode = 500, array $errorData = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errorData = $errorData;
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->errorData,
        ], $this->statusCode);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        \Log::error('Qurban Exception: ' . $this->getMessage(), [
            'status_code' => $this->statusCode,
            'error_data' => $this->errorData,
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
