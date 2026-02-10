<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerLookupController extends Controller
{
    /** AJAX endpoint – look up a customer by phone number. */
    public function lookup(Request $request): JsonResponse
    {
        $phone = $request->input('phone');

        if (! $phone || strlen($phone) < 4) {
            return response()->json(['found' => false]);
        }

        $customer = User::where('role', 'customer')
            ->where('phone', $phone)
            ->first(['id', 'name', 'phone']);

        return $customer
            ? response()->json(['found' => true, 'customer' => $customer])
            : response()->json(['found' => false]);
    }
}
