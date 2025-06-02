<?php

namespace App\Models;

use App\Domain\Entities\EnterpriseUser as EnterpriseUserEntity;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnterpriseUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function toEntity(): EnterpriseUserEntity
    {
        return new EnterpriseUserEntity(
            name: $this->name,
            email: new Email($this->email),
            password: new Password($this->password),
            company: $this->company ? $this->company->toEntity() : null,
            id: $this->id
        );
    }
}
