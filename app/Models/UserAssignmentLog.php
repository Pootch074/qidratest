<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignmentLog extends Model
{
    use HasFactory;

    // 1️⃣ Table name (if it doesn’t follow Laravel’s pluralization convention)
    protected $table = 'user_assignments_log';

    // 2️⃣ Mass assignable fields
    protected $fillable = [
        'section_id',
        'admin_id',
        'target_user_id',
        'assignment_id',
        'role_before',
        'role_after',
        'step_before',
        'step_after',
        'window_before',
        'window_after',
        'assigned_category_before',
        'assigned_category_after',
    ];

    // 3️⃣ Cast step/window/roles to string when retrieved (optional, for safety)
    protected $casts = [
        'role_before' => 'string',
        'role_after' => 'string',
        'step_before' => 'string',
        'step_after' => 'string',
        'window_before' => 'string',
        'window_after' => 'string',
        'assigned_category_before' => 'string',
        'assigned_category_after' => 'string',
    ];

    // 4️⃣ Relationships (optional, for convenience)

    // Admin who made the change
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // User whose assignment was changed
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    // Section related to the user
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
