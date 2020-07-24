<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Constants\DeleteStatus;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function __construct(
        Product $product,
        Sale $sale
    ) {
        $this->product = $product;
        $this->sale = $sale;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $productCount = $this->product->count();
        $userCount = \App\Models\User::count();
        $staffCount = \App\Models\Staff::count();
        $customerCount = \App\Models\Customer::count();
        $categoryCount = \App\Models\Category::count();
        $products = $this->product->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'product-page');
        $sales  = $this->sale->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('id', 'desc');
        // login of staff
        if(\Auth::user()->isRoleStaff()) {
            $staffId = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
            $sales->where('staff_id', $staffId);
        }
        // count total sales
        $salesCount = $sales->count();
        // list sales
        $sales = $sales->paginate(10, ['*'], 'sale-page');
        // login of staff
        $salesCountMonthlyByUser = $this->sale->where('is_delete', '<>', DeleteStatus::DELETED)
            ->whereYear('sale_date', date('Y'))
            ->whereMonth('sale_date', date('m'));
        if(\Auth::user()->isRoleStaff()) {
            $staffId = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
            $salesCountMonthlyByUser->where('staff_id', $staffId);
        }
        $salesCountMonthlyByUser = $salesCountMonthlyByUser->count();

        return view('backends.dashboard', [
            'productCount' => $productCount,
            'products' => $products,
            'userCount' => $userCount,
            'staffCount' => $staffCount,
            'customerCount' => $customerCount,
            'categoryCount' => $categoryCount,
            'sales' => $sales,
            'salesCount' => $salesCount,
            'salesCountMonthlyByUser' => $salesCountMonthlyByUser
        ]);

    }
}
