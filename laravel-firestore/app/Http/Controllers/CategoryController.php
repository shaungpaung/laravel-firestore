<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class CategoryController extends Controller
{
    protected $firestore;

    public function __construct(FirestoreClient $firestore)
    {
        $this->firestore = $firestore;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->firestore->collection('categories')->documents();

        // Convert Firestore documents to array
        $data = [];
        foreach ($categories as $category) {
            $data[] = $category->data();
        }

        return response()->json(['categories' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $categoryData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $this->firestore->collection('categories')->add($categoryData);

        return response()->json(['message' => 'Category created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->firestore->collection('categories')->document($id)->snapshot();

        if (!$category->exists()) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['category' => $category->data()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $categoryData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $this->firestore->collection('categories')->document($id)->set($categoryData);

        return response()->json(['message' => 'Category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = $this->firestore->collection('categories')->document($id);

        if (!$category->snapshot()->exists()) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}