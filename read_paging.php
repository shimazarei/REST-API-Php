<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '/core.php';
include_once '/utilities.php';
include_once '/config.php';
include_once '/node_tree.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and node object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$node = new Node($db);
 
// query nodes
$stmt = $node->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // Nodes array
    $nodes_arr=array();
    $nodes_arr["records"]=array();
    $nodes_arr["paging"]=array();
 
    // retrieve our table contents and fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
 
        $node_item=array(
            "iNode" => $iNode,
            "nodeName" => $nodeName,
            "Number_of_Childnodes" => $node_tree->Number_of_Childnode
                            );
 
        array_push($nodes_arr["records"], $node_item);
    }
 
 
    // include paging
    $total_rows=$product->count();
    $page_url="{$home_url}read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"]=$paging;
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($nodes_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user nodes does not exist
    echo json_encode(
        array("message" => "No nodes found.")
    );
}
?>
