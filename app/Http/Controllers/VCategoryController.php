<?php

namespace App\Http\Controllers;

use App\Models\VCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class VCategoryController extends Controller
{
    public function index()
    {
        // $category = VCategory::orderBy('id','desc')->paginate(2);
        // return view('category.index', compact('category'));
        
        $categories = VCategory::select('categories.id', 'categories.name', 'categories.slug', 'categories.parent_id', 'parent.name as parent_name')
        ->leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.id')
        ->get();
        //$categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();

        return $categories;
        //return $categories = VCategory::all();
        //return view('vcategory.index', compact('categories'));
    }

    public function showall()
    {
        // $category = VCategory::orderBy('id','desc')->paginate(2);
        // return view('category.index', compact('category'));
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        return view('vcategory.index', compact('categories'));
    }
    public function catform()
    {
        // $category = VCategory::orderBy('id','desc')->paginate(2);
        // return view('category.index', compact('category'));
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        return view('vcategory.index', compact('categories'));
    }
    public function create(Request $request)
    {
        $categories = VCategory::where('parent_id', null)->orderby('name', 'asc')->get();
        if($request->method()=='GET')
        {
            return view('category.create', compact('categories'));
        }
        if($request->method()=='POST')
        {
            $validator = $request->validate([
                'name'      => 'required',
                'slug'      => 'required|unique:categories',
                'parent_id' => 'nullable|numeric'
            ]);

            VCategory::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' =>$request->parent_id,
                'created_by' =>Auth::id()
            ]);

            return redirect()->back()->with('success', 'Category has been created successfully.');
        }
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name'      => 'required',
            'slug'      => 'required|unique:categories',
            'parent_id' => 'nullable|numeric'
        ]);

        VCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' =>$request->parent_id,
            'created_by' =>1
        ]);
        return response('', 200);
        //return redirect()->route('category.index')->with('success', 'Category has been created successfully.');
    }

    public function show(Category $Category)
    {
        return $Category;
        //return view('category.show',compact('Category'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Category  $Category
    * @return \Illuminate\Http\Response
    */
    public function edit($id, Request $request)
    {
        //return view('category.edit',compact('Category'));
        $category = VCategory::findOrFail($id);
        if($request->method()=='GET')
        {
            $categories = VCategory::where('parent_id', null)->where('id', '!=', $category->id)->orderby('name', 'asc')->get();
            return view('category.edit', compact('category', 'categories'));
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Category  $Category
    * @return \Illuminate\Http\Response
    */
    public function update($id, Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'slug' => 'required',
        //     'parent_id' => 'nullable|numeric'
        // ]);
        
        // $Category->fill($request->post())->save();

        // return redirect()->route('category.index')->with('success','Category Has Been updated successfully');
        
        $category = VCategory::findOrFail($id);
       
        
            
            $validator = $request->validate([
                'name'     => 'required',
                'slug' => ['required', Rule::unique('categories')->ignore($category->id)],
                'parent_id'=> 'nullable|numeric'
            ]);
            
            if($request->name != $category->name || $request->parent_id != $category->parent_id)
            {
                if(isset($request->parent_id))
                {
                    $checkDuplicate = VCategory::where('name', $request->name)->where('parent_id', $request->parent_id)->first();
                    if($checkDuplicate)
                    {
                        return redirect()->back()->with('error', 'Category already exist in this parent.');
                    }
                }
                else
                {
                    $checkDuplicate = VCategory::where('name', $request->name)->where('parent_id', null)->first();
                    if($checkDuplicate)
                    {
                        return redirect()->back()->with('error', 'Category already exist with this name.');
                    }
                }
            }

            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            $category->slug = $request->slug;
            $category->save();
            return response('', 200);
            //return redirect()->route('category.index')->with('success', 'Category has been updated successfully.');
        

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Category  $Category
    * @return \Illuminate\Http\Response
    */
    public function destroy(VCategory $vcategory)
    {
        $vcategory->delete();
        return response('', 204);
        //return redirect()->route('category.index')->with('success','Category has been deleted successfully');
    }
}
