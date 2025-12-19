<?php

namespace App\Services\Website;

use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactMessageService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ContactMessage::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Apply sorting
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }

    public function create(array $data): ContactMessage
    {
        return DB::transaction(function () use ($data) {
            return ContactMessage::create($data);
        });
    }

    public function update(ContactMessage $contactMessage, array $data): ContactMessage
    {
        return DB::transaction(function () use ($contactMessage, $data) {
            $contactMessage->update($data);
            return $contactMessage->fresh();
        });
    }

    public function delete(ContactMessage $contactMessage): bool
    {
        return DB::transaction(function () use ($contactMessage) {
            return $contactMessage->delete();
        });
    }

    public function find(int $id): ?ContactMessage
    {
        return ContactMessage::find($id);
    }

    public function markAsRead(ContactMessage $contactMessage): ContactMessage
    {
        // If you add a 'read_at' or 'status' field later
        // $contactMessage->update(['read_at' => now()]);
        return $contactMessage;
    }
}
