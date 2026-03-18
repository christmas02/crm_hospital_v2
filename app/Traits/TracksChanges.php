<?php
namespace App\Traits;

use App\Models\ModelChange;

trait TracksChanges
{
    protected static function bootTracksChanges()
    {
        static::created(function ($model) {
            ModelChange::create([
                'user_id' => auth()->id(),
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'action' => 'created',
                'new_values' => $model->getAttributes(),
            ]);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) return;

            $old = array_intersect_key($model->getOriginal(), $dirty);
            ModelChange::create([
                'user_id' => auth()->id(),
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'action' => 'updated',
                'old_values' => $old,
                'new_values' => $dirty,
            ]);
        });

        static::deleted(function ($model) {
            ModelChange::create([
                'user_id' => auth()->id(),
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'action' => 'deleted',
                'old_values' => $model->getAttributes(),
            ]);
        });
    }
}
