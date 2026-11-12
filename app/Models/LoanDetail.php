<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
   protected $fillable = ['loan_id','tool_id','qty'];
   public function tool() { return $this->belongsTo(Tool::class); }
}
