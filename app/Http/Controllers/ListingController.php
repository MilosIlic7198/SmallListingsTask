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
    public function index()
    {
        //
        $listings = Listing::with(['user', 'category'])->get();

        return Inertia::render('Welcome', [
            'listings' => $listings,
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
