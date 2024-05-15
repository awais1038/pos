<?php

namespace App\Http\Controllers;
use App\Models\VProduct;
use App\Models\VCategory;
use App\Models\VProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        //$product = VProduct::orderBy('id','desc')->paginate(2);
        // $product = DB::table('product')
        // ->join('product_unit', 'product.unit_id', '=', 'product_unit.id')
        // ->join('categories', 'product.category_id', '=', 'categories.id')
        // ->select('product.id','product.name', 'product.unit_price', 'product.unit_in_stock', 'product_unit.name AS unit_name', 'categories.name AS category_name', 'product.cost AS cost', 'product.quantity AS quantity')
        // ->get();
        $product = VProduct::with('category', 'productUnit')->get();
        return $product;
        //return view('product.index', compact('product'));
    }

    public function showall()
    {
        //$product = VProduct::orderBy('id','desc')->paginate(2);
        // $product = DB::table('product')
        // ->join('product_unit', 'product.unit_id', '=', 'product_unit.id')
        // ->join('categories', 'product.category_id', '=', 'categories.id')
        // ->select('product.id','product.name', 'product.unit_price', 'product.unit_in_stock', 'product_unit.name AS unit_name', 'categories.name AS category_name', 'product.cost AS cost', 'product.quantity AS quantity')
        // ->get();
        $product = VProduct::with('category', 'productUnit')->get();
        return view('vproduct.index', compact('product'));
        //return view('product.index', compact('product'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        $productunit = VProductUnit::orderby('name', 'asc')->get();
        
        return view('product.create', compact('categories','productunit'));
        
        //return view('product.create');
    }

    public function categoryList()
    {
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        return $categories;
        //$productunit = VProductUnit::orderby('name', 'asc')->get();
    }

    public function unitList()
    {
        $productunit = VProductUnit::orderby('name', 'asc')->get();
        return $productunit;
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
            'discount_percentage' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1048',
        ]);

        //print_r($request);exit;

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
        $record['discount_percentage'] = $request->discount_percentage;
        $record['created_by'] = Auth::id();
        $record['image'] = $imagePath; // Assuming the database column name is 'image_path'
        //$record->save();

        VProduct::create($record);
        return response('', 200);
        //return redirect()->route('product.index')->with('success','Product has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function show(Product $Product)
    {
        return $Product;
        //return view('product.show',compact('Product'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function edit(Product $Product)
    {
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        $productunit = VProductUnit::orderby('name', 'asc')->get();
        return view('product.edit',compact('Product','categories','productunit'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $Product
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id)
    {
        //print_r($request);exit;
        $product = VProduct::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'unit_in_stock' => 'required',
            'unit_price' => 'required',
            'cost' => 'required',
            'discount_percentage' => 'required'
        ]);
        
        $record = array();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $record['image'] = $imagePath;
        }else{
            //$imagePath = null;
        }
        
        $record['name'] = $request->name;
        $record['category_id'] = $request->category_id;
        $record['unit_id'] = $request->unit_id;
        $record['unit_in_stock'] = $request->unit_in_stock;
        $record['unit_price'] = $request->unit_price;
        $record['cost'] = $request->cost;
        
        $record['discount_percentage'] = $request->discount_percentage;
        //$record['updated_by'] = Auth::id();
        $record['updated_at'] = date("Y-m-d H:i:s");

        $recordUpdate = VProduct::where('id', $id)->update($record);
        //$Product->fill($request->post())->save();
        return response('', 200);
        //return redirect()->route('product.index')->with('success','Product Has Been updated successfully');
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
        return response('', 204);
        //return redirect()->route('product.index')->with('success','Product has been deleted successfully');
    }
}
