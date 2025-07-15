<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * Get categories for the create form.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesForForm()
    {
        return Category::whereNull(['parent_id', 'deleted_at'])->get(); // Exclude soft-deleted categories.
    }

    /**
     * Get all categories with their children and grandchildren.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return Category::whereNull('parent_id')
            ->with('children.children') // Eager load sub categories and sub sub categories.
            ->whereNull('deleted_at') // Exclude soft-deleted categories.
            ->get();
    }

    /**
     * Get subcategories for a given category.
     *
     * @param Category $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSubcategories(Category $category)
    {
        return $category->children()->get();
    }

    /**
     * Create new categories from validated data.
     *
     * @param array $data
     * @return void
     */
    public function createCategories(array $data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data['newCategories'] as $newCategory) {
                Category::create([
                    'name' => $newCategory['name'],
                    'parent_id' => $data['child_id'] ?? $data['parent_id'],
                ]);
            }
        });
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    /**
     * Delete a category and its descendants.
     *
     * @param Category $category
     * @return void
     */
    public function deleteCategory(Category $category)
    {
        // Recursively soft delete the category, its descendants, and associated listings.
        DB::transaction(function () use ($category) {
            $category->deleteDescendantsAndListings();
        });
    }
}
