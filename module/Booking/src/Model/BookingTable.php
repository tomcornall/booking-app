<?php

namespace Booking\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class BookingTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get a collection of bookings
     *
     * @return Zend\Db\Sql\Select
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Get a booking by ID
     *
     * @param int $id
     * @return
     */
    public function getBooking($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException("Could not find row with identifier $id");
        }

        return $row;
    }

    /**
     * Create or update a booking
     *
     * @param Booking\Model\Booking
     */
    public function saveBooking(Booking $booking)
    {
        $data = [
            'username' => $booking->username,
            'reason' => $booking->reason,
            'start_date' => $booking->start_date,
            'end_date'  => $booking->end_date,
        ];

        $id = (int) $booking->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getBooking($id)) {
            throw new RuntimeException("Cannot update booking with identifier $id; does not exist");
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    /**
     * Delete a booking by ID
     *
     * @param int $id
     */
    public function deleteBooking($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}