<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Http\Requests\StoreTranslationsRequest;
use App\Http\Requests\UpdateTranslationsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Translation Management API",
 *     version="1.0.0",
 *     description="API for managing translations with multi-locale and tagging support."
 * )
 *
 * @OA\Tag(
 *     name="Translations",
 *     description="Operations on translations"
 * )
 */
class TranslationsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Get paginated list of translations",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of translations"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Translation::latest()->paginate(50));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Create a new translation",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key","locale","content"},
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Translation created")
     * )
     */
    public function store(StoreTranslationsRequest $request): JsonResponse
    {
        $translation = Translation::create($request->all());
        return response()->json($translation, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Get a specific translation by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="locale", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(Translation $translation): JsonResponse
    {
        return response()->json($translation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Translation $translation)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Update an existing translation",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Translation updated"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(UpdateTranslationsRequest $request, Translation $translation): JsonResponse
    {
        $translation->update($request->all());
        return response()->json($translation);
    }

    /**
     * @OA\Delete(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Delete a translation by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Translation deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy(Translation $translation): JsonResponse
    {
        $translation->delete();
        return response()->json(['message' => 'Translation Deleted']);
    }

    /**
     * @OA\Get(
     *     path="/api/translations/search",
     *     tags={"Translations"},
     *     summary="Search translations by key, content, or tag",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="key", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="tag", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="content", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Search results")
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $query = Translation::query();

        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
        }

        if ($request->has('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->has('content')) {
            $query->where('content', 'like', "%{$request->content}%");
        }

        return response()->json($query->paginate(50));
    }

    /**
     * @OA\Get(
     *     path="/api/translations/export",
     *     tags={"Translations"},
     *     summary="Export translations by locale as JSON",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="locale", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Exported translations")
     * )
     */
    public function export(Request $request): mixed
    {
        $locale = $request->query('locale', 'en');
        $cacheKey = "translations_json_{$locale}";

        return Cache::remember($cacheKey, 60, function () use ($locale) {
            $translations = Translation::where('locale', $locale)->get()->mapWithKeys(function ($item) {
                return [$item->key => $item->content];
            });

            return response()->json($translations, 200);
        });
    }
}
