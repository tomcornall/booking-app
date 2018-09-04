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
     * Loads bookings edit view
     */
    public function editAction()
    {
    }

    /**
     * Loads bookings delete view
     */
    public function deleteAction()
    {
    }
}