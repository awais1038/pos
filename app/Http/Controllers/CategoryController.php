<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class CategoryController extends Controller
{
    public function index()
    {
        // $category = Category::orderBy('id','desc')->paginate(2);
        // return view('category.index', compact('category'));
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get();
        return view('category.index', compact('categories'));
    }
    public function create(Request $request)
    {
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get();
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

            Category::create([
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

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' =>$request->parent_id,
            'created_by' =>Auth::id()
        ]);

        return redirect()->route('category.index')->with('success', 'Category has been created successfully.');
    }

    public function show(Category $Category)
    {
        return view('category.show',compact('Category'));
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
        $category = Category::findOrFail($id);
        if($request->method()=='GET')
        {
            $categories = Category::where('parent_id', null)->where('id', '!=', $category->id)->orderby('name', 'asc')->get();
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
        
        $category = Category::findOrFail($id);
       
        
            
            $validator = $request->validate([
                'name'     => 'required',
                'slug' => ['required', Rule::unique('categories')->ignore($category->id)],
                'parent_id'=> 'nullable|numeric'
            ]);
            
            if($request->name != $category->name || $request->parent_id != $category->parent_id)
            {
                if(isset($request->parent_id))
                {
                    $checkDuplicate = Category::where('name', $request->name)->where('parent_id', $request->parent_id)->first();
                    if($checkDuplicate)
                    {
                        return redirect()->back()->with('error', 'Category already exist in this parent.');
                    }
                }
                else
                {
                    $checkDuplicate = Category::where('name', $request->name)->where('parent_id', null)->first();
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
            return redirect()->route('category.index')->with('success', 'Category has been updated successfully.');
        

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Category  $Category
    * @return \Illuminate\Http\Response
    */
    public function destroy(Category $Category)
    {
        $Category->delete();
        return redirect()->route('category.index')->with('success','Category has been deleted successfully');
    }
}
