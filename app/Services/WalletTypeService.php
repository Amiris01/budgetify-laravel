<?php
namespace App\Services;

use App\Models\WalletType;

class WalletTypeService{

    public function getAllWalletType()
    {
        return WalletType::all();
    }
}

?>
