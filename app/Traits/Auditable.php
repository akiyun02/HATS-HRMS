<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::updated(function (Model $model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            $oldValues = [];
            $newValues = [];

            foreach ($dirty as $key => $value) {
                $oldValues[$key] = $model->getOriginal($key);
                $newValues[$key] = $value;
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
            ]);
        });

        static::created(function (Model $model) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'new_values' => $model->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });

        static::deleted(function (Model $model) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $model->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });
    }
}
