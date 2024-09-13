<?php
namespace App\Services;

use App\Models\Style;

class StyleService{

    public function getAllStyle()
    {
        return Style::all();
    }
}

?>
