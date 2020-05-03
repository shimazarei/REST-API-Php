<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '/core.php';
include_once '/config.php';
include_once '/node_tree.php';
 
// instantiate database node object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$node = new Node($db);
 
// get parameters
$iNode=isset($_GET["iNode"]) ? $_GET["iNode"] : "";
$language=isset($_GET["language"]) ? $_GET["language"] : "";
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
$page_number=isset($_GET["page_number"]) ? $_GET["page_number"] : "";
$page_size=isset($_GET["page_size"]) ? $_GET["page_size"] : "";


//ERROR MESSAGES

if(!($iNode && $language)){
   http_response_code(401);
   echo json_encode(
        array("message" => "Missing Mandatory params")
    );
}

if(!($page_number>0)){
   http_response_code(406);
   echo json_encode(
        array("message" => "Invalid page number requested")
    );
}

if(!(0<$page_size<1000)){
   http_response_code(406);
   echo json_encode(
        array("message" => "Invalid page size")
    );
}




// query nodes
$stmt = $node->search($iNode, $language, $keywords, $page_number, $page_size);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // nodes array
    $nodes_arr=array();
    $nodes_arr["records"]=array();
 
    // retrieve our table contents,fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $node_item=array(
            "iNode" => $iNode,
            "nodeName" => $nodeName,
            "Number_of_Childnodes" => $Number_of_Childnodes
                    
        );
 
        array_push($nodes_arr["records"], $node_item);
    }
 
    // set response code - 200 OK
    http_response_code(200)
 
    // show nodes data
    echo json_encode($nodes_arr);
}
 
else{
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
    if($row['iNode']!= $iNode) 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no nodes found
    echo json_encode(
        array("message" => "Invalid Node Id"));  
            
}
}
?>
