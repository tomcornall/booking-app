<?php
namespace BookingRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Db\Adapter\AdapterInterface;
use Booking\Model\Booking;
use Booking\Model\BookingTable;
use Zend\View\Model\JsonModel;
use RuntimeException;

class BookingRestController extends AbstractRestfulController
{
    protected $table;

    /**
     * Constuctor method loads the BookingTable into a
     * private variable
     *
     * @see \BookingRest\Module.php to see how the module
     * config instantiates the BookingRestController with
     * a BookingTable
     */
    public function __construct(BookingTable $table)
    {
        $this->table = $table;
    }

    /**
     * Returns list of Bookings
     *
     * @return JsonModel
     */
    public function getList()
    {
        $results = $this->table->fetchAll();
        $data = [];

        foreach($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(['data' => $data]);
    }

    /**
     * Returns a single Booking by ID
     *
     * @param int $id ID of the booking
     * @return JsonModel
     */
    public function get($id)
    {
        try {
            $booking = $this->table->getBooking($id);
        } catch (RuntimeException $e) {
            return new JsonModel(['data' => $e->getMessage()]);
        }

        return new JsonModel(['data' => $booking]);
    }

    /**
     * Creates a Booking
     *
     * @param array $data Request data
     * @return JsonModel
     */
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

        // Update object to get newly created ID value
        try {
            $booking = $this->table->getBooking($id);
        } catch (RuntimeException $e) {
            return new JsonModel(['data' => $e->getMessage()]);
        }

        return new JsonModel([
            'data' => $booking->getArrayCopy(),
        ]);
    }

    /**
     * Updates a Booking
     *
     * @param int $id Booking ID
     * @param array $data Request data
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $data['id'] = $id;

        try {
            $booking = $this->table->getBooking($id);
        } catch (RuntimeException $e) {
            return new JsonModel(['data' => $e->getMessage()]);
        }

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

    /**
     * Deletes a Booking
     *
     * @param int $id Booking ID
     * @return JsonModel
     */
    public function delete($id)
    {
        $deleted = $this->table->deleteBooking($id);

        if ($deleted) {
            return new JsonModel([
                'data' => 'deleted',
            ]);
        } else {
            return new JsonModel([
                'data' => "ID $id not found",
            ]);
        }
    }
}