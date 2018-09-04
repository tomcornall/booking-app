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
     * @return Zend\Db\ResultSet\ResultSet Object
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Get a booking by ID
     *
     * @param int $id
     * @return Booking\Model\Booking Object
     */
    public function getBooking($id)
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException("Could not find booking with ID $id");
        }

        return $row;
    }

    /**
     * Create or update a booking
     *
     * @param Booking\Model\Booking Object
     * @return int $id - ID of Booking object
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
            $test = $this->tableGateway->lastInsertValue;
            return $this->tableGateway->lastInsertValue;
        }

        if (! $this->getBooking($id)) {
            throw new RuntimeException("Cannot update booking with identifier $id; does not exist");
        }

        $this->tableGateway->update($data, ['id' => $id]);
        return $id;
    }

    /**
     * Delete a booking by ID
     *
     * @param int $id
     * @return bool - True for valid ID found/deleted
     */
    public function deleteBooking($id)
    {
        $affected = $this->tableGateway->delete(['id' => (int) $id]);
        file_put_contents("affectted.txt", print_r($affected, true));
        return $affected > 0 ? true : false;
    }
}