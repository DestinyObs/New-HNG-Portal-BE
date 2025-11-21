<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ApiDocsTest extends TestCase
{
    public function test_api_docs_paths_exist_in_routes(): void
    {
        $jsonPath = base_path('storage/api-docs/api-docs.json');
        $this->assertFileExists($jsonPath, "api-docs.json not found at {$jsonPath}");

        $data = json_decode(file_get_contents($jsonPath), true);
        $this->assertIsArray($data, 'api-docs.json could not be decoded as JSON');
        $this->assertArrayHasKey('paths', $data, "No 'paths' key in api-docs.json");

        $docPaths = array_keys($data['paths']);

        $routeUris = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $routeUris[] = '/' . ltrim($route->uri(), '/');
        }
        $routeUris = array_unique($routeUris);

        // Normalize parameter names: convert {anything} -> {param}
        $normalize = static function (string $p): string {
            $p = '/' . ltrim($p, '/');
            return preg_replace('/\{[^}]+\}/', '{param}', $p);
        };

        $normalizedRoutes = array_map($normalize, $routeUris);

        $missing = [];
        foreach ($docPaths as $path) {
            $docNorm = $normalize($path);

            if (in_array($docNorm, $normalizedRoutes, true)) {
                continue;
            }

            // Try with or without leading /api prefix
            $withApi = $docNorm;
            $withoutApi = preg_replace('#^/api#', '', $docNorm);

            if (in_array('/api' . $withoutApi, $normalizedRoutes, true) || in_array($withoutApi, $normalizedRoutes, true) || in_array($withApi, $normalizedRoutes, true)) {
                continue;
            }

            $missing[] = $docNorm;
        }

        $this->assertEmpty($missing, "Some api-docs paths are not present in application routes: " . implode(', ', $missing));
    }
}
