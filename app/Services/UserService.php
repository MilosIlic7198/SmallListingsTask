<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
    * Get all customers (users without admin role)
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getAllCustomers()
    {
        return User::withTrashed() 
        ->where('role', 'customer')
        ->select('id', 'name', 'email', 'deleted_at', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Create a new customer
     *
     * @param array $data
     * @return User
     */
    public function createCustomer(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);
    }

    /**
    * Update an existing customer
    *
    * @param User $user
    * @param array $data
    * @return User
    */
    public function updateCustomer(User $customer, array $data)
    {
        $customer->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => isset($data['password']) ? Hash::make($data['password']) : $customer->password,
        ]);

        return $customer;
    }

    /**
    * Delete a customer
    *
    * @param User $user
    * @return void
    */
    public function deleteCustomer(User $customer)
    {
        $customer->delete();
    }

    /**
    * Restore a customer
    *
    * @param User $user
    * @return void
    */
    public function restoreCustomer(User $customer)
    {
        $customer->restore();
    }
}
