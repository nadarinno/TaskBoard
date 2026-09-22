<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'created_by',
        'status_id',
        'title',
        'description',
        'due_date',
        'label',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function team()
    {
        return $this->belongsTo(
            Team::class
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function status()
    {
        return $this->belongsTo(
            Status::class
        );
    }

    public function assignees()
    {
        return $this->belongsToMany(
            User::class
        )->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(
            Comment::class
        );
    }

    public function attachments()
    {
        return $this->hasMany(
            TaskAttachment::class
        );
    }
}