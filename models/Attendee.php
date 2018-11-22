<?php
 
class Attendee
{ 
    public $attendee_id;
    public $event_id;
    public $is_going;
    public $is_interested;
    public $is_invited;
    public $user_id;
    public $updated_at;
    public $created_at;
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