<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
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
        // $purchaseorder = DB::table('purchaseorder')
        // ->join('product', 'product.id', '=', 'purchaseorder.product_id')
        // ->join('supplier', 'purchaseorder.supplier_id', '=', 'supplier.id')
        // ->select('purchaseorder.id','purchaseorder.unit_price', 'purchaseorder.quantity', 'purchaseorder.order_date', 'purchaseorder.sub_total', 'product.name AS product_name', 'supplier.name AS supplier_name')
        // ->get();
        $purchaseorder = PurchaseOrder::with('product', 'supplier')->get();
        return view('purchaseorder.index', compact('purchaseorder'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $supplier = Supplier::orderby('name', 'asc')->get();
        $product = Product::orderby('name', 'asc')->get();
        
        return view('purchaseorder.create', compact('supplier','product'));
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
            'product_id' => 'required',
            'supplier_id' => 'required',
            'unit_price' => 'required',
            'sub_total' => 'required',
            'order_date' => 'required'
        ]);

        $record = array();
        
        $record['name'] = $request->name;
        $record['product_id'] = $request->product_id;
        $record['supplier_id'] = $request->supplier_id;
        $record['quantity'] = $request->quantity;
        $record['unit_price'] = $request->unit_price;
        $record['sub_total'] = $request->sub_total;
        $record['order_date'] = $request->order_date;
        $record['created_by'] = Auth::id();

        PurchaseOrder::create($record);

        return redirect()->route('purchaseorder.index')->with('success','Order has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\PurchaseOrder  $PurchaseOrder
    * @return \Illuminate\Http\Response
    */
    public function show(PurchaseOrder $PurchaseOrder)
    {
        
        return view('purchaseorder.index',compact('PurchaseOrder'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\PurchaseOrder  $PurchaseOrder
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $supplier = Supplier::orderby('name', 'asc')->get();
        $product = Product::orderby('name', 'asc')->get();
        $PurchaseOrder = PurchaseOrder::findOrFail($id);
        return view('purchaseorder.edit', get_defined_vars());
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\PurchaseOrder  $PurchaseOrder
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'product_id' => 'required',
            'supplier_id' => 'required',
            'unit_price' => 'required',
            'sub_total' => 'required',
            'order_date' => 'required'
        ]);
        
        //$record = PurchaseOrder::findOrFail($id);

        $date = date("Y-m-d H:i:s");
        $requestData = [
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'sub_total' => $request->sub_total,
            'order_date' => $request->order_date,
            'updated_at' => $date
        ];

        $recordUpdate = PurchaseOrder::where('id', $id)->update($requestData);
        //$PurchaseOrder->fill($request->post())->save();
        
        return redirect()->route('purchaseorder.index')->with('success','purchaseorder Has Been updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\PurchaseOrder  $PurchaseOrder
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        
        $record = PurchaseOrder::findOrFail($id);
        $del = $record->delete();
        //$PurchaseOrder->delete();
        return redirect()->route('purchaseorder.index')->with('success','purchaseorder has been deleted successfully');
    }
}
