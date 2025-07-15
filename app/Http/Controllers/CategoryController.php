<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        try {
            return Inertia::render('Admin/Categories', [
                'categories' => $this->categoryService->getCategoriesForForm(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to load the create form. Please try again.',
            ]);
        }
    }

    /**
     * Endpoint to fetch all categories.
     */
    public function all(Category $category)
    {
        try {
            return response()->json($this->categoryService->getAllCategories());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch categories'], 500);
        }
    }

    /**
     * Endpoint to fetch categories for form dropdowns.
     */
    public function subcategories(Category $category)
    {
        try {
            return response()->json($this->categoryService->getSubcategories($category));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subcategories'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->categoryService->createCategories($request->validated());

            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'success',
                'message' => 'Category created successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to create category. Please try again.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->updateCategory($category, $request->validated());

            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'success',
                'message' => 'Category updated successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to update category. Please try again.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Recursively soft delete the category, its descendants, and associated listings.
            $this->categoryService->deleteCategory($category);

            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'success',
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.create')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to delete category. Please try again.',
            ]);
        }
    }
}
