<?php

namespace App\Domains\Bookings\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketAddedEvent extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public string $uuid,
        public int $currentPrice,
    ) {
    }
}
