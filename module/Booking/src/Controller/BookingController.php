<?php

namespace Booking\Controller;

use Booking\Model\BookingTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Booking\Form\BookingForm;
use Booking\Model\Booking;

class BookingController extends AbstractActionController
{
    private $table;

    public function __construct(BookingTable $table)
    {
        $this->table = $table;
    }

    /**
     * Loads bookings index view
     */
    public function indexAction()
    {
        return new ViewModel([
            'bookings' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Loads booking Add View, or creates a new booking
     */
    public function addAction()
    {
        $form = new BookingForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        // Request is a POST, handle:
        $booking = new Booking();
        $form->setInputFilter($booking->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $booking->exchangeArray($form->getData());
        $this->table->saveBooking($booking);
        return $this->redirect()->toRoute('booking');
    }

    /**
     * Loads bookings edit view, or edits a booking
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('booking', ['action' => 'add']);
        }

        // Retrieve the booking with the specified id. Doing so raises
        // an exception if the booking is not found, which should result
        // in redirecting to the landing page.
        try {
            $booking = $this->table->getBooking($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('booking', ['action' => 'index']);
        }

        $form = new BookingForm();
        $form->bind($booking);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($booking->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveBooking($booking);

        // Redirect to booking list
        return $this->redirect()->toRoute('booking', ['action' => 'index']);
    }

    /**
     * Delete bookings handler
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('booking');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteBooking($id);
            }

            // Redirect to list of bookings
            return $this->redirect()->toRoute('booking');
        }

        return [
            'id'    => $id,
            'booking' => $this->table->getBooking($id),
        ];
    }
}