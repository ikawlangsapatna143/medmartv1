<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sales = Sale::query()
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.*')
            ->where(function ($query) use ($search) {
                $query->where('total_amount', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('sales.created_at', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('sales.index', [
            'sales' => $sales,
        ]);
    }

    public function refund($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->refunded += $sale->total_amount;
        $sale->total_amount = 0;
        $sale->status = 'refunded';

        $sale->save();

        return redirect()->route('sales.index')->with('success', 'Sale refunded successfully.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('saleDetails.product');

        return view('sales.show', ['sale' => $sale]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale) {}
}
