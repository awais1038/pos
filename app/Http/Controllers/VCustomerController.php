<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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
        $customer = DB::table('customer')
        ->select('customer.id','customer.name', 'customer.code', 'customer.email', 'customer.phone', 'customer.address')
        ->get();
        return view('customer.index', compact('customer'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('customer.create');
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
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);

        $record = array();
        
        $record['code'] = $request->code;
        $record['name'] = $request->name;
        $record['email'] = $request->email;
        $record['phone'] = $request->phone;
        $record['address'] = $request->address;

        Customer::create($record);

        return redirect()->route('customer.index')->with('success','Customer has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Customer  $Customer
    * @return \Illuminate\Http\Response
    */
    public function show(Customer $Customer)
    {
        return view('customer.show',compact('Customer'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Customer  $Customer
    * @return \Illuminate\Http\Response
    */
    public function edit(Customer $Customer)
    {
        return view('customer.edit',compact('Customer'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Customer  $Customer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Customer $Customer)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);
        
        $Customer->fill($request->post())->save();

        return redirect()->route('customer.index')->with('success','Customer Has Been updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Customer  $Customer
    * @return \Illuminate\Http\Response
    */
    public function destroy(Customer $Customer)
    {
        $Customer->delete();
        return redirect()->route('customer.index')->with('success','Customer has been deleted successfully');
    }
}
