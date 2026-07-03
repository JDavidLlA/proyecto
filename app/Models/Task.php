<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    public const PRIORIDADES = [
        'baja',
        'media',
        'alta',
        'urgente',
    ];

    public const PRIORIDADES_DESTACADAS = [
        'alta',
        'urgente',
    ];

    protected $fillable = [
        'project_id',
        'assignee_id',
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'due_date',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function esPrioridadDestacada(): bool
    {
        return in_array($this->prioridad, self::PRIORIDADES_DESTACADAS, true);
    }

    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }
}