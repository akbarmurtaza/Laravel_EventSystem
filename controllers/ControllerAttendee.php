<?php
 
class ControllerAttendee
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
 
    // public function updateCategory($itm) 
    // {
    //     $stmt = $this->pdo->prepare('UPDATE tbl_dealfinder_categories 
    //                                     SET category = :category,
    //                                         category_icon =  :category_icon,
    //                                         updated_at = :updated_at

    //                                     WHERE category_id = :category_id');

    //     $result = $stmt->execute(
    //                         array('category' => $itm->category, 
    //                                 'category_icon' => $itm->category_icon, 
    //                                 'updated_at' => $itm->updated_at, 
    //                                 'category_id' => $itm->category_id) );
        
    //     return $result ? true : false;
    // }

    // public function deleteCategory($category_id, $is_deleted) 
    // {

    //     $stmt = $this->pdo->prepare('UPDATE tbl_dealfinder_categories 
    //                                     SET is_deleted = :is_deleted
    //                                     WHERE category_id = :category_id');


    //     $result = $stmt->execute(
    //                         array('is_deleted' => $is_deleted, 
    //                                 'category_id' => $category_id) );
        
    //     return $result ? true : false;
    // }

    public function getAttendeeByEventIdAndUserId($event_id, $user_id) 
    {
        $stmt = $this->pdo->prepare('SELECT * 
                                        FROM tbl_eventfinder_attendees WHERE event_id = :event_id AND user_id = :user_id');

        $stmt->execute( array('event_id' => $event_id, 'user_id' => $user_id) );

        $array = array();
        foreach ($stmt as $row) 
        {
            // do something with $row
            $itm = new Attendee();
            $itm->event_id = $row['event_id'];
            $itm->is_going = $row['is_going'];
            $itm->attendee_id = $row['attendee_id'];
            $itm->is_going = $row['is_going'];
            $itm->user_id = $row['user_id'];
            $itm->created_at = $row['created_at'];
            $itm->updated_at = $row['updated_at'];
            $itm->is_deleted = $row['is_deleted'];

            return $itm;
        }
        return null;
    }

    public function insertAttendee($itm) 
    {
        $stmt = $this->pdo->prepare('INSERT INTO tbl_eventfinder_attendees( 
                                            event_id,
                                            is_going,
                                            user_id,
                                            created_at,
                                            updated_at ) 
                                        VALUES( 
                                            :event_id,
                                            :is_going,
                                            :user_id,
                                            :created_at,
                                            :updated_at )');
        
        $result = $stmt->execute(
                            array('event_id' => $itm->event_id,
                                    'is_going' => $itm->is_going,
                                    'user_id' => $itm->user_id,
                                    'created_at' => $itm->created_at,
                                    'updated_at' => $itm->updated_at ) );
        
        return $result ? true : false;
    }
 
    
    // public function getCategories() 
    // {
    //     $stmt = $this->pdo->prepare('SELECT * 
    //                             FROM tbl_dealfinder_categories 
    //                              WHERE is_deleted = 0 ORDER BY category ASC');

    //     $stmt->execute();

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         // do something with $row
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         $array[$ind] = $itm;
    //         $ind++;
    //     }
    //     return $array;
    // }

    // public function getCategoriesBySearching($search) 
    // {
    //     $stmt = $this->pdo->prepare('SELECT * 
    //                                     FROM tbl_dealfinder_categories 
    //                                     WHERE is_deleted = 0 AND category LIKE :search ORDER BY category ASC');

    //     $stmt->execute( array('search' => '%'.$search.'%'));

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         // do something with $row
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         $array[$ind] = $itm;
    //         $ind++;
    //     }
    //     return $array;
    // }


    // public function getCategoryByCategoryId($category_id) 
    // {
    //     $stmt = $this->pdo->prepare('SELECT * 
    //                                     FROM tbl_dealfinder_categories WHERE category_id = :category_id');

    //     $stmt->execute( array('category_id' => $category_id));

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         // do something with $row
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         return $itm;
    //     }
    //     return null;
    // }


    // public function getCategoriesByCategory($category) 
    // {
    //     $stmt = $this->pdo->prepare('SELECT * 
    //                                     FROM tbl_dealfinder_categories WHERE category = :category');

    //     $stmt->execute( array('category' => $category));

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         // do something with $row
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         return $itm;
    //     }
    //     return null;
    // }

    // public function getCategoriesAtRange($begin, $end) 
    // {
    //     $stmt = $this->pdo->prepare('SELECT * 
    //                                     FROM tbl_dealfinder_categories 
    //                                     WHERE is_deleted = 0 ORDER BY category_id ASC LIMIT :beg, :end');
        
    //     $stmt->execute( array('beg' => $begin, 'end' => $end) );

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         $array[$ind] = $itm;
    //         $ind++;
    //     } 
        
    //     return $array;
    // }

    // public function getCategoriesByDealId($event_id) 
    // {
    //     $stmt = $this->pdo->prepare('SELECT tbl_dealfinder_categories.category_id,
    //                                         tbl_dealfinder_categories.category,
    //                                         tbl_dealfinder_categories.category_icon,
    //                                         tbl_dealfinder_categories.created_at,
    //                                         tbl_dealfinder_categories.updated_at,
    //                                         tbl_dealfinder_categories.is_deleted  
    //                             FROM tbl_dealfinder_categories 
    //                             INNER JOIN tbl_dealfinder_cat_deals_assoc ON
    //                             tbl_dealfinder_categories.category_id = tbl_dealfinder_cat_deals_assoc.category_id 
    //                              WHERE tbl_dealfinder_categories.is_deleted = 0 AND tbl_dealfinder_cat_deals_assoc.is_deleted = 0 AND tbl_dealfinder_cat_deals_assoc.event_id = :event_id ORDER BY category ASC');

    //     $stmt->execute( array('event_id' => $event_id) );

    //     $array = array();
    //     $ind = 0;
    //     foreach ($stmt as $row) 
    //     {
    //         // do something with $row
    //         $itm = new Category();
    //         $itm->category_id = $row['category_id'];
    //         $itm->category = $row['category'];
    //         $itm->category_icon = $row['category_icon'];
    //         $itm->created_at = $row['created_at'];
    //         $itm->updated_at = $row['updated_at'];
    //         $itm->is_deleted = $row['is_deleted'];

    //         $array[$ind] = $itm;
    //         $ind++;
    //     }
    //     return $array;
    // }

}
 
?>