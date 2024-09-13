<?php
namespace App\Services;

use App\Models\ApparelType;

class ApparelTypeService{

    public function getAllApparelType()
    {
        return ApparelType::all();
    }
}

?>
