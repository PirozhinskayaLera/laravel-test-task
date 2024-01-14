<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DateTime;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['weight', 'region', 'assign_time', 'complete_time'];

    /**
     * Weight restrictions for couriers
     * @var array
     */
    public static $weightRestrictions = [
        'foot' => 10,
        'bike' => 10,
        'car'  => 20
    ];

    /**
     * Adding an entry to the orders table taking into account the pivot tables
     *
     * @param  array $item
     * @return Order
     */
    public static function add(array $item): Order
    {
        $order = Order::create($item);
        $order->deliveryHours()->createMany(Helpers::convertingArrayBeforeSavingInDB($item['delivery_hours'], 'hour'));
        return $order;
    }

    /**
     * An order to be assigned to a courier
     *
     * @param  array $courier
     * @return Order|null
     */
    public static function getOrderForAssignedCourier(array $courier): Order|null
    {
        return Order::whereNull('complete_time')
            ->whereNull('courier_id')
            ->where('weight', '<=', self::$weightRestrictions[$courier['courier_type']])
            ->whereIn('region', $courier['regions'])
            ->whereHas("deliveryHours", function($q) use ($courier){
                $q->whereIn('hour', $courier['working_hours']);
            })
            ->first();
    }

    /**
     * Assign a courier to order
     *
     * @param int $courierId
     * @return void
     */
    public function assignCourierToOrder(int $courierId): void
    {
        $date = new DateTime();
        $this->courier_id = $courierId;
        $this->assign_time = $date->format('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * Relation to the delivery hours table
     *
     * @return HasMany
     */
    public function deliveryHours(): HasMany
    {
        return $this->hasMany(DeliveryHour::class);
    }
}
