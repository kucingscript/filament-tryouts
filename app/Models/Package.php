<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'duration'];

    protected $cascadeDeletes = ['tryouts'];

    protected $dates = ['deleted_at'];

    public function questions(): HasMany
    {
        return $this->hasMany(PackageQuestion::class, 'package_id');
    }

    public function tryouts(): HasMany
    {
        return $this->hasMany(Tryout::class);
    }
}
