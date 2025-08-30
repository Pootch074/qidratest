<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const TYPE_ADMIN = 1;
    const TYPE_PREASSESS = 2;
    const TYPE_ENCODE = 3;
    const TYPE_ASSESSMENT = 4;
    const TYPE_RELEASE = 5;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'position',
        'user_type',
        'assigned_category',
        'window_id',
        'status',
        'email_verified_at',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['deleted_at']; // Ensure Laravel treats it as a date

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'assigned_category' => 'string',
            'window_id' => 'integer',
            'status' => 'integer',
            'user_type' => 'integer',
        ];
    }


    public static function getUserTypes(): array
    {
        return [
            self::TYPE_ADMIN      => 'Admin',
            self::TYPE_PREASSESS  => 'Preassess',
            self::TYPE_ENCODE     => 'Encode',
            self::TYPE_ASSESSMENT => 'Assessment',
            self::TYPE_RELEASE    => 'Release',
        ];
    }

    public function getUserType(int $i): string
    {
        return self::getUserTypes()[$i] ?? 'Unknown';
    }

    public function getUserTypeName(): string
    {
        return $this->getUserType($this->user_type);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
        ];
    }

    public function getStatus($i)
    {
        switch ($i) {
            case self::STATUS_INACTIVE:
                return 'Inactive';
            case self::STATUS_ACTIVE:
                return 'Active';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePreassess($query)
    {
        return $query->where('user_type', self::TYPE_PREASSESS);
    }

    public function scopeEncoders($query)
    {
        return $query->where('user_type', self::TYPE_ENCODE);
    }

    public function scopeAssessors($query)
    {
        return $query->where('user_type', self::TYPE_ASSESSMENT);
    }

    public function scopeReleases($query)
    {
        return $query->where('user_type', self::TYPE_RELEASE);
    }
}
