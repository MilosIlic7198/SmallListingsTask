<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = $this->userService->getAllCustomers();
        return Inertia::render('Admin/Customers', [
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
       try {
            $this->userService->createCustomer($request->validated());

            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'success',
                'message' => 'Customer created successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to create customer: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        try {
            return Inertia::render('Admin/Customer/Edit', [
                'customer' => $customer->only('id', 'name', 'email')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.customers')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to load customer edit form: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, User $customer)
    {
        try {
            $this->userService->updateCustomer($customer, $request->validated());

            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'success',
                'message' => 'Customer updated successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to update customer: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        try {
            $this->userService->deleteCustomer($customer);

            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'success',
                'message' => 'Customer deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to delete customer: ' . $e->getMessage(),
            ]);
        }
    }

    /**
    * Remove the specified customer from storage.
    */
    public function restore(User $customer)
    {
        try {
            $this->userService->restoreCustomer($customer);

            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'success',
                'message' => 'Customer restored successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('flash', [
                'type' => 'error',
                'message' => 'Failed to restored customer: ' . $e->getMessage(),
            ]);
        }
    }
}
