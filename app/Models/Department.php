<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $guarded = ['id'];

    /**
     * Get all of the instructor for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instructor(): HasMany
    {
        return $this->hasMany(Instructor::class);
    }
}
