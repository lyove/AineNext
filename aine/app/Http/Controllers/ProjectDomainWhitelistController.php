<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectOrigin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectDomainWhitelistController extends Controller
{
    /**
     * List all whitelisted origins for a project.
     */
    public function index(Project $project): JsonResponse
    {
        $origins = $project->origins()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($origins);
    }

    /**
     * Add a new origin to the whitelist.
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'origin' => 'required|string|url:http,https|max:255',
            'description' => 'nullable|string|max:255',
            'abilities' => 'sometimes|array|min:1',
            'abilities.*' => 'in:read,create,update',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $normalizedOrigin = $this->normalizeOrigin($request->input('origin'));

        // Check if origin already exists for this project
        $exists = $project->origins()
            ->where('origin', $normalizedOrigin)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This origin is already in the whitelist.',
            ], 422);
        }

        $origin = $project->origins()->create([
            'origin' => $normalizedOrigin,
            'description' => $request->input('description'),
            'abilities' => $request->input('abilities', ['read']),
        ]);

        return response()->json($origin, 201);
    }

    /**
     * Update an existing origin configuration.
     */
    public function update(Request $request, Project $project, ProjectOrigin $origin): JsonResponse
    {
        if ($origin->project_id !== $project->id) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:255',
            'abilities' => 'sometimes|array|min:1',
            'abilities.*' => 'in:read,create,update',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $origin->update($request->only(['description', 'abilities']));

        return response()->json($origin);
    }

    /**
     * Delete an origin from the whitelist.
     */
    public function destroy(Project $project, ProjectOrigin $origin): JsonResponse
    {
        if ($origin->project_id !== $project->id) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $origin->delete();

        return response()->json(['message' => 'Origin removed from whitelist.']);
    }

    /**
     * Toggle an origin's active status.
     */
    public function toggle(Project $project, ProjectOrigin $origin): JsonResponse
    {
        if ($origin->project_id !== $project->id) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $origin->is_active = ! $origin->is_active;
        $origin->save();

        return response()->json([
            'is_active' => $origin->is_active,
        ]);
    }

    /**
     * Normalize origin URL: lowercase scheme/host, remove trailing slash.
     */
    protected function normalizeOrigin(string $origin): string
    {
        $parsed = parse_url($origin);

        if (! $parsed || ! isset($parsed['scheme'], $parsed['host'])) {
            return rtrim($origin, '/');
        }

        $scheme = strtolower($parsed['scheme']);
        $host = strtolower($parsed['host']);
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';

        return "{$scheme}://{$host}{$port}";
    }
}
