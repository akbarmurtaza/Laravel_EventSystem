<?php
 
class Event
{ 
    public $event_id;
    public $address;
    public $event_desc;
    public $gmt_date_set;
    public $is_ticket_available;
    public $lat;
    public $lon;
    public $ticket_url;
    public $email_address;
    public $contact_no;
    public $title;
    public $user_id;
    public $is_featured;
    public $photo_url;
    public $created_at;
    public $updated_at;
    public $is_deleted;
    
    // constructor
    function __construct() 
    {

    }
 
    // destructor
    function __destruct() 
    {
         
    }
}
 
?>