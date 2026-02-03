<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssignmentLog extends Model
{
    protected $table = 'user_assignments_log';

    protected $fillable = [
        'section_id',
        'admin_id',
        'target_user_id',
        'role_before',
        'role_after',
        'assignment_id',
    ];

    // Relationships (optional)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
