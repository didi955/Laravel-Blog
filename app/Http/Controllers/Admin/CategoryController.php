<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::latest()
                ->paginate(20),
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required', 'alpha:ascii', Rule::unique('categories', 'name')],
        ]);

        $category = new Category($attributes);
        $category->save();

        return back()->with('success', 'Category created successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted successfully');
    }
}
