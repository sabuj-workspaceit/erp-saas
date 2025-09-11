<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Project extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'tenant_id'];
}
