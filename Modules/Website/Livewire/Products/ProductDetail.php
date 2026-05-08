<?php

namespace Modules\Website\Livewire\Products;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Modules\Website\Models\WpProduct;
use Modules\Website\Models\Cart;
use Modules\Website\Models\CartItem;
use Modules\Website\Models\Review;
use Illuminate\Http\Request;

class ProductDetail extends Component
{
    public $product;
    public $reviews;
    public $quantity = 1;
    public $affiliateLink; // Link để người dùng mang đi chia sẻ

    public function mount($slug, Request $request)
    {
        // 1. Lấy thông tin sản phẩm
        $this->product = WpProduct::with(['categories', 'user']) // Eager load user nếu cần
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 2. Xử lý Logic Affiliate (Người mua click vào link giới thiệu)
        if ($request->has('ref')) {
            // Lưu mã người giới thiệu vào Session trong 30 ngày (hoặc cookie)
            Session::put('affiliate_ref', $request->get('ref'));
        }

        // 3. Tạo link Affiliate cho người đang xem (để họ mang đi chia sẻ)
        if (auth()->check()) {
            // Nếu đã đăng nhập, gắn thêm ?ref=ID_CUA_HO vào link
            $this->affiliateLink = route('product.detail', ['slug' => $slug, 'ref' => auth()->id()]);
        } else {
            // Nếu chưa đăng nhập, chỉ hiện link gốc
            $this->affiliateLink = route('product.detail', ['slug' => $slug]);
        }

        $this->reviews = Review::where('product_id', $this->product->id)
                       ->where('is_approved', true)
                       ->latest()
                       ->get();
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $sessionId = Session::getId();
        $user = auth()->user();

        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => $user ? $user->id : null]
        );

        $price = $this->product->sale_price > 0 ? $this->product->sale_price : $this->product->regular_price;

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $this->quantity;
            $cartItem->total = $cartItem->quantity * $price;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'price' => $price,
                'quantity' => $this->quantity,
                'total' => $price * $this->quantity,
            ]);
        }

        // Dispatch sự kiện cập nhật giỏ hàng trên Header
        $this->dispatch('cart-updated');

        // Thông báo đẹp dạng Toast
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Đã thêm ' . $this->product->title . ' vào giỏ hàng!'
        ]);
    }

    // Lấy sản phẩm liên quan (Computed Property để tối ưu)
    public function getRelatedProductsProperty()
    {
        return WpProduct::where('id', '!=', $this->product->id)
            ->whereHas('categories', function($q) {
                $q->whereIn('id', $this->product->categories->pluck('id'));
            })
            ->where('is_active', true)
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('Website::livewire.products.product-detail');
    }
}
