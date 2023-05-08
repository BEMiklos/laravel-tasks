<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase.create');
    }

    /**
     * Buy an item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buy(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'id' => 'required|exists:purchases',
            'item_id' => 'required|exists:items,id',
        ]);

        // Find the purchase by ID or create a new one with a sum of 0
        $purchase = Purchase::firstOrNew(['id' => $validatedData['id']]);
        if (!$purchase->exists) {
            $purchase->sum = 0;
        }

        // Find the item by ID and retrieve the price
        $item = Item::findOrFail($validatedData['item_id']);
        $price = $item->price;

        // Add the price to the sum
        $purchase->sum += $price;

        // Save the purchase
        $purchase->save();

        return redirect()->route('purchases.index')->with('success', 'Purchase completed successfully.');
    }
}
