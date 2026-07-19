<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeInformation extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_profile_id',
        'employee_id',
        'position',
        'assignment',
        'designation',
        'employment_status',
        'salary_grade',
        'step_increment',
        'monthly_salary',
        'employed_in_dar_since',
        'employed_in_government_since',
    ];

    protected function casts(): array
    {
        return [
            'employed_in_dar_since'       => 'date',
            'employed_in_government_since' => 'date',
            'monthly_salary'              => 'decimal:2',
        ];
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
