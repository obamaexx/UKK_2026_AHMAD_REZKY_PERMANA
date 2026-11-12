<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class LoanReturn extends Model
{
    use LogsActivity;

    protected $fillable = ['loan_id','returned_at','condition','late_days','fine','notes'];
    public function loan() { return $this->belongsTo(Loan::class); }
}
