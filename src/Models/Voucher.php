<?php

namespace BeyondCode\Vouchers\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'code',
        'discount',
        'data',
        'inspires_at',
        'expires_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'inspires_at',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'collection'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('vouchers.table', 'vouchers');
    }

    /**
     * Get the users who redeemed this voucher.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('vouchers.user_model'), config('vouchers.relation_table'))->withPivot('redeemed_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Check if code is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? Carbon::now()->gte($this->expires_at) : false;
    }

        /**
     * Check if code is inspire.
     *
     * @return bool
     */
    public function isInspire()
    {
        return $this->inspires_at ? Carbon::now()->lte($this->inspires_at) : false;
    }
}