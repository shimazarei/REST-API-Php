<?php
class Node{
 
    private $conn;
    private $table_name = "node_tree";
    
 
    // object properties
    public $iNode;
    public $level;
    public $iLeft;
    public $iRight;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
// read nodes with pagination
public function readPaging($from_record_num, $records_per_page){
 
    // select query
    $query = "SELECT
                nt.nodeName as nodeName, n.iNode, n.level, n.iLeft, n.iRight
            FROM
                " . $this->table_name . " n
                LEFT JOIN
                    node_tree_name nt
                        ON n.iNode = nt.iNode
            LIMIT ?, ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 
    // return values from database
    return $stmt;
}
    
// used for paging nodess
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . ""; 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); 
    return $row['total_rows'];
}    

// search nodes
function search($iNode, $language, $keyword, $page_number, $page_size){
    
    $page_number = 1;
    if(!empty($_GET['page_number'])) {
    $page_number = filter_input(INPUT_GET, 'page_number', FILTER_VALIDATE_INT);
    if(false === $page_number) {
        $page_number = 1;
    }
}

// set the number of items to display per page
    $items_per_page = 1000;

// build query
    $offset = ($page_number - 1) * $items_per_page;
// select all query
    $query = "SELECT
nt.nodeName as nodeName, nt.language as language, n.iNode ad iNode, SUM(n.iLeft + n.iRight) as           Number_of_Childnodes
           FROM
                " . $this->table_name . " n
                LEFT JOIN
                    node_tree_name nt
                        ON n.iNode = nt.iNode
            WHERE
                nt.iNode='".$this->iNode."' AND nt.language='".$this->language."' AND page_number='".$this->page_number."' OR nt.nodeName LIKE ?
                LIMIT $offset, page_size='".$this->page_size."'";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $iNode=htmlspecialchars(strip_tags($iNode));
    $language=htmlspecialchars(strip_tags($language));
    $keyword=htmlspecialchars(strip_tags($keyword));
    $page_number=htmlspecialchars(strip_tags($page_number));
    $page_size=htmlspecialchars(strip_tags($page_size));
    $iNode = "%{$iNode}%";
    $language = "%{$language}%";
    $keywords = "%{$keyword}%";
    $page_number = "%{$page_number}%"
    $page_size = "%{$page_size}%"    
 
    // bind
    $stmt->bindParam(1, $iNode);
    $stmt->bindParam(2, $language);
    $stmt->bindParam(3, $keyword);
    $stmt->bindParam(4, $page_number);
    $stmt->bindParam(5, $page_size);
    
    // execute query
    $stmt->execute();
 
    return $stmt;
}    
}
?>