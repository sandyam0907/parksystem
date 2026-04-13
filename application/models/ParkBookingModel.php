<?php

class ParkBookingModel extends CI_Model
{

    public function getAll()
    {
        return $this->db->get('park_booking')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('park_booking', $data);
    }

    public function bookingRefExists($ref)
    {
        return $this->db
            ->where('booking_ref', $ref)
            ->count_all_results('park_booking') > 0;
    }

    public function updateslots($id, $data)
    {
        return $this->db
            ->where('park_id', $id)
            ->update('parking', $data);
    }

    public function getBookedSlots($park_id, $booked_date, $start_time, $end_time)
    {
        $this->db->where('park_id', $park_id);
        $this->db->where('booked_date', $booked_date);
        $this->db->where('booking_status', 'Confirmed');
        $this->db->where('booking_status', 'ongoing');
        $this->db->where('start_time <', $end_time);
        $this->db->where('end_time >', $start_time);

        return $this->db->count_all_results('park_booking');
    }

    public function getBookingWithUserDetails($userId)
    {
        return $this->db
            ->select('
            pb.booking_id,
            pb.booking_ref,
            pb.parking_name,
            pb.booked_date,
            pb.start_time,
            pb.end_time,
            pb.parking_price,
            pb.parking_slots,
            pb.parking_duration,
            pb.total_amount,
            pb.booking_status,
            pb.created_at,
            u.name,
            u.email AS user_email
        ')
            ->from('park_booking pb')
            ->join('users u', 'u.id = pb.user_id')
            ->where('pb.user_id', $userId)
            ->order_by('pb.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function getFullBookingDetails($bookingId)
    {
        return $this->db
            ->select('
            pb.*,

            u.name     AS user_name,
            u.email    AS user_email,
            u.mobile    AS user_phone,

            p.parking_name,
            p.parking_location,
            p.area,
            p.pincode,
            p.latitude,
            p.longitude,
            p.parking_price
        ')
            ->from('park_booking pb')
            ->join('users u', 'u.id = pb.user_id', 'left')
            ->join('parking p', 'p.park_id = pb.park_id', 'left')
            ->where('pb.booking_id', $bookingId)
            ->get()
            ->row();
    }


    public function getBookingsByWatchman($watchmanId, $bookeddate, $status)
    {


        return $this->db
            ->select('
            pb.booking_id,
            pb.booking_ref,
            pb.booked_date,
            pb.start_time,
            pb.end_time,
            pb.parking_booking_status,
            pb.total_amount,

            u.id        AS user_id,
            u.name      AS user_name,
            u.email     AS user_email,
            u.mobile     AS user_phone,
            u.photo     AS user_photo,
            u.latitude  AS user_latitude,
            u.longitude AS user_longitude,
            u.status    AS user_status,

            p.parking_name,
            p.parking_price,
            p.parking_slots,
            p.parking_location,
            p.area AS parking_area,
            p.pincode AS parking_pincode,
            p.latitude AS parking_latitude,
            p.longitude AS parking_longitude,
            p.parking_status,

            pb.vehicle_name,
            pb.vehicle_model,
            pb.vehicle_number,
            pb.payment_type,
            pb.payment_status

        ')
            ->from('park_booking pb')
            ->join('users u', 'u.id = pb.user_id', 'left')
            ->join('parking p', 'p.park_id = pb.park_id', 'left')
            ->where('pb.watchman_id', $watchmanId)
            ->where('pb.booked_date', $bookeddate)
            ->where('pb.parking_booking_status', $status)
            ->order_by('pb.start_time', 'ASC')
            ->get()
            ->result();
        //echo $this->db->last_query();exit;
    }


    public function getById($booking_id)
    {
        return $this->db
            ->where('booking_id', $booking_id)
            ->get('park_booking')
            ->row();
    }

    public function getByBookingId($booking_id)
    {
        return $this->db->where('booking_ref', $booking_id)->get('park_booking')->row();
    }

    public function updateById($booking_id, $data)
    {
        $this->db->where('booking_ref', $booking_id);
        return $this->db->update('park_booking', $data);
    }

    public function getByBookingRef($booking_ref)
    {
        return $this->db
            ->where('booking_ref', $booking_ref)
            ->get('park_booking')
            ->row();
    }


    public function updateByBookingRef($booking_ref, $data)
    {
        return $this->db
            ->where('booking_ref', $booking_ref)
            ->update('park_booking', $data);
    }





}
?>