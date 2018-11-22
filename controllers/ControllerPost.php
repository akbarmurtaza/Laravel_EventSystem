<?php

class ControllerPost
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
 
    public function deletePost($post_id, $is_deleted) 
    {
        $stmt = $this->pdo->prepare('UPDATE tbl_eventfinder_posts 
                                        SET is_deleted = :is_deleted 
                                        WHERE post_id = :post_id ');
        
        $result = $stmt->execute(
                            array('post_id' => $post_id, 
                                    'is_deleted' => $is_deleted) );

        return $result ? true : false;
    }


    public function getPostsByEventId($event_id) 
    {
        $stmt = $this->pdo->prepare('SELECT tbl_eventfinder_posts.post, 
                                            tbl_eventfinder_posts.gmt_date_added,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_posts.event_id,
                                            tbl_eventfinder_posts.user_id,
                                            tbl_eventfinder_posts.created_at,
                                            tbl_eventfinder_posts.updated_at,
                                            tbl_eventfinder_posts.is_deleted,
                                            tbl_eventfinder_posts.post_id,
                                            tbl_eventfinder_posts.event_id  

                                        FROM tbl_eventfinder_posts 
                                        INNER JOIN tbl_eventfinder_users 
                                        ON tbl_eventfinder_posts.user_id = tbl_eventfinder_users.user_id
                                        WHERE tbl_eventfinder_posts.is_deleted = 0 AND tbl_eventfinder_posts.event_id = :event_id ORDER BY tbl_eventfinder_posts.updated_at DESC');
        
        $stmt->execute( array('event_id' => $event_id) );
        $array = array();
        $ind = 0;
        foreach ($stmt as $row) 
        {
            $itm = $this->formatPost($row);
            $array[$ind] = $itm;
            $ind++;
        } 
        return $array;
    }

    function formatPost($row) {

        $itm = new Post();
        $itm->post_id                   = $row['post_id'];
        $itm->event_id                  = $row['event_id'];
        $itm->post                      = $row['post'];
        $itm->user_id                   = $row['user_id'];
        $itm->created_at                = $row['created_at'];
        $itm->updated_at                = $row['updated_at'];
        $itm->is_deleted                = $row['is_deleted'];
        $itm->gmt_date_added            = $row['gmt_date_added'];
        $itm->full_name                 = $row['full_name'];

        return $itm;
    }

    public function insertPost($itm) 
    {
        $stmt = $this->pdo->prepare('INSERT INTO tbl_eventfinder_posts( 
                                            event_id,
                                            post,
                                            user_id,
                                            gmt_date_added,
                                            created_at,
                                            updated_at ) 
                                        VALUES( 
                                            :event_id,
                                            :post,
                                            :user_id,
                                            :gmt_date_added,
                                            :created_at,
                                            :updated_at )');
        
        $result = $stmt->execute(
                            array('event_id' => $itm->event_id,
                                    'post' => $itm->post,
                                    'user_id' => $itm->user_id,
                                    'gmt_date_added' => $itm->gmt_date_added,
                                    'created_at' => $itm->created_at,
                                    'updated_at' => $itm->updated_at ) );
        
        return $result ? true : false;
    }
}
 
?>