<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an action
     */
    public static function log($action, $modelType, $modelId = null, $description = null, $changes = null, $tenantId = null)
    {
        if (!Auth::check()) {
            return;
        }

        $tenantId = $tenantId ?? Auth::user()->tenant_id ?? null;

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'description' => $description,
            'tenant_id' => $tenantId,
        ]);
    }

    /**
     * Log model creation
     */
    public static function logCreate($model, $description = null)
    {
        self::log(
            'created',
            class_basename($model),
            $model->id,
            $description ?? "Created " . strtolower(class_basename($model)),
            $model->toArray()
        );
    }

    /**
     * Log model update
     */
    public static function logUpdate($model, $changes, $description = null)
    {
        self::log(
            'updated',
            class_basename($model),
            $model->id,
            $description ?? "Updated " . strtolower(class_basename($model)),
            $changes
        );
    }

    /**
     * Log model deletion
     */
    public static function logDelete($model, $description = null)
    {
        self::log(
            'deleted',
            class_basename($model),
            $model->id,
            $description ?? "Deleted " . strtolower(class_basename($model)),
            $model->toArray()
        );
    }

    /**
     * Log status change
     */
    public static function logStatusChange($model, $oldStatus, $newStatus)
    {
        self::log(
            'status_changed',
            class_basename($model),
            $model->id,
            "Status changed from {$oldStatus} to {$newStatus}",
            ['old_status' => $oldStatus, 'new_status' => $newStatus]
        );
    }

    /**
     * Log password change
     */
    public static function logPasswordChange($user, $description = null)
    {
        self::log(
            'password_changed',
            'User',
            $user->id,
            $description ?? "Password changed for {$user->fullname}",
            []
        );
    }
}
