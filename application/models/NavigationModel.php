<?php

class NavigationModel extends CI_Model
{

    public function getBooking($booking_id)
    {
        return $this->db->where('booking_id', $booking_id)
            ->get('park_booking')
            ->row();
    }

}
?>