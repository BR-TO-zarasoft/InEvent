<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class UpstreamException extends RuntimeException
{
    public static function fromStatus(string $service, int $status): self
    {
        return new self("Falha ao consultar {$service} (HTTP {$status}).");
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 502);
    }
}
