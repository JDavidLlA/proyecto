<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
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
        'nombre',
        'descripcion',
        'estado',
        'prioridad',
        'owner_id',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('project_role')
            ->withTimestamps();
    }

    public function esPrioridadDestacada(): bool
    {
        return in_array($this->prioridad, self::PRIORIDADES_DESTACADAS, true);
    }

    public function estaFinalizado(): bool
    {
        return $this->estado === 'finalizado';
    }
}