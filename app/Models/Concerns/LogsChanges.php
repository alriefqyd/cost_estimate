<?php

namespace App\Models\Concerns;

use App\Models\ChangeLog;

trait LogsChanges
{
    public static function bootLogsChanges()
    {
        static::created(function ($model) {
            $model->logCustom('created', 'Record created');
        });

        static::updated(function ($model) {
            $ignored = ['updated_at', 'created_at'];
            foreach ($model->getChanges() as $field => $newValue) {
                if (in_array($field, $ignored, true)) {
                    continue;
                }

                $oldValue = $model->getOriginal($field);
                if ($oldValue == $newValue) {
                    continue;
                }

                $model->logCustom(
                    'updated',
                    "Changed {$field}: " . ($oldValue ?? '—') . ' → ' . ($newValue ?? '—'),
                    $oldValue,
                    $newValue,
                    $field
                );
            }
        });
    }

    public function changeLogs()
    {
        return $this->morphMany(ChangeLog::class, 'loggable')->latest();
    }

    public function logCustom(string $action, string $description, $old = null, $new = null, ?string $field = null)
    {
        return $this->changeLogs()->create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'field'       => $field,
            'old_value'   => $old,
            'new_value'   => $new,
            'description' => $description,
        ]);
    }
}
