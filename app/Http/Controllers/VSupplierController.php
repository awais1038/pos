<?php

namespace App\Http\Controllers;
use App\Models\VSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VSupplierController extends Controller
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
        //$product = Product::orderBy('id','desc')->paginate(2);
        // $supplier = DB::table('supplier')
        // ->select('supplier.id','supplier.name', 'supplier.code', 'supplier.email', 'supplier.phone', 'supplier.address')
        // ->get();
        $supplier = VSupplier::get();
        return $supplier;
        //return view('supplier.index', compact('supplier'));
    }

    public function showall()
    {
        $supplier = VSupplier::get();
        return view('vsupplier.index', compact('supplier'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('supplier.create');
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

        VSupplier::create($record);
        return response('', 200);
        //return redirect()->route('supplier.index')->with('success','Supplier has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Supplier  $Supplier
    * @return \Illuminate\Http\Response
    */
    public function show(Supplier $Supplier)
    {
        return view('supplier.show',compact('Supplier'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Supplier  $Supplier
    * @return \Illuminate\Http\Response
    */
    public function edit(Supplier $Supplier)
    {
        return view('supplier.edit',compact('Supplier'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Supplier  $Supplier
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $supplier = VSupplier::findOrFail($id);
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);

        $record['code'] = $request->code;
        $record['name'] = $request->name;
        $record['phone'] = $request->phone;
        $record['email'] = $request->email;
        $record['address'] = $request->address;

        $recordUpdate = VSupplier::where('id', $id)->update($record);
        //$Supplier->fill($request->post())->save();
        return response('', 200);
        //return redirect()->route('supplier.index')->with('success','Supplier Has Been updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Supplier  $Supplier
    * @return \Illuminate\Http\Response
    */
    public function destroy(Supplier $Supplier)
    {
        $Supplier->delete();
        return redirect()->route('supplier.index')->with('success','Supplier has been deleted successfully');
    }
}
