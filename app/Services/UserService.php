<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserService
{

    /**
    * Get all customers (users without admin role)
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getAllCustomers()
    {
        return User::where('role', 'customer')
        ->select('id', 'name', 'email', 'deleted_at', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
