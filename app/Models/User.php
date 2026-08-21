<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdTeams()
    {
        return $this->hasMany(
            Team::class,
            'created_by'
        );
    }

    public function teams()
    {
        return $this->belongsToMany(
            Team::class
        )->withTimestamps();
    }

    public function createdTasks()
    {
        return $this->hasMany(
            Task::class,
            'created_by'
        );
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(
            Task::class
        )->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(
            Comment::class
        );
    }
}