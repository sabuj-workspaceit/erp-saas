<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
  protected static function bootBelongsToTenant(): void
  {
    static::creating(function ($model) {
      if (tenant() && empty($model->tenant_id)) {
        $model->tenant_id = tenant()->id;
      }
    });

    static::addGlobalScope('tenant', function (Builder $builder) {
      if (tenant()) {
        $builder->where($builder->getModel()->getTable() . '.tenant_id', tenant()->id);
      }
    });
  }
}
