<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingService
{
    /**
     * Get filtered and paginated listings.
     *
     * @param Request $request
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredListings(Request $request, int $perPage = 8)
    {
        $query = Listing::query()->with('category')->whereNull('deleted_at'); // Eager load the category relationship

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });

                if (is_numeric($search)) {
                    $q->orWhere('price', 'like', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get listings for the authenticated user.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCustomerListings(int $perPage = 4)
    {
        return Listing::with('category')
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->paginate($perPage);
    }

    /**
     * Get categories for listing creation or editing.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories()
    {
        return Category::whereNull(['parent_id', 'deleted_at'])->get();
    }

    /**
     * Create a new listing with optional image upload.
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $image
     * @return Listing
     */
    public function createListing(array $data, $image = null)
    {
        $data['user_id'] = Auth::id();
        $listing = Listing::create($data);

        if ($image) {
            $path = $this->uploadImage($image, $listing->id);
            $listing->update(['image_path' => $path]);
        }

        return $listing;
    }

    /**
     * Update an existing listing with optional image upload.
     *
     * @param Listing $listing
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $image
     * @return Listing
     * @throws \Exception
     */
    public function updateListing(Listing $listing, array $data, $image = null)
    {
        if ($image) {
            if ($listing->image_path) {
                Storage::disk('public')->delete($listing->image_path);
            }
            $path = $this->uploadImage($image, $listing->id);
            $data['image_path'] = $path;
        }

        $listing->update($data);

        return $listing;
    }

    /**
     * Delete a listing and its associated image.
     *
     * @param Listing $listing
     * @return void
     * @throws \Exception
     */
    public function deleteListing(Listing $listing)
    {
        if ($listing->image_path) {
            Storage::disk('public')->delete($listing->image_path);
        }

        // Soft delete.
        $listing->delete();
    }

    /**
     * Upload an image for a listing.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param int $listingId
     * @return string
     */
    protected function uploadImage($image, int $listingId)
    {
        $timestamp = now()->format('YmdHis');
        $extension = $image->getClientOriginalExtension();
        $filename = "{$timestamp}_listing_{$listingId}.{$extension}";

        return Storage::disk('public')->putFileAs('images', $image, $filename);
    }

    /**
     * Get category hierarchy for a listing.
     *
     * @param Listing $listing
     * @return array
     */
    public function getCategoryHierarchy(Listing $listing)
    {
        return $listing->category?->getCategoryHierarchy() ?? [];
    }
}
