<?php

namespace Modules\Website\Livewire\Cart;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Modules\Website\Services\CartService;
use Modules\Website\Models\CartItem;
use Illuminate\Support\Facades\App;

class CartList extends Component
{
    public $couponCodeInput = '';

    // Inject Service (Laravel 12 style hoặc boot)
    protected function getCartService()
    {
        return App::make(CartService::class);
    } 

    #[Computed]
    public function cartData()
    {
        return $this->getCartService()->getCartSummary();
    }

    public function increment($itemId)
    {
        // 1. Query trực tiếp từ DB để lấy số lượng tươi mới nhất (Tránh lỗi cache Computed)
        $item = CartItem::find($itemId);

        if ($item) {
            try {
                // 2. Gọi Service update
                $this->getCartService()->updateQuantity($itemId, $item->quantity + 1);
                
                // 3. QUAN TRỌNG: Xóa cache computed để View render lại dữ liệu mới
                unset($this->cartData); 

                $this->dispatch('cart-updated');
            } catch (\Exception $e) {
                $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function decrement($itemId)
    {
        // Tương tự increment
        $item = CartItem::find($itemId);

        if ($item && $item->quantity > 1) {
            try {
                $this->getCartService()->updateQuantity($itemId, $item->quantity - 1);
                
                // Xóa cache computed
                unset($this->cartData);

                $this->dispatch('cart-updated');
            } catch (\Exception $e) {
                $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function remove($itemId)
    {
        $this->getCartService()->removeItem($itemId);
        // Xóa cache computed
        unset($this->cartData);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa sản phẩm']);
    }

    public function applyCoupon()
    {
        try {
            $this->getCartService()->applyCoupon($this->couponCodeInput);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Áp dụng mã giảm giá thành công!']);
            $this->couponCodeInput = ''; // Reset input
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function removeCoupon()
    {
         $this->getCartService()->removeCoupon();
         $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã gỡ mã giảm giá']);
    }

    public function render()
    {
        return view('Website::livewire.cart.cart-list');
    }
}
