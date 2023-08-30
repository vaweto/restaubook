<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->limit(15)->paginate();
        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $image = $request->file('image')->store('public/categories');

        Category::create([
           'name' => $request->name,
           'description' => $request->description,
           'image' => $image
        ]);

        return to_route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Category $category
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category') );
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryUpdateRequest $request
     * @param Category $category
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $image = $category->image;

        if($request->hasFile('image')) {
            Storage::delete($image);

            $image = $request->file('image')->store('public/categories');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);

        return to_route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category)
    {
        $image = $category->image;

        $category->delete();

        Storage::delete($image);

        return back();
    }
}
