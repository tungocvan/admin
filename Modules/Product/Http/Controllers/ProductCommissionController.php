<?php
namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Product;

class ProductCommissionController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);

        return view('Product::pages.affiliate.product-commissions', [
            'product' => $product
        ]);
    }
}
