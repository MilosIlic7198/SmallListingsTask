<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    protected $listingService;

    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {
            $listings = $this->listingService->getAllListings($request);
            return Inertia::render('Admin/Listings', [
                'listings' => $listings,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.listings.index')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to retrieve listings: ' . $e->getMessage(),
            ]);
        }
    }

    /**
    * Display a listing of the resource for public view.
    */
    public function publicIndex(Request $request): Response
    {
        return Inertia::render('Welcome', [
            'listings' => $this->listingService->getFilteredListings($request),
            'categories' => $this->listingService->getCategories(),
        ]);
    }

    /**
     * Display the customer listings.
     */
    public function customerListings(): Response
    {
        return Inertia::render('Customer/Listings', [
            'listings' => $this->listingService->getCustomerListings(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Customer/Listing/Create', [
            'categories' => $this->listingService->getCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request)
    {
        try {
            $this->listingService->createListing(
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('customer.listings.create')->with('flash', [
                'type' => 'success',
                'message' => 'Listing created successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('customer.listings.create')->with('flash', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing): Response
    {
        return Inertia::render('Listing', [
            'listing' => $listing->load('category', 'user'),
            'categories' => $this->listingService->getCategories(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing): Response
    {
        $listing->load('category', 'user');
        $pathIds = $this->listingService->getCategoryHierarchy($listing);

        return Inertia::render('Customer/Listing/Edit', [
            'listing' => $listing,
            'categories' => $this->listingService->getCategories(),
            'selectedCategories' => [
                'parent_id' => $pathIds[0] ?? null,
                'child_id' => $pathIds[1] ?? null,
                'grandchild_id' => $pathIds[2] ?? null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreListingRequest $request, Listing $listing)
    {
        try {
            $this->listingService->updateListing(
                $listing,
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'success',
                'message' => 'Listing updated successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        try {
            $this->listingService->deleteListing($listing);

            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'success',
                'message' => 'Listing deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('customer.listings')->with('flash', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
