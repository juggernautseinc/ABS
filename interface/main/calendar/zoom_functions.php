<?php
/**
 *
 *Function to creatw and save zoom call details
 *
 *
 * **/
include_once 'zoom/api.php';
include_once $GLOBALS['vendor_dir'] ."/autoload.php";

function zoom_meeting($eid){
    $args=sqlQuery("select * from openemr_postcalendar_events where pc_eid=".$eid);

    $z=new API();
    $user_data=sqlQuery("select zoom_user_id from users where id=".$args['pc_aid']);
    $user = $user_data['zoom_user_id'];
    //$user = "X9UwyxHaSo-2buiKLqDriw";
        if(!empty($user)){
                $token = $z->zoom_jwt_token();
                $url='users/'.$user.'/meetings';
                $type='GET';
		$params = array();
		$response=$z->curl_Call($url,$type,$params,$token,true);
		$response = json_decode($response);
		sqlQuery("update openemr_postcalendar_events set meeting_link='".$response->meetings[3]->join_url."' where pc_eid=".$eid);
                return true;
          }
          else{

                  Die("Provider Doesn't have Active Zoom Account");
          }
}
//end of zoom meeting

?>

