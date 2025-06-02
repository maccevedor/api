<?php

namespace App\Models;

use App\Domain\Entities\Plan as PlanEntity;
use App\Domain\ValueObjects\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'monthly_price',
        'user_limit',
    ];

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function toEntity(): PlanEntity
    {
        return new PlanEntity(
            id: $this->id,
            name: $this->name,
            monthlyPrice: new Money($this->monthly_price),
            userLimit: $this->user_limit
        );
    }
}
