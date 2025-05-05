<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'action',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'details',
        'error_message',
        'mks_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the user that performed the import action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related mata kuliah semester.
     */
    public function mataKuliahSemester(): BelongsTo
    {
        return $this->belongsTo(MataKuliahSemester::class, 'mks_id');
    }

    /**
     * Create a log entry for import actions
     *
     * @param string $action submit|preview|confirm|cancel
     * @param array $data Additional data to log
     * @return self
     */
    public static function createLog(string $action, array $data = []): self
    {
        $logData = [
            'action' => $action,
            'user_id' => auth()->id(),
            'status' => $data['status'] ?? 'success',
        ];

        // Add file details if available
        if (isset($data['file_path'])) {
            $logData['file_path'] = $data['file_path'];
            $logData['file_name'] = $data['file_name'] ?? basename($data['file_path']);
            $logData['file_type'] = $data['file_type'] ?? null;
            $logData['file_size'] = $data['file_size'] ?? null;
        }

        // Store MKS details if available
        if (isset($data['mks_id'])) {
            $logData['mks_id'] = $data['mks_id'];
        }

        // Store error message if any
        if (isset($data['error_message'])) {
            $logData['error_message'] = $data['error_message'];
            $logData['status'] = 'failed';
        }

        // Store any additional details
        if (!empty($data['details'])) {
            $logData['details'] = $data['details'];
        }

        return self::create($logData);
    }
}
