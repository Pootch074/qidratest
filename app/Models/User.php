<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const TYPE_SUPERADMIN = 0;
    const TYPE_ADMIN = 1;
    const TYPE_IDSCAN = 2;
    const TYPE_PACD = 3;

    const TYPE_USER = 5;
    const TYPE_DISPLAY = 6;


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
        'section_id',
        'user_type',
        'assigned_category',
        'step_id',
        'window_id',
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
            self::TYPE_SUPERADMIN      => 'Super Admin',
            self::TYPE_ADMIN      => 'Admin',
            self::TYPE_USER    => 'User',
            self::TYPE_PACD    => 'PACD',
            self::TYPE_DISPLAY    => 'Display',
            self::TYPE_IDSCAN    => 'Idscan',
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

    public function scopeAdmins($query)
    {
        return $query->where('user_type', self::TYPE_ADMIN);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('user_type', self::TYPE_SUPERADMIN);
    }


    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function user($query)
    {
        return $query->where('user_type', self::TYPE_USER);
    }
    public function pacd($query)
    {
        return $query->where('user_type', self::TYPE_PACD);
    }
    public function display($query)
    {
        return $query->where('user_type', self::TYPE_DISPLAY);
    }
    public function idscan($query)
    {
        return $query->where('user_type', self::TYPE_IDSCAN);
    }


    public function window()
    {
        return $this->belongsTo(Window::class);
    }
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
