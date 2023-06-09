<?php

namespace App\Domains\Bookings\Commands;

use App\Domains\Bookings\BookingAggregateRoot;
use App\Domains\Bookings\Services\BookingService;
use Spatie\EventSourcing\Commands\AggregateUuid;
use Spatie\EventSourcing\Commands\HandledBy;

#[HandledBy(BookingAggregateRoot::class)]
class CreateInvoiceCmd
{
    public function __construct(
        #[AggregateUuid] public string $bookingUuid,
        public string $invoiceUuid,
    ) {
    }

    public function getInvoiceUuid()
    {
        return $this->invoiceUuid;
    }

    public function getTotalPrice(): int
    {
        return BookingService::calculateTotalPrice($this->bookingUuid);
    }
}
