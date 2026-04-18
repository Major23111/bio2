<?php

namespace App\Enums;

// This enum defines all order statuses throughout the order lifecycle.
enum OrderStatus: string
{
    // Order has been placed by the customer but not yet confirmed.
    case Submitted = 'submitted';

    // Order has been cancelled by customer or system.
    case Cancelled = 'cancelled';

    // Order has been completed and shipped.
    case Completed = 'completed';

    // Check if order is active (not cancelled or completed).
    public function isActive(): bool
    {
        return $this === self::Submitted;
    }

    // Check if order is terminal state.
    public function isTerminal(): bool
    {
        return $this === self::Cancelled || $this === self::Completed;
    }
}
