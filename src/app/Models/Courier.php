<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Http\FormRequest;

class Courier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['courier_type'];

    /**
     * Courier types
     * @var array
     */
    public static $courierTypes = ['foot', 'bike', 'car'];

    /**
     * Adding an entry to the couriers table taking into account the pivot tables
     *
     * @param  array $item
     * @return Courier
     */
    public static function add(array $item): Courier
    {
        $courier = Courier::create($item);
        $courier->regions()->createMany(Helpers::convertingArrayBeforeSavingInDB($item['regions'], 'region'));
        $courier->workingHours()->createMany(Helpers::convertingArrayBeforeSavingInDB($item['working_hours'], 'hour'));
        return $courier;
    }

    /**
     * Updating related tables
     *
     * @param  array $item
     * @return void
     */
    public function updateRelatedTable(array $item): void
    {
        if(!empty($item['regions']))
        {
            $this->regions()->delete();
            $this->regions()
                ->createMany(Helpers::convertingArrayBeforeSavingInDB($item['regions'], 'region'));
        }
        if(!empty($item['working_hours']))
        {
            $this->workingHours()->delete();
            $this->workingHours()
                ->createMany(Helpers::convertingArrayBeforeSavingInDB($item['working_hours'], 'hour'));
        }
    }

    /**
     * Get information about the courier
     *
     * @param int $courierId
     * @return array
     */
    public static function getDataCourier(int $courierId): array
    {
        $courier = Courier::find($courierId);
        $result = $courier->toArray();
        $result['regions'] = array_column($courier->regions()->get()->toArray(), 'region');
        $result['working_hours'] = array_column($courier->workingHours()->get()->toArray(), 'hour');
        return $result;
    }

    /**
     * Relation to the region table
     *
     * @return HasMany
     */
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    /**
     * relation to the working hours table
     *
     * @return HasMany
     */
    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHour::class);
    }
}
