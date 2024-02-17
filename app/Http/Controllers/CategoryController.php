<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\CategoryInterface;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function __construct(protected CategoryInterface $categoryInterface)
    {
    }

    public function createCategory(CategoryRequest $request)
    {
        return $this->categoryInterface->createCategory($request);
    }
    public function updateCategory(Request $request, $category_id)
    {
        return $this->categoryInterface->updateCategory($request, $category_id);
    }
    public  function getCategory()
    {
        return $this->categoryInterface->getCategory();
    }
}
