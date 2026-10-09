<?php

// app/Domain/Socle/Models/BaseModel.php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            // journalisation via observer
            app(\App\Domain\Socle\Services\JournalService::class)
                ->log('create', $model, auth()->user()?->id, request()->ip());
        });
        static::updating(function ($model) {
            app(\App\Domain\Socle\Services\JournalService::class)
                ->log('update', $model, auth()->user()?->id, request()->ip());
        });
    }
}