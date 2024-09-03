<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['question', 'explanation'];

    protected $cascadeDeletes = ['packages'];

    protected $dates = ['deleted_at'];

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(PackageQuestion::class);
    }
}
