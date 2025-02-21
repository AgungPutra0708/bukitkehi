<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'cart_id' => 'required|exists:carts,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'ticket_id' => $request->ticket_id, 'cart_id' => $request->cart_id],
            ['rating' => $request->rating]
        );

        return response()->json(['success' => 'Rating Berhasil Diberikan']);
    }
}
