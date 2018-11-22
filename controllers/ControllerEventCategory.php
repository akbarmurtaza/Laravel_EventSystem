<?php
 
class ControllerEventCategory
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
 
    public function restoreCategoryEvent($category_id, $event_id) 
    {
        $stmt = $this->pdo->prepare('UPDATE  tbl_eventfinder_event_categories 
                                        SET is_deleted = 0
                                        WHERE category_id = :category_id AND event_id = :event_id');


        $result = $stmt->execute(
                            array('category_id' => $category_id, 
                                    'event_id' => $event_id) );
        
        return $result ? true : false;
    }

    public function deleteEventCategoriesByEventId($event_id) 
    {
        $stmt = $this->pdo->prepare('UPDATE  tbl_eventfinder_event_categories 
                                        SET is_deleted = 1 
                                        WHERE event_id = :event_id');


        $result = $stmt->execute( array('event_id' => $event_id) );
        
        return $result ? true : false;
    }

    public function deleteCategoryEvent($category_id, $event_id) 
    {
        $stmt = $this->pdo->prepare('UPDATE  tbl_eventfinder_event_categories 
                                        SET is_deleted = 1 
                                        WHERE category_id = :category_id AND event_id = :event_id');


        $result = $stmt->execute(
                            array('category_id' => $category_id, 
                                    'event_id' => $event_id) );
        
        return $result ? true : false;
    }

    public function insertCategoryEvent($itm) 
    {
        $stmt = $this->pdo->prepare('INSERT INTO tbl_eventfinder_event_categories( 
                                            category_id,
                                            event_id,
                                            created_at,
                                            updated_at ) 
                                        VALUES( 
                                            :category_id,
                                            :event_id,
                                            :created_at,
                                            :updated_at )');
        
        $result = $stmt->execute(
                            array('category_id' => $itm->category_id,
                                    'event_id' => $itm->event_id,
                                    'created_at' => $itm->created_at,
                                    'updated_at' => $itm->updated_at ) );
        
        return $result ? true : false;
    }
 
    public function getCategoryEventByCategoryIdAndEventId($category_id, $event_id) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                        FROM tbl_eventfinder_event_categories 
                                        WHERE category_id = :category_id AND event_id = :event_id');

        $stmt->execute( array('category_id' => $category_id, 'event_id' => $event_id) );

        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            // do something with $row
            $itm = new EventCategory();
            $itm->category_id = $row['category_id'];
            $itm->event_id = $row['event_id'];
            $itm->event_category_id = $row['event_category_id'];
            $itm->created_at = $row['created_at'];
            $itm->updated_at = $row['updated_at'];
            $itm->is_deleted = $row['is_deleted'];
            return $itm;
        }
        return null;
    }
}
 
?>