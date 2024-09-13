<?php
namespace App\Services;

use App\Models\FinancialInstitute;

class FinancialInstituteService{

    public function getAllFinancialInstitute()
    {
        return FinancialInstitute::all();
    }
}

?>
