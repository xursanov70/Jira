<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\CategoryInterface;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryInterface
{

    public function createCategory(CategoryRequest $request)
    {
        $category = Category::create([
            'category_name' => $request->category_name
        ]);
        return response()->json(["message" => "Category created successfully!", "data" => $category]);
    }

    public function updateCategory(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(["message" => "Category mavjud emas!"]);
        }
        $category->update([
            'category_name' => $request->category_name
        ]);
        return response()->json(["message" => "Category updated successfully!"]);
    }

    public function getCategory()
    {
        $get = Category::get();
        return CategoryResource::collection($get);
    }
}
