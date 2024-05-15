<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //$product = Product::orderBy('id','desc')->paginate(2);
        // $product = DB::table('product')
        // ->join('product_unit', 'product.unit_id', '=', 'product_unit.id')
        // ->join('categories', 'product.category_id', '=', 'categories.id')
        // ->select('product.id','product.name', 'product.unit_price', 'product.unit_in_stock', 'product_unit.name AS unit_name', 'categories.name AS category_name', 'product.cost AS cost', 'product.quantity AS quantity')
        // ->get();
        $product = Product::with('category', 'productUnit')->get();
        return view('product.index', compact('product'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get();
        $productunit = ProductUnit::orderby('name', 'asc')->get();
        
        return view('product.create', compact('categories','productunit'));
        
        //return view('product.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'unit_in_stock' => 'required',
            'unit_price' => 'required',
            'cost' => 'required',
            'quantity' => 'required',
            'discount_percentage' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }else{
            $imagePath = null;
        }
        $record = array();
        
        $record['name'] = $request->name;
        $record['category_id'] = $request->category_id;
        $record['unit_id'] = $request->unit_id;
        $record['unit_in_stock'] = $request->unit_in_stock;
        $record['unit_price'] = $request->unit_price;
        $record['cost'] = $request->cost;
        $record['quantity'] = $request->quantity;
        $record['discount_percentage'] = $request->discount_percentage;
        $record['created_by'] = Auth::id();
        $record['image'] = $imagePath; // Assuming the database column name is 'image_path'
        //$record->save();

        Product::create($record);

        return redirect()->route('product.index')->with('success','Product has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function show(Product $Product)
    {
        return view('product.show',compact('Product'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function edit(Product $Product)
    {
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get();
        $productunit = ProductUnit::orderby('name', 'asc')->get();
        return view('product.edit',compact('Product','categories','productunit'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Product $Product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'unit_in_stock' => 'required',
            'unit_price' => 'required',
            'cost' => 'required',
            'discount_percentage' => 'required',
        ]);
        

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }else{
            $imagePath = null;
        }

        
        $record = array();
        
        $record['name'] = $request->name;
        $record['category_id'] = $request->category_id;
        $record['unit_id'] = $request->unit_id;
        $record['unit_in_stock'] = $request->unit_in_stock;
        $record['unit_price'] = $request->unit_price;
        $record['cost'] = $request->cost;
        $record['quantity'] = $request->quantity;
        $record['discount_percentage'] = $request->discount_percentage;
        //$record['updated_by'] = Auth::id();
        $record['updated_at'] = date("Y-m-d H:i:s");
        $record['image'] = $imagePath;

        $recordUpdate = Product::where('id', $Product->id)->update($record);
        //$Product->fill($request->post())->save();

        return redirect()->route('product.index')->with('success','Product Has Been updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function destroy(Product $Product)
    {
        $Product->delete();
        return redirect()->route('product.index')->with('success','Product has been deleted successfully');
    }
}
