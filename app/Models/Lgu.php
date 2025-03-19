<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lgu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lgus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'province_id',
        'region_id',
        'lgu_type',
        'office_address',
        'telephone',
        'mobile_number',
        'email_address'
    ];

    protected $dates = ['deleted_at']; // Ensure Laravel treats it as a date
    const TYPE_CITY = 'City';
    const TYPE_MUNICIPALITY = 'Municipality';
    const TYPE_PROVINCIAL = 'Provincial';

    public static function getLguTypes()
    {
        return [
            self::TYPE_CITY => 'City',
            self::TYPE_MUNICIPALITY => 'Municipality',
            self::TYPE_PROVINCIAL => 'Provincial'
        ];
    }

    public function getLguType($n)
    {
        switch($n) {
            case self::TYPE_CITY: return 'City';
            case self::TYPE_MUNICIPALITY: return 'Municipality';
            case self::TYPE_PROVINCIAL: return 'Provincial';
        }
    }

    public function getLguTypeName()
    {
        return self::getLguType($this->lgu_type);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
