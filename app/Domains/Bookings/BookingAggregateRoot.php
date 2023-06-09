<?php

namespace App\Domains\Bookings;

use App\Domains\Bookings\Commands\AddTicketsCmd;
use App\Domains\Bookings\Commands\CreateBookingCmd;
use App\Domains\Bookings\Commands\CreateInvoiceCmd;
use App\Domains\Bookings\Commands\UpdateInvoiceCmd;
use App\Domains\Bookings\Events\BookingCreatedEvent;
use App\Domains\Bookings\Events\InvoiceCreatedEvent;
use App\Domains\Bookings\Events\InvoiceUpdatedEvent;
use App\Domains\Bookings\Events\TicketAddedEvent;
use Illuminate\Support\Str;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class BookingAggregateRoot extends AggregateRoot
{
    public function createBooking(CreateBookingCmd $command): self
    {
        $this->recordThat(new BookingCreatedEvent(
            bookingUuid: $this->uuid(),
            customerEmail: $command->getUserEmail(),
            customerName: $command->getUserName(),
            customerPhone: $command->getUserPhone(),
            type: $command->getType(),
        ));

        return $this;
    }

    public function addTickets(AddTicketsCmd $command): self
    {
        for ($i = 0; $i < $command->getQuantity(); $i++) {
            try {
                $ticketUuid = $command->getUuids()[$i];
            } catch (\Exception) {
                $ticketUuid = Str::uuid();
            }

            $this->recordThat(new TicketAddedEvent(
                $this->uuid(),
                $ticketUuid,
                $command->getCurrentPrice(),
            ));
        }

        return $this;
    }

    public function createInvoice(CreateInvoiceCmd $command): self
    {
        $this->recordThat(new InvoiceCreatedEvent(
            $this->uuid(),
            $command->getInvoiceUuid(),
            $command->getTotalPrice()
        ));

        return $this;
    }

    public function updateInvoice(UpdateInvoiceCmd $command): self
    {
        $this->recordThat(new InvoiceUpdatedEvent(
            $this->uuid(),
            $command->getInvoiceUuid(),
            $command->getCurrentInvoiceUuid(),
            $command->getNewTotalPrice(),
        ));

        return $this;
    }
}
