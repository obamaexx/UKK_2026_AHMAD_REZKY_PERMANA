<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description'];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }
}