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
    
    public function customerListings()
    {
        $listings = Listing::with('category')
        ->where('user_id', Auth::id())
        ->get();

        return Inertia::render('Customer/Listings', ['listings' => $listings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Customer/Listings', ['categories' => Category::whereNull('parent_id')->get()]);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        //
    }
}
