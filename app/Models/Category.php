<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the listings for the category.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
    * Get the IDs of all parent categories up to the root, including self.
    *
    * @return array<int>
    */
    public function getCategoryHierarchy(): array
    {
        $ids = [];

        $category = $this;

        while ($category) {
            array_unshift($ids, $category->id); // Prepend to keep root-first order
            $category = $category->parent;
        }
        return $ids;
    }

    /**
    * Recursively soft delete the category, its descendants, and associated listings.
    */
    public function deleteDescendantsAndListings()
    {
        // Soft delete child categories recursively
        $this->children()->get()->each(function ($child) {
            $child->deleteDescendantsAndListings();
        });

        // Soft delete associated listings
        $this->listings()->get()->each(function ($listing) {
            $listing->delete();
        });

        // Soft delete the current category
        $this->delete();
    }
}
