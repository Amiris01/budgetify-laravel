<?php
namespace App\Services;

use App\Models\Apparels;
use Illuminate\Support\Facades\Session;

class ApparelsService{

    public function getAllApparels($perPage = 5)
    {
        $userId = session('user_id');
        return Apparels::where('user_id', $userId)
        ->with('apparelType', 'apparelStyle', 'apparelBrand')
        ->paginate($perPage);
    }

    public function create(array $data)
    {
        $userId = Session::get('user_id');
        $data['user_id'] = $userId;
        $formattedData = [];
        foreach ($data as $key => $value) {
            $newKey = preg_replace('/1$/', '', $key);
            $formattedData[$newKey] = $value;
        }

        return Apparels::create($formattedData);
    }

    public function getApparelById($id)
    {
        return Apparels::with('apparelType', 'apparelStyle', 'apparelBrand')->find($id);
    }

    public function update(Apparels $apparel, array $data)
    {
        $apparel->update($data);
        return $apparel;
    }

    public function getDashboardData()
    {
        $userId = session('user_id');

        $totalApparels = Apparels::where('user_id', $userId)->count();
        $expensiveApparels = Apparels::where('price', '>=', 200)
            ->where('user_id', $userId)
            ->count();

        $apparelsThisMonth = Apparels::where('user_id', $userId)
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->count();

        $totalSpending = Apparels::where('user_id', $userId)
            ->sum('price');

        return [
            'totalApparels' => $totalApparels,
            'expensiveApparels' => $expensiveApparels,
            'apparelsThisMonth' => $apparelsThisMonth,
            'totalSpending' => $totalSpending
        ];
    }

}

?>
