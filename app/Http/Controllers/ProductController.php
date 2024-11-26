<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        // Search for products by name (using a case-insensitive search)
        $products = Product::where('product_name', 'like', '%' . $query . '%')->get();

        return response()->json($products);
    }


    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('product_name', 'asc')
            ->paginate(10);

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return view('products.partials._table', ['products' => $products])->render();
        }

        return view('products.index', [
            'products' => $products,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => ['required', 'max:100'],
            'generic_name' => ['max:100'],
            'category' => ['required'],
            'product_description' => ['required'],
            'price' => ['required', 'numeric'],
            'barcode' => ['nullable', 'string', 'unique:products,barcode'],
        ]);

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'The product is added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'product_name' => ['required', 'max:100'],
            'generic_name' => ['nullable', 'max:100'],
            'category' => ['required'],
            'product_description' => ['required'],
            'price' => ['required', 'numeric'],
            'barcode' => ['nullable', 'string', 'unique:products,barcode'],
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'The product is updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('deleted', 'The product was deleted');

    }
    public function export(Request $request)
    {
        // Optionally, handle filtering (search, category) here
        $search = $request->input('search');
        $category = $request->input('category');

        // Build the query for filtering
        $productsQuery = Product::query();

        // Select only the necessary columns
        $productsQuery->select('product_name', 'generic_name', 'price');

        if ($search) {
            $productsQuery->where('product_name', 'like', "%{$search}%");
        }

        if ($category) {
            $productsQuery->where('category', $category);
        }

        // Add sorting by product_name in ascending order
        $productsQuery->orderBy('product_name', 'asc');

        // Get the filtered and sorted products
        $products = $productsQuery->get();

        // Export filtered and sorted products
        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }


}
