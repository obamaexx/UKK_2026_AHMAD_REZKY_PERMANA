<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                ActivityLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => $event,
                    'subject'     => class_basename($model) . ' #' . $model->getKey(),
                    'description' => class_basename($model) . ' ' . $event,
                ]);
            });
        }
    }
}