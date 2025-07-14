<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreListingRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Listing::query()->with('category'); // Eager load the category relationship

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
                    $q->orWhere('price', '=', $search);
                }
            });
        }

        $listings = $query->paginate(8)->withQueryString();
        $categories = Category::whereNull('parent_id')->get();

        return Inertia::render('Welcome', [
            'listings' => $listings,
            'categories' => Category::whereNull('parent_id')->get()
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function customerListings()
    {
        $listings = Listing::with('category')
        ->where('user_id', Auth::id())
        ->paginate(4);

        return Inertia::render('Customer/Listings', ['listings' => $listings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Customer/Listing/Create', ['categories' => Category::whereNull('parent_id')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request)
    {
        // Get validated data
        $validated = $request->validated();

        // Assign the currently authenticated user
        $validated['user_id'] = Auth::id();

        // Create the listing first
        $listing = Listing::create($validated);
        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $timestamp = now()->format('YmdHis');
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$timestamp}_listing_{$listing->id}.{$extension}";

            $validated['image_path'] = $request->file('image')->storeAs(
                'images',
                $filename,
                'public'
            );
            
            // Update the listing with the image path
            $listing->update(['image_path' => $validated['image_path']]);
        }

        return redirect()->route('customer.create')->with('flash', [
            'type' => 'success',
            'message' => 'Listing created successfully.',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        return Inertia::render('Listing', [
            'listing' => $listing->load('category', 'user'),
            'categories' => Category::whereNull('parent_id')->get()
        ]);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Listing $listing)
    {
        // Load the listing with its category
        $listing->load('category', 'user');

        $categories = Category::whereNull('parent_id')->get();
        
        $pathIds = $listing->category?->getCategoryPathIds() ?? [];


         $parentId = $pathIds[0] ?? null;
         $childId = $pathIds[1] ?? null;
         $grandchildId = $pathIds[2] ?? null;

         return Inertia::render('Customer/Listing/Edit', [
            'listing' => $listing,
            'categories' => $categories,
            'selectedCategories' => [
                'parent_id' => $parentId,
                'child_id' => $childId,
                'grandchild_id' => $grandchildId,
            ],
        ]);
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(StoreListingRequest $request, Listing $listing)
    {
        // Ensure the authenticated user owns the listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'error',
                'message' => 'You are not authorized to update this listing.',
            ]);
        }
        
        // Get validated data
        $validated = $request->validated();
        
        // Handle image upload if exists
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($listing->image_path) {
                \Storage::disk('public')->delete($listing->image_path);
            }
            
            $timestamp = now()->format('YmdHis');
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$timestamp}_listing_{$listing->id}.{$extension}";

            $validated['image_path'] = $request->file('image')->storeAs(
                'images',
                $filename,
                'public'
            );
        }

        // Update the listing
        $listing->update($validated);
        
        return redirect()->route('customer.listings')->with('flash', [
            'type' => 'success',
            'message' => 'Listing updated successfully.',
        ]);
    }
    
    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Listing $listing)
    {
        // Ensure the authenticated user owns the listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'error',
                'message' => 'You are not authorized to delete this listing.',
            ]);
        }
        
        // Delete image if exists
        if ($listing->image_path) {
            \Storage::disk('public')->delete($listing->image_path);
        }
        
        // Delete the listing
        $listing->delete();

        return redirect()->route('customer.listings')->with('flash', [
            'type' => 'success',
            'message' => 'Listing deleted successfully.',
        ]);
    }
}
