<?
	$array = array(
		array('username' => 'user1', 'message' => 'message1'),
		array('username' => 'user2', 'message' => 'message1'),
		array('username' => 'user1', 'message' => 'message2'),
		array('username' => 'user2', 'message' => 'message2'),
		array('username' => 'user1', 'message' => 'message3'),
	);
		
	$resp = array();
	
	foreach ($array as $row) {
    	$resp[$row["username"]]['message'][] = $row["message"];
	}
	
	echo json_encode($resp);
?>