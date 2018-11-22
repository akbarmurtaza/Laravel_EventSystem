<?php

class ControllerEvent
{
    private $db;
    private $pdo;

    function __construct() 
    {
        // connecting to database
        $this->db = new DB_Connect();
        $this->pdo = $this->db->connect();
    }
 

    function __destruct() { }
 

    public function updateEvent($itm) 
    {
        $stmt = $this->pdo->prepare('UPDATE tbl_eventfinder_events 

                                        SET address = :address, 
                                            gmt_date_set = :gmt_date_set, 
                                            is_ticket_available = :is_ticket_available, 
                                            lat = :lat, 
                                            lon = :lon, 
                                            ticket_url = :ticket_url, 
                                            email_address = :email_address, 
                                            contact_no = :contact_no, 
                                            title = :title,
                                            user_id = :user_id,
                                            updated_at = :updated_at,
                                            event_desc = :event_desc,
                                            is_featured = :is_featured,
                                            photo_url = :photo_url  

                                        WHERE event_id = :event_id');

        $result = $stmt->execute(
                            array('address' => $itm->address,
                                    'gmt_date_set' => $itm->gmt_date_set,  
                                    'is_ticket_available' => $itm->is_ticket_available,
                                    'lat' => $itm->lat,
                                    'lon' => $itm->lon,
                                    'ticket_url' => $itm->ticket_url,
                                    'email_address' => $itm->email_address,
                                    'contact_no' => $itm->contact_no,
                                    'title' => $itm->title,
                                    'user_id' => $itm->user_id,
                                    'updated_at' => $itm->updated_at,
                                    'event_desc' => $itm->event_desc,
                                    'photo_url' => $itm->photo_url,
                                    'event_id' => $itm->event_id,
                                    'is_featured' => $itm->is_featured  ) );
        
        return $result ? true : false;

    }


    public function deleteEvent($event_id, $is_deleted) 
    {
        $stmt = $this->pdo->prepare('UPDATE tbl_eventfinder_events 
                                        SET is_deleted = :is_deleted 
                                        WHERE event_id = :event_id ');
        
        $result = $stmt->execute(
                            array('event_id' => $event_id, 
                                    'is_deleted' => $is_deleted) );

        return $result ? true : false;
    }


    public function updateEventFeatured($itm) 
    {
        $stmt = $this->pdo->prepare('UPDATE tbl_eventfinder_events 
                                        SET is_featured = :is_featured 
                                        WHERE event_id = :event_id ');
        
        $result = $stmt->execute(
                            array('event_id' => $itm->event_id, 
                                    'is_featured' => $itm->is_featured) );
        
        return $result ? true : false;
    }


    public function insertEvent($itm) 
    {
        $stmt = $this->pdo->prepare('INSERT INTO tbl_eventfinder_events( 
                                        address, 
                                        event_desc, 
                                        gmt_date_set, 
                                        is_ticket_available, 
                                        lat, 
                                        lon, 
                                        ticket_url, 
                                        email_address, 
                                        contact_no, 
                                        title, 
                                        user_id, 
                                        is_featured,
                                        photo_url,
                                        created_at,
                                        updated_at,
                                        is_deleted) 

                                    VALUES(
                                        :address, 
                                        :event_desc, 
                                        :gmt_date_set, 
                                        :is_ticket_available, 
                                        :lat, 
                                        :lon, 
                                        :ticket_url, 
                                        :email_address, 
                                        :contact_no, 
                                        :title, 
                                        :user_id, 
                                        :is_featured,
                                        :photo_url,
                                        :created_at,
                                        :updated_at,
                                        :is_deleted )');
        
        $result = $stmt->execute(
                            array(  'address'               => $itm->address,
                                    'event_desc'            => $itm->event_desc,  
                                    'gmt_date_set'          => $itm->gmt_date_set,
                                    'is_ticket_available'   => $itm->is_ticket_available,
                                    'lat'                   => $itm->lat,
                                    'lon'                   => $itm->lon,
                                    'ticket_url'            => $itm->ticket_url,
                                    'email_address'         => $itm->email_address,
                                    'contact_no'            => $itm->contact_no,
                                    'title'                 => $itm->title,
                                    'user_id'               => $itm->user_id,
                                    'is_featured'           => $itm->is_featured,
                                    'photo_url'             => $itm->photo_url,
                                    'created_at'            => $itm->created_at,
                                    'updated_at'            => $itm->updated_at,
                                    'is_deleted'            => $itm->is_deleted) );
        
        return $result ? true : false;
    }
 

    public function getEvents() 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                        FROM tbl_eventfinder_events 
                                        WHERE is_deleted = 0 ORDER BY title ASC');
        
        $stmt->execute();
        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        return $array;
    }


    public function getEventsBySearchingApproved($search, $is_approved) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                FROM tbl_eventfinder_events 
                                WHERE is_deleted = 0 AND is_approved = :is_approved AND title LIKE :search ORDER BY title ASC');
        
        $stmt->execute( array('search' => '%'.$search.'%', 'is_approved' => $is_approved) );

        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        
        return $array;
    }

    public function getEventsBySearching($search) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                FROM tbl_eventfinder_events 
                                WHERE is_deleted = 0 AND title LIKE :search ORDER BY title ASC');
        
        $stmt->execute( array('search' => '%'.$search.'%'));

        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        
        return $array;
    }


    public function getEventByEventId($event_id) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                FROM tbl_eventfinder_events 
                                WHERE event_id = :event_id');
        
        $stmt->execute( array('event_id' => $event_id));
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            return $itm;
        } 
        
        return null;
    }


    public function getEventsFeatured() 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                FROM tbl_eventfinder_events 
                                WHERE is_featured = 1 AND is_deleted = 0 ORDER BY title ASC');
        
        $stmt->execute();
        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        return $array;
    }


    public function getLastInsertedId() {

        return $this->pdo->lastInsertId(); 
    }


    public function getEventsAtRange($begin, $end) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                        FROM tbl_eventfinder_events 
                                        WHERE is_deleted = 0 ORDER BY event_id ASC LIMIT :beg, :end');
        
        $stmt->execute( array('beg' => $begin, 'end' => $end) );
        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        
        return $array;
    }


    function formatEvent($row) {

        $itm = new Event();
        $itm->address                   = $row['address'];
        $itm->event_desc                = $row['event_desc'];
        $itm->gmt_date_set              = $row['gmt_date_set'];
        $itm->is_ticket_available       = $row['is_ticket_available'];
        $itm->lat                       = $row['lat'];
        $itm->lon                       = $row['lon'];
        $itm->ticket_url                = $row['ticket_url'];
        $itm->email_address             = $row['email_address'];
        $itm->contact_no                = $row['contact_no'];
        $itm->title                     = $row['title'];
        $itm->user_id                   = $row['user_id'];
        $itm->is_featured               = $row['is_featured'];
        $itm->photo_url                 = $row['photo_url'];
        $itm->created_at                = $row['created_at'];
        $itm->updated_at                = $row['updated_at'];
        $itm->is_deleted                = $row['is_deleted'];
        $itm->event_id                  = $row['event_id'];

        return $itm;
    }

    public function getFeaturedEvents() {
        $stmt = $this->pdo->prepare('SELECT * 
                                        FROM tbl_eventfinder_events 
                                        WHERE is_deleted = 0 AND is_featured = 1 ORDER BY title ASC');
        
        $stmt->execute();
        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatEvent($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        return $array;
    }
}
 
?>