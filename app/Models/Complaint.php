<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    // protected $guarded = [];
    protected $fillable = [
        'type',
        'theConcerned',
        'address',
        'description',
        'image',
        'file',
        'notesForEmployee',
        'status',
        'userId'
    ];

    protected function casts(): array
    {
        return [];
    }
    protected static function booted()
    {
        static::created(function (Complaint $complaint) {
            $complaint->referenceNumber = self::generateReferenceUsingId($complaint->id);
            $complaint->saveQuietly();
        });
    }

    public static function generateReferenceUsingId(int $id): string
    {
        do {
            $random3 = str_pad((string) rand(0, 999), 3, '0', STR_PAD_LEFT);
            $ref = "{$id}-{$random3}";

            $exists = self::where('referenceNumber', $ref)->exists();
        } while ($exists);

        return $ref;
    }
}
