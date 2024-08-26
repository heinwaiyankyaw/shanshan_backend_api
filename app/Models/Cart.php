<?php

namespace App\Models;

use App\Models\AhtoneLevel;
use App\Models\CartItem;
use App\Models\Menu;
use App\Models\PaymentType;
use App\Models\SpicyLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "payment_type_id",
        "menu_id",
        "spicy_level_id",
        "ahtone_level_id",
        "remark",
        "table_number",
        "order_no",
        "dine_in_or_percel",
        "sub_total",
        "tax",
        "discount",
        "grand_total",
        "paid_cash",
        "paid_online",
        "refund",
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function spicyLevel()
    {
        return $this->belongsTo(SpicyLevel::class);
    }

    public function ahtoneLevel()
    {
        return $this->belongsTo(AhtoneLevel::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}