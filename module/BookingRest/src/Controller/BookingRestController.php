<?php
namespace BookingRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Db\Adapter\AdapterInterface;
use Booking\Model\Booking;
use Booking\Model\BookingTable;
use Zend\View\Model\JsonModel;

class BookingRestController extends AbstractRestfulController
{
    protected $table;

    public function __construct(BookingTable $table)
    {
        $this->table = $table;
    }

    public function getList()
    {
        $results = $this->table->fetchAll();
        $data = [];

        foreach($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(['data' => $data]);
    }

    public function get($id)
    {
        $booking = $this->table->getBooking($id);

        return new JsonModel(['data' => $booking]);
    }

    public function create($data)
    {
        $booking = new Booking();

        $inputFilter = $booking->getInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel([
                'data' => $inputFilter->getMessages()
            ]);
        }

        // Populate using filtered values
        $data = $inputFilter->getValues();

        $booking->exchangeArray($data);
        $id = $this->table->saveBooking($booking);

        return new JsonModel([
            'data' => $data,
        ]);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;

        $booking = $this->table->getBooking($id);

        $inputFilter = $booking->getInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel([
                'data' => $inputFilter->getMessages()
            ]);
        }

        // Populate using filtered values
        $data = $inputFilter->getValues();

        $booking->exchangeArray($data);
        $id = $this->table->saveBooking($booking);

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function delete($id)
    {
        $this->table->deleteBooking($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
}