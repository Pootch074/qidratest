<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const TYPE_ADMIN = 1;
    const TYPE_LGU = 2;
    const TYPE_TL = 3;
    const TYPE_RMT = 4;

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
        'password',
        'user_type',
        'status',
        'position',
        'google_id',
        'avatar',
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
        ];
    }

    public static function getUserTypes()
    {
        return [
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_LGU => 'LGU Focal',
            self::TYPE_TL => 'Team Leader',
            self::TYPE_RMT => 'RMT',
        ];
    }

    public function getUserType($i)
    {
        switch($i) {
            case self::TYPE_ADMIN: return 'Admin'; break;
            case self::TYPE_LGU: return 'LGU Focal'; break;
            case self::TYPE_TL: return 'Team Leader'; break;
            case self::TYPE_RMT: return 'RMT'; break;
        }
    }

    public function getUserTypeName()
    {
        return self::getUserType($this->user_type);
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
        switch($i) {
            case self::STATUS_INACTIVE: return 'Inactive'; break;
            case self::STATUS_ACTIVE: return 'Active'; break;
        }
    }

}
