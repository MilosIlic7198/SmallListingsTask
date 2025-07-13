<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Categories', [
            'categories' => Category::whereNull('parent_id')->get()
        ]);
    }

    /**
     * Endpoint to fetch all categories.
     */
    public function all(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->with('children.children') //Eager load sub categories and sub sub categories.
            ->get();
        return response()->json($categories);
    }

    /**
     * Endpoint to fetch categories for form dropdowns.
     */
    public function subcategories(Category $category)
    {
        $subcategories = $category->children()->get();
        return response()->json($subcategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'child_id' => 'nullable|exists:categories,id',
            'newCategories' => 'required|array|min:1',
            'newCategories.*.name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['newCategories'] as $newCategory) {
                Category::create([
                    'name' => $newCategory['name'],
                    'parent_id' => $validated['child_id'] ?? $validated['parent_id'],
                ]);
            }
        });

        return redirect()->route('admin.categories.create')->with('flash', [
            'type' => 'success',
            'message' => 'Category created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|not_in:'.$category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.create')->with('flash', [
            'type' => 'success',
            'message' => 'Category updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return redirect()->route('admin.categories')->with('flash', [
            'type' => 'error',
            'message' => 'Cannot delete category with subcategories.',
            'category_id' => $category->id,
        ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.create')->with('flash', [
            'type' => 'success',
            'message' => 'Category deleted successfully.',
        ]);
    }
}
