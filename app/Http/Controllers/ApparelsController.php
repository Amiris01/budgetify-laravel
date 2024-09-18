<?php

namespace App\Http\Controllers;

use App\Models\Apparels;
use App\Http\Requests\StoreApparelsRequest;
use App\Http\Requests\UpdateApparelsRequest;
use App\Services\ApparelsService;
use App\Services\ApparelTypeService;
use App\Services\BrandsService;
use App\Services\StyleService;
use RealRashid\SweetAlert\Facades\Alert;

class ApparelsController extends Controller
{
    protected $apparelService;
    protected $apparelTypeService;
    protected $brandsService;
    protected $styleService;

    public function __construct(ApparelsService $apparelService, ApparelTypeService $apparelTypeService, BrandsService $brandsService, StyleService $styleService)
    {
        $this->apparelService = $apparelService;
        $this->apparelTypeService = $apparelTypeService;
        $this->brandsService = $brandsService;
        $this->styleService = $styleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apparels = $this->apparelService->getAllApparels();
        $apparelType = $this->apparelTypeService->getAllApparelType();
        $brands = $this->brandsService->getAllBrands();
        $styles = $this->styleService->getAllStyle();
        $data = $this->apparelService->getDashboardData();
        return view('apparels.index', compact('apparels', 'apparelType', 'brands', 'styles', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApparelsRequest $request)
    {
        $data = $request->validated();
        $this->apparelService->create($data);
        return redirect()->route('apparels.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apparels $apparel)
    {
        $apparel = $this->apparelService->getApparelById($apparel->id);
        return $apparel->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apparels $apparels)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApparelsRequest $request, Apparels $apparel)
    {
        $data = $request->validated();
        $this->apparelService->update($apparel, $data);

        return redirect()->route('apparels.index')->with('success', 'Apparel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apparels $apparel)
    {
        $apparel->delete();
        Alert::success('Apparel has been deleted', 'Your apparel has been deleted successfully.');
        return redirect()->route('apparels.index')->with('success', 'Apparel deleted successfully');
    }
}
