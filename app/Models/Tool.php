<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use LogsActivity;

    protected $fillable = ['category_id','code','name','stock','condition'];
    public function category() { 
        return $this->belongsTo(Category::class); 
        }
}
