<?php

class ControllerRest {

    private $db;
    private $pdo;
    function __construct() {
        $this->db = new DB_Connect();
        $this->pdo = $this->db->connect();
    }
 
    function __destruct() { }

    public function getNews() 
    {
        $stmt = $this->pdo->prepare(
                            'SELECT * 
                            FROM tbl_eventfinder_news 
                            WHERE tbl_eventfinder_news.is_deleted = 0 
                            ORDER BY created_at DESC');

        $params = array();
        $stmt->execute($params);
        return $stmt;
    }

    public function getEventByEventId($event_id) 
    {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,
                                    COALESCE(0, 0) AS distance 

                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    INNER JOIN tbl_eventfinder_event_categories ON tbl_eventfinder_events.event_id = tbl_eventfinder_event_categories.event_id 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND tbl_eventfinder_events.event_id = :event_id ');

        $params = array();
        $params['event_id'] = $event_id;
        $stmt->execute($params);
        return $stmt;
    }
 
    public function getResultCategoryEvents($radius, $lat, $lon, $category_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    INNER JOIN tbl_eventfinder_event_categories ON tbl_eventfinder_events.event_id = tbl_eventfinder_event_categories.event_id 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND tbl_eventfinder_event_categories.category_id = :category_id 
                                    AND :curr_date <= tbl_eventfinder_events.gmt_date_set AND tbl_eventfinder_event_categories.is_deleted = 0 
                                    GROUP BY tbl_eventfinder_events.event_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $params['category_id'] = $category_id;
        $params['curr_date'] = gmdate('Y-m-d H:i:s', time());
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultEventsByEventId($event_id, $lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND tbl_eventfinder_events.event_id = :event_id 
                                    ORDER BY tbl_eventfinder_events.event_id ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['event_id'] = $event_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultEvents($radius, $lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND :curr_date <= tbl_eventfinder_events.gmt_date_set 
                                    GROUP BY tbl_eventfinder_events.event_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $params['curr_date'] = gmdate('Y-m-d H:i:s', time());
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultFeaturedEvents($radius, $lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND :curr_date <= tbl_eventfinder_events.gmt_date_set AND is_featured = 1 
                                    GROUP BY tbl_eventfinder_events.event_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $params['curr_date'] = gmdate('Y-m-d H:i:s', time());
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultPostsTotalCount($event_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_posts.post,
                                    tbl_eventfinder_posts.gmt_date_added,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url  
                                    FROM tbl_eventfinder_posts 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_posts.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_posts.event_id = :event_id 
                                    ORDER BY tbl_eventfinder_posts.gmt_date_added DESC');

        $params = array();
        $params['event_id'] = $event_id;
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function getResultPosts($min_count, $max_count, $event_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_posts.post,
                                    tbl_eventfinder_posts.gmt_date_added,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url  
                                    FROM tbl_eventfinder_posts 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_posts.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_posts.event_id = :event_id 
                                    ORDER BY tbl_eventfinder_posts.gmt_date_added DESC LIMIT :min_count, :max_count');

        $params = array();
        $params['min_count'] = $min_count;    
        $params['max_count'] = $max_count;   
        $params['event_id'] = $event_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultMyEventsLatLonUser($radius, $lat, $lon, $user_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_eventfinder_users 
                                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                                    WHERE tbl_eventfinder_events.is_deleted = 0 AND tbl_eventfinder_users.user_id = :user_id 
                                    GROUP BY tbl_eventfinder_events.event_id 
                                    HAVING distance <= :radius 
                                    ORDER BY tbl_eventfinder_events.gmt_date_set DESC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $params['user_id'] = $user_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultAllCategories($event_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_categories.category,
                                    tbl_eventfinder_categories.category_icon,
                                    tbl_eventfinder_categories.category_id 
                            FROM tbl_eventfinder_categories 
                            INNER JOIN tbl_eventfinder_event_categories ON tbl_eventfinder_categories.category_id = tbl_eventfinder_event_categories.category_id
                            WHERE tbl_eventfinder_event_categories.is_deleted = 0 
                            AND tbl_eventfinder_event_categories.is_deleted = 0 
                            AND tbl_eventfinder_event_categories.event_id = :event_id');

        $params = array();
        $params['event_id'] = $event_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultCategories() {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_categories.category,
                                    tbl_eventfinder_categories.category_icon,
                                    tbl_eventfinder_categories.category_id 
                            FROM tbl_eventfinder_categories  
                            WHERE tbl_eventfinder_categories.is_deleted = 0');

        $stmt->execute();
        return $stmt;
    }

    public function getResultSearchEvents($radius, $lat, $lon, $keywords, $category_ids) {
        $params = array();
        $sql = 'SELECT tbl_eventfinder_events.event_id,
                        tbl_eventfinder_events.address,
                        tbl_eventfinder_events.event_desc,
                        tbl_eventfinder_events.gmt_date_set,
                        tbl_eventfinder_events.is_ticket_available,
                        tbl_eventfinder_events.photo_url,
                        tbl_eventfinder_events.lat,
                        tbl_eventfinder_events.lon,
                        tbl_eventfinder_events.ticket_url,
                        tbl_eventfinder_events.email_address,
                        tbl_eventfinder_events.contact_no,
                        tbl_eventfinder_events.title,
                        tbl_eventfinder_events.created_at,
                        tbl_eventfinder_events.updated_at,
                        tbl_eventfinder_events.is_deleted,
                        tbl_eventfinder_events.user_id,
                        tbl_eventfinder_users.full_name,
                        tbl_eventfinder_users.thumb_url,
                        tbl_eventfinder_events.is_featured ';

        if($lat != -1 && $lon != -1 && $radius != -1) {
            $sql .= ', COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_eventfinder_events.lat ) ) * 
                            cos( radians( tbl_eventfinder_events.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_eventfinder_events.lat ) ) ) ), 0) AS distance ';
        }

        $sql .= 'FROM tbl_eventfinder_users 
                    INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1)  ';

        if(strlen($category_ids) > 0) {
            $sql .= 'INNER JOIN tbl_eventfinder_event_categories ON tbl_eventfinder_events.event_id = tbl_eventfinder_event_categories.event_id ';
        }

        $sql .= 'WHERE tbl_eventfinder_events.is_deleted = 0 ';
        $sql .= 'AND :curr_date <= tbl_eventfinder_events.gmt_date_set ';

        if(strlen($category_ids) > 0) {
            $arrCategoryIds = explode(',', $category_ids);
            $sql_category = "AND (";
            for($x = 0; $x < count($arrCategoryIds); $x++) {
                $sql_tag = 'category_id_'.$x;

                $sql_category .= 'tbl_eventfinder_event_categories.category_id = :'.$sql_tag.' ';
                $params[$sql_tag] = $arrCategoryIds[$x];

                if($x < count($arrCategoryIds) - 1)
                    $sql_category .= "OR ";
            }

            $sql_category .= ") ";
            $sql .= $sql_category;
        }

        if(strlen($keywords) > 0)
            $sql .= 'AND (tbl_eventfinder_events.title LIKE :keywords OR tbl_eventfinder_events.event_desc LIKE :keywords1 OR tbl_eventfinder_events.address LIKE :keywords2) ';

        $sql .= 'GROUP BY tbl_eventfinder_events.event_id ';

        if($lat != -1 && $lon != -1 && $radius != -1)
            $sql .= 'HAVING distance <= :radius ORDER BY distance ASC';
        else
            $sql .= 'ORDER BY tbl_eventfinder_events.title ASC';

        $stmt = $this->pdo->prepare($sql);

        if($lat != -1 && $lon != -1 && $radius != -1) {
            $params['lat_params'] = $lat;
            $params['lat_params1'] = $lat;   
            $params['lon_params'] = $lon;
            $params['radius'] = $radius;
        }

        if(strlen($keywords) > 0) {
            $params['keywords'] = '%'.$keywords.'%';
            $params['keywords1'] = '%'.$keywords.'%';
            $params['keywords2'] = '%'.$keywords.'%';
        }
        
        $params['curr_date'] = gmdate('Y-m-d H:i:s', time());
        $stmt->execute($params);
        return $stmt;
    }

    public function getLastEventsAttended($min_count, $max_count, $user_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_eventfinder_events.event_id,
                                    tbl_eventfinder_events.address,
                                    tbl_eventfinder_events.event_desc,
                                    tbl_eventfinder_events.gmt_date_set,
                                    tbl_eventfinder_events.is_ticket_available,
                                    tbl_eventfinder_events.photo_url,
                                    tbl_eventfinder_events.lat,
                                    tbl_eventfinder_events.lon,
                                    tbl_eventfinder_events.ticket_url,
                                    tbl_eventfinder_events.email_address,
                                    tbl_eventfinder_events.contact_no,
                                    tbl_eventfinder_events.title,
                                    tbl_eventfinder_events.created_at,
                                    tbl_eventfinder_events.updated_at,
                                    tbl_eventfinder_events.is_deleted,
                                    tbl_eventfinder_events.user_id,
                                    tbl_eventfinder_users.full_name,
                                    tbl_eventfinder_users.thumb_url,
                                    tbl_eventfinder_events.is_featured,
                                    COALESCE(0, 0) AS distance 

                            FROM tbl_eventfinder_users 
                            INNER JOIN tbl_eventfinder_events ON (tbl_eventfinder_users.user_id = tbl_eventfinder_events.user_id OR tbl_eventfinder_events.user_id = -1) 
                            WHERE tbl_eventfinder_events.is_deleted = 0 AND user_id = :user_id 
                            GROUP BY tbl_eventfinder_events.event_id 
                            ORDER BY tbl_eventfinder_events.gmt_date_set DESC LIMIT :min_count, :max_count');

        $params = array();
        $params['min_count'] = $min_count;    
        $params['max_count'] = $max_count;   
        $params['user_id'] = $user_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultUsersForEventTotal($event_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url 
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'event_id' => $event_id) );

        return $stmt->rowCount();
    }

    public function getResultUserIdsForEventWhoJoined($event_id) {
        
        $stmt = $this->pdo->prepare('SELECT tbl_eventfinder_users.user_id 
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 
                                    AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'event_id' => $event_id ) );

        return $stmt;
    }

    public function getResultUserForEvent($min_count, $max_count, $event_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url,
                                            tbl_eventfinder_users.email,
                                            tbl_eventfinder_attendees.attendee_id,
                                            tbl_eventfinder_attendees.is_going,
                                            tbl_eventfinder_attendees.is_interested,
                                            tbl_eventfinder_attendees.is_invited
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC LIMIT :min_count, :max_count');
        
        $stmt->execute( array( 'event_id' => $event_id,
                                'min_count' => $min_count,
                                'max_count' => $max_count ) );

        return $stmt;
    }

    public function getResultUserEventAttendedCount($user_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url,
                                            tbl_eventfinder_users.email,
                                            tbl_eventfinder_attendees.attendee_id,
                                            tbl_eventfinder_attendees.is_going,
                                            tbl_eventfinder_attendees.is_interested,
                                            tbl_eventfinder_attendees.is_invited
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND tbl_eventfinder_users.user_id = :user_id 
                                    AND is_going = 1 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'user_id' => $user_id) );

        return $stmt->rowCount();
    }

    public function getResultUserForEventGoingCount($event_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url,
                                            tbl_eventfinder_users.email,
                                            tbl_eventfinder_attendees.attendee_id,
                                            tbl_eventfinder_attendees.is_going,
                                            tbl_eventfinder_attendees.is_interested,
                                            tbl_eventfinder_attendees.is_invited
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    AND is_going = 1 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'event_id' => $event_id) );

        return $stmt->rowCount();
    }

    public function getResultUserForEventInvitedCount($event_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url,
                                            tbl_eventfinder_users.email,
                                            tbl_eventfinder_attendees.attendee_id,
                                            tbl_eventfinder_attendees.is_going,
                                            tbl_eventfinder_attendees.is_interested,
                                            tbl_eventfinder_attendees.is_invited
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    AND is_invited = 1 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'event_id' => $event_id) );

        return $stmt->rowCount();
    }

    public function getResultUserForEventInterestedCount($event_id) {
        
        $stmt = $this->pdo->prepare('SELECT 
                                            tbl_eventfinder_users.user_id,
                                            tbl_eventfinder_users.full_name,
                                            tbl_eventfinder_users.thumb_url,
                                            tbl_eventfinder_users.email,
                                            tbl_eventfinder_attendees.attendee_id,
                                            tbl_eventfinder_attendees.is_going,
                                            tbl_eventfinder_attendees.is_interested,
                                            tbl_eventfinder_attendees.is_invited
                                            
                                    FROM  tbl_eventfinder_attendees 
                                    INNER JOIN tbl_eventfinder_users ON tbl_eventfinder_attendees.user_id = tbl_eventfinder_users.user_id 
                                    WHERE tbl_eventfinder_attendees.is_deleted = 0 AND tbl_eventfinder_users.deny_access = 0 AND event_id = :event_id 
                                    AND is_interested = 1 
                                    ORDER BY tbl_eventfinder_attendees.created_at DESC');
        
        $stmt->execute( array( 'event_id' => $event_id) );

        return $stmt->rowCount();
    }

    function getArrayJSON($results) {
        $ind = 0;
        $arrayObs = array();
        foreach ($results as $row) {
            $arrayObj = array();
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $arrayObj[$columnName] = "".$val."";
                }
            }
            $arrayObs[$ind] = $arrayObj;
            $ind += 1;
        }
        return $arrayObs;
    }
}
 
?>