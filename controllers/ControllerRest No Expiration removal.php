<?php

class ControllerRest {

    private $db;
    private $pdo;
    function __construct() {
        $this->db = new DB_Connect();
        $this->pdo = $this->db->connect();
    }
 
    function __destruct() { }
 
    public function getResultCategoryDeals($radius, $lat, $lon, $category_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_dealfinder_deals.deal_id,
                                    tbl_dealfinder_deals.title,
                                    tbl_dealfinder_deals.subtitle,
                                    tbl_dealfinder_deals.desc,
                                    tbl_dealfinder_deals.price,
                                    tbl_dealfinder_deals.price_off,
                                    tbl_dealfinder_deals.photo_url,
                                    tbl_dealfinder_deals.gmt_date_added,
                                    tbl_dealfinder_deals.no_of_days_expire,
                                    tbl_dealfinder_deals.is_featured,
                                    tbl_dealfinder_deals.lat,
                                    tbl_dealfinder_deals.lon,
                                    tbl_dealfinder_deals.created_at,
                                    tbl_dealfinder_deals.updated_at,
                                    tbl_dealfinder_deals.user_id,
                                    tbl_dealfinder_deals.is_approved,
                                    tbl_dealfinder_deals.currency_symbol,
                                    tbl_dealfinder_deals.address,

                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_dealfinder_deals 
                                    INNER JOIN tbl_dealfinder_cat_deals_assoc ON tbl_dealfinder_deals.deal_id = tbl_dealfinder_cat_deals_assoc.deal_id 
                                    WHERE tbl_dealfinder_deals.is_deleted = 0 AND tbl_dealfinder_cat_deals_assoc.category_id = :category_id AND is_approved = 1 
                                    GROUP BY tbl_dealfinder_deals.deal_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $params['category_id'] = $category_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultFeaturedDeals($radius, $lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT *, 
                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 AND is_featured = 1 AND is_approved = 1 
                                    GROUP BY tbl_dealfinder_deals.deal_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $stmt->execute($params);
        return $stmt;
    }

    public function getDealByDealId($deal_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT * 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 && deal_id = :deal_id 
                                    ORDER BY title ASC');

        $stmt->execute(array("deal_id" => $deal_id));
        return $stmt;
    }

    public function getResultMyDeals() {
        $stmt = $this->pdo->prepare(
                            'SELECT * 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 AND is_approved = 1 
                                    ORDER BY title ASC');

        $stmt->execute();
        return $stmt;
    }

    public function getResultDeals($radius, $lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT *, 
                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 AND is_approved = 1 
                                    GROUP BY tbl_dealfinder_deals.deal_id 
                                    HAVING distance <= :radius 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['radius'] = $radius;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultMyDealsLatLon($lat, $lon) {
        $stmt = $this->pdo->prepare(
                            'SELECT *,
                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 AND is_approved = 1 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultMyDealsLatLonUser($lat, $lon, $user_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT *,
                            COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance 
                                    FROM tbl_dealfinder_deals 
                                    WHERE is_deleted = 0 AND user_id = :user_id 
                                    ORDER BY distance ASC');

        $params = array();
        $params['lat_params'] = $lat;    
        $params['lat_params1'] = $lat;   
        $params['lon_params'] = $lon;
        $params['user_id'] = $user_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getCategoryByDealId($deal_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_dealfinder_categories.category,
                                    tbl_dealfinder_categories.category_icon,
                                    tbl_dealfinder_categories.category_id 
                            FROM tbl_dealfinder_categories 
                            INNER JOIN tbl_dealfinder_cat_deals_assoc ON tbl_dealfinder_categories.category_id = tbl_dealfinder_cat_deals_assoc.category_id
                            WHERE tbl_dealfinder_cat_deals_assoc.is_deleted = 0 
                            AND tbl_dealfinder_categories.is_deleted = 0 
                            AND tbl_dealfinder_cat_deals_assoc.deal_id = :deal_id');

        $params = array();
        $params['deal_id'] = $deal_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultAllCategories($deal_id) {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_dealfinder_categories.category,
                                    tbl_dealfinder_categories.category_icon,
                                    tbl_dealfinder_categories.category_id 
                            FROM tbl_dealfinder_categories 
                            INNER JOIN tbl_dealfinder_cat_deals_assoc ON tbl_dealfinder_categories.category_id = tbl_dealfinder_cat_deals_assoc.category_id
                            WHERE tbl_dealfinder_cat_deals_assoc.is_deleted = 0 
                            AND tbl_dealfinder_categories.is_deleted = 0 
                            AND tbl_dealfinder_cat_deals_assoc.deal_id = :deal_id');

        $params = array();
        $params['deal_id'] = $deal_id;
        $stmt->execute($params);
        return $stmt;
    }

    public function getResultCategories() {
        $stmt = $this->pdo->prepare(
                            'SELECT tbl_dealfinder_categories.category,
                                    tbl_dealfinder_categories.category_icon,
                                    tbl_dealfinder_categories.category_id 
                            FROM tbl_dealfinder_categories  
                            WHERE tbl_dealfinder_categories.is_deleted = 0');

        $stmt->execute();
        return $stmt;
    }

    public function getResultSearchDeals($radius, $lat, $lon, $keywords, $category_ids) {
        $params = array();
        $sql = 'SELECT tbl_dealfinder_deals.deal_id,
                                    tbl_dealfinder_deals.title,
                                    tbl_dealfinder_deals.subtitle,
                                    tbl_dealfinder_deals.desc,
                                    tbl_dealfinder_deals.price,
                                    tbl_dealfinder_deals.price_off,
                                    tbl_dealfinder_deals.photo_url,
                                    tbl_dealfinder_deals.gmt_date_added,
                                    tbl_dealfinder_deals.no_of_days_expire,
                                    tbl_dealfinder_deals.is_featured,
                                    tbl_dealfinder_deals.lat,
                                    tbl_dealfinder_deals.lon,
                                    tbl_dealfinder_deals.created_at,
                                    tbl_dealfinder_deals.updated_at,
                                    tbl_dealfinder_deals.user_id,
                                    tbl_dealfinder_deals.is_approved,
                                    tbl_dealfinder_deals.currency_symbol,
                                    tbl_dealfinder_deals.address ';

        if($lat != -1 && $lon != -1 && $radius != -1) {
            $sql .= ', COALESCE(( 3959 * acos( cos( radians(:lat_params) ) *  cos( radians( tbl_dealfinder_deals.lat ) ) * 
                            cos( radians( tbl_dealfinder_deals.lon ) - radians(:lon_params) ) + sin( radians(:lat_params1) ) * 
                            sin( radians( tbl_dealfinder_deals.lat ) ) ) ), 0) AS distance ';
        }

        $sql .= 'FROM tbl_dealfinder_deals ';

        if(strlen($category_ids) > 0) {
            $sql .= 'INNER JOIN tbl_dealfinder_cat_deals_assoc ON tbl_dealfinder_deals.deal_id = tbl_dealfinder_cat_deals_assoc.deal_id ';
        }

        $sql .= 'WHERE tbl_dealfinder_deals.is_deleted = 0 AND is_approved = 1 ';

        if(strlen($category_ids) > 0) {
            $arrCategoryIds = explode(',', $category_ids);
            $sql_category = "AND (";
            for($x = 0; $x < count($arrCategoryIds); $x++) {
                $sql_tag = 'category_id_'.$x;

                $sql_category .= 'tbl_dealfinder_cat_deals_assoc.category_id = :'.$sql_tag.' ';
                $params[$sql_tag] = $arrCategoryIds[$x];

                if($x < count($arrCategoryIds) - 1)
                    $sql_category .= "OR ";
            }

            $sql_category .= ") ";
            $sql .= $sql_category;
        }

        if(strlen($keywords) > 0)
            $sql .= 'AND (tbl_dealfinder_deals.title LIKE :keywords OR tbl_dealfinder_deals.subtitle LIKE :keywords1 OR tbl_dealfinder_deals.desc LIKE :keywords2 OR tbl_dealfinder_deals.address LIKE :keywords3) ';

        $sql .= 'GROUP BY tbl_dealfinder_deals.deal_id ';

        if($lat != -1 && $lon != -1 && $radius != -1)
            $sql .= 'HAVING distance <= :radius ORDER BY distance ASC';
        else
            $sql .= 'ORDER BY tbl_dealfinder_deals.title ASC';

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
            $params['keywords3'] = '%'.$keywords.'%';
        }
        
        $stmt->execute($params);
        return $stmt;
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