<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id','approved_by','loan_date','due_date','status','notes','approved_at'
    ];
    protected $casts = [
        'loan_date'=>'date','due_date'=>'date'
    ];
    public function user() {
         return $this->belongsTo(User::class); 
    }
    public function details() {
         return $this->hasMany(LoanDetail::class); 
    }
    public function loanReturn() {
         return $this->hasOne(LoanReturn::class); 
    }
}
