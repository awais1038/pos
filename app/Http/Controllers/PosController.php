<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Sales;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
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
        $product = Product::with('category', 'productUnit')->get();
        return view('pos.index',get_defined_vars());
    }

    public function search(Request $request)
    {
        $search = $request->get('term');
        $products = Product::select('id', 'name as value','code')->where('name', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->get();
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product['id'] = $product->id;
                $product['value'] = $product->value.'~ ('.$product->code.')';   
            }
        }else{
            $product=array();
        }

        return response()->json($product);
    }

    public function append_product(Request $request)
    {
        $product_name = $request->get('id');
        
        $products = Product::select('id', 'name as value','code','unit_price')->where('name', '=', $product_name)->get();
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product['id'] = $product->id;
                $product['name'] = $product->value.' ('.$product->code.')';   
                $product['price'] = $product->unit_price;
            }
        }else{
            $product=array();
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        
        //Validate array of items
        // $request->validate([
        //     'price' => 'required|string|max:255',
        //     'quantity' => 'required|numeric|min:1',    
        //     'invoice.customer_id' => 'required|numeric|min:1',
        //     'invoice.total_value' => 'required|numeric|min:0',
        //     'invoice.total_payable_value' => 'required|numeric|min:0',
        // ]);
        
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $product_id = $request->input('product_id');
        $subtotalval = $request->input('subtotalval');

        // Create a new sales record
        $invoice = Invoice::create([
            'customer_id' => $request->input('invoice.customer_id'),
            'amount_tendered' => $request->input('invoice.total_value'),
            'total_amount' => $request->input('invoice.total_payable_value'),
            'payment_type' => $request->input('payment_type'),
            'created_by' => Auth::id(),
            'date_recorded' => date('Y-m-d')
        ]);

        foreach ($price as $index => $pri) {
            // Insert each sale into the database
            Sales::create([
                'unit_price' => $pri,
                'quantity' => $quantity[$index],
                'sub_total' => $subtotalval[$index],
                'product_id' => $product_id[$index],
                'invoice_id' => $invoice->id
            ]);
        }
        // Insert items associated with the sales record
        // $sales = Sales::create($request->input('items'));

        // Create a new invoice record associated with the sales record
        //$sales->invoice()->create($request->input('items'));

        //return redirect()->route('pos.saleview/'.$invoice->id)->with('success', 'Sale created successfully');
        return redirect()->route('saleview', ['id' => $invoice->id]);
    }

    public function saleview($id)
    {
       // $invoice = Sales::with('invoice', 'customer','products')->where('invoice_id', 3)->get();
        //DB::enableQueryLog();
        //$invoice = Sales::with('invoice', 'customer','products')->where('invoice_id', 3)->get();

        $invoice = DB::table('tblsales')
        ->join('tblinvoice', 'tblsales.invoice_id', '=', 'tblinvoice.id')
        ->join('customer', 'tblinvoice.customer_id', '=', 'customer.id')
        ->join('product', 'tblsales.product_id', '=', 'product.id')
        ->select('product.id','product.name', 'tblsales.unit_price', 'product.unit_in_stock', 'tblsales.quantity AS quantity', 'tblsales.sub_total AS sub_total', 'tblinvoice.total_amount AS total_amount', 'tblinvoice.amount_tendered AS amount_tendered')->where('invoice_id', $id)
        ->get();


       // $query = DB::getQueryLog();
        //dd($query);
        return view('pos.saleview',get_defined_vars());
    }
}
