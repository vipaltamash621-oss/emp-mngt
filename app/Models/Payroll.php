<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic',
        'house_rent',
        'medical',
        'transport',
        'phone_bill',
        'internet_bill',
        'special',
        'bonus',
        'gross_salary',
        'days_present',
        'days_absent',
        'provident_fund',
        'income_tax',
        'life_insurance',
        'health_insurance',
        'advanced',
        'deduction',
        'net_salary',
    ];
    // Define relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
