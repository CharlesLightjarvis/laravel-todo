<?php

namespace CharlesLightjarvis\Todo\Models;

use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $todoable_type
 * @property int $todoable_id
 * @property string $title
 * @property string|null $notes
 * @property TodoStatusEnum $status
 * @property TodoPriorityEnum $priority
 * @property \Illuminate\Support\Carbon|null $due_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $creator_type
 * @property int|null $creator_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model $todoable
 * @property-read \Illuminate\Database\Eloquent\Model|null $creator
 */
class Todo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => TodoStatusEnum::class,
        'priority' => TodoPriorityEnum::class,
    ];

    public function todoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', TodoStatusEnum::PENDING);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', TodoStatusEnum::IN_PROGRESS);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TodoStatusEnum::COMPLETED);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', TodoStatusEnum::CANCELLED);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_at', '<', now())
            ->where('status', '!=', TodoStatusEnum::COMPLETED);
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', TodoPriorityEnum::HIGH);
    }

    /**
     * Scope a query to only include todos due today. Filtre les todos dont la date d'échéance est aujourd'hui (peu importe l'heure).whereDate() compare uniquement la partie date, ignore l'heure.
     */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_at', now()->toDateString());
    }
}
