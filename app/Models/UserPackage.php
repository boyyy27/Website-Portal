<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_packages';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'transaction_id',
        'start_date',
        'end_date',
        'is_active',
        'auto_renew',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'auto_renew' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the package.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the user package.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the transaction that created this user package.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid (not expired) packages.
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where('end_date', '>', now());
    }

    /**
     * Check if the package is still valid (not expired).
     */
    public function isValid()
    {
        return $this->is_active && $this->end_date > now();
    }
}

