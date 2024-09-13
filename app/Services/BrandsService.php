<?php
namespace App\Services;

use App\Models\Brands;

class BrandsService{

    public function getAllBrands()
    {
        return Brands::all();
    }
}

?>
