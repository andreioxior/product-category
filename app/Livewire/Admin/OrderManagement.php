<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class OrderManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $statusFilter = null;

    public bool $showArchived = false;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public function archiveOrder(Order $order): void
    {
        $order->archived_at = $order->archived_at ? null : now();
        $order->save();
    }

    public function updateOrderStatus(Order $order, string $status): void
    {
        $order->order_status = $status;
        $order->save();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function getOrdersProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Order::query()
            ->with(['items', 'user'])
            ->when($this->search, fn ($query) => $query
                ->where('customer_name', 'ilike', "%{$this->search}%")
                ->orWhere('customer_email', 'ilike', "%{$this->search}%")
                ->orWhere('id', $this->search)
            )
            ->when($this->statusFilter, fn ($query) => $query->where('order_status', $this->statusFilter))
            ->when($this->showArchived, fn ($query) => $query->whereNotNull('archived_at'), fn ($query) => $query->whereNull('archived_at'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }

    public function getStatusOptionsProperty(): array
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function getStatusColorProperty(): array
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.order-management', [
            'orders' => $this->orders,
            'statusOptions' => $this->statusOptions,
            'statusColor' => $this->statusColor,
        ]);
    }
}
