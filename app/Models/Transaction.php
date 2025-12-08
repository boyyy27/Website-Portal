<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

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
        'order_id',
        'transaction_id',
        'package_id',
        'package_name',
        'package_price',
        'transaction_status',
        'payment_type',
        'payment_method',
        'transaction_time',
        'settlement_time',
        'gross_amount',
        'currency',
        'fraud_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'midtrans_response',
        'notification_received',
        'notification_count',
        'last_notification_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'package_price' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'last_notification_at' => 'datetime',
        'notification_received' => 'boolean',
        'notification_count' => 'integer',
        'midtrans_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the transaction.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user packages created from this transaction.
     */
    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }

    /**
     * Get the payment logs for this transaction.
     */
    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    /**
     * Scope a query to only include settled transactions.
     */
    public function scopeSettled($query)
    {
        return $query->where('transaction_status', 'settlement');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }
}

