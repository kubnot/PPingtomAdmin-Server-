<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Http\Resources\Admin\SubCategoryResource;
use App\Models\ProductSubCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubCategoriesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubCategoryResource(ProductSubCategory::with(['category'])->get());
    }

    public function store(StoreSubCategoryRequest $request)
    {
        $subCategory = ProductSubCategory::create($request->all());

        return (new SubCategoryResource($subCategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SubCategory $subCategory)
    {
        abort_if(Gate::denies('sub_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubCategoryResource($subCategory->load(['category']));
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory->update($request->all());

        return (new SubCategoryResource($subCategory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SubCategory $subCategory)
    {
        abort_if(Gate::denies('sub_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subCategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getProductCategoriesWithSub()
    {
        abort_if(Gate::denies('sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return response()->json(
            [
                'status_code' => 200,
                'message' => 'success',
                'data' => [
                    'categories' => ProductCategory::with(['subcategories'])->get(),
                ]
            ]
        );
    }

}
