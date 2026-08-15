<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorHandler
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('api', 'api/*')) {
            return $next($request);
        }

        $response = $next($request);

        if ($response->getStatusCode() < 400) {
            return $response;
        }

        return $this->normalize($response);
    }

    private function normalize(Response $response): JsonResponse
    {
        $status = $response->getStatusCode();

        $content = $response->getContent();
        $decoded = is_string($content) ? json_decode($content, true) : null;
        $body = is_array($decoded) ? $decoded : [];

        $bodyMessage = is_string($body['message'] ?? null) ? $body['message'] : '';

        return response()->json([
            'stacktrace' => $this->stacktrace($body),
            'message' => $this->message($status, $bodyMessage),
            'error' => $this->code($status),
        ], $status);
    }

    private function code(int $status): string
    {
        return match ($status) {
            400 => 'bad_request',
            401 => 'unauthenticated',
            403 => 'forbidden',
            404 => 'not_found',
            405 => 'method_not_allowed',
            422 => 'validation_error',
            429 => 'too_many_requests',
            500 => 'internal_error',
            502 => 'upstream_error',
            503 => 'service_unavailable',
            default => 'http_error',
        };
    }

    private function message(int $status, string $bodyMessage): string
    {
        $defaults = [
            400 => 'Requisição inválida.',
            401 => 'Não autenticado.',
            403 => 'Ação não permitida.',
            404 => 'Rota ou recurso não encontrado.',
            405 => 'Método HTTP não permitido.',
            422 => 'Dados inválidos.',
            429 => 'Muitas requisições. Tente novamente mais tarde.',
            502 => 'Erro ao acessar o serviço externo.',
            503 => 'Serviço temporariamente indisponível.',
        ];

        $default = $defaults[$status] ?? 'Erro HTTP.';

        if ($bodyMessage === '') {
            return $default;
        }

        return in_array($status, [422, 502], true) ? $bodyMessage : $default;
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private function stacktrace(array $body): ?string
    {
        if (! config('app.debug')) {
            return null;
        }

        $trace = $body['trace'] ?? null;

        if (! is_array($trace)) {
            return null;
        }

        $encoded = json_encode($trace);

        return $encoded !== false ? $encoded : null;
    }
}
