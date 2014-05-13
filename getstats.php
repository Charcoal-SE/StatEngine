<?php
include 'base.php';

$url = 'https://api.stackexchange.com/2.1/info';
$data = array("site" => 'space', "key" => "WFiOehV2MzNIFYyUbvtNRg((");
 
$response = (new Curl)->exec($url . '?' . http_build_query($data), [CURLOPT_ENCODING => 'gzip']);
 
// echo $response;

$obj = json_decode($response);
$items = $obj->{'items'}[0];
// print "total comments:";
// print $obj->{'items'}[0]->{"total_comments"};
$timestamp = date("Y:m:d H:i:s");
// var_dump(json_decode($response, true));

$db = PDODatabaseObject();

$stmt = $db->prepare("INSERT INTO `stats` (`total_questions`, `total_unanswered`, `total_accepted`, `total_answers`, `questions_per_minute`, `total_comments`, `total_votes`, `total_badges`, `badges_per_minute`, `total_users`, `new_active_users`, `site`) VALUES(:total_questions,:total_unanswered,:total_accepted,:total_answers,:questions_per_minute,:total_comments,:total_votes,:total_badges,:badges_per_minute,:total_users,:new_active_users,:site)");
$stmt->execute(array(':total_questions' => $items->{'total_questions'}, ':total_unanswered' => $items->{'total_unanswered'}, ':total_accepted' => $items->{'total_accepted'}, ':total_answers' => $items->{'total_answers'}, ':questions_per_minute' => $items->{'questions_per_minute'}, ':total_comments' => $items->{'total_comments'}, ':total_votes' => $items->{'total_votes'}, ':total_badges' => $items->{'total_badges'}, ':badges_per_minute' => $items->{'badges_per_minute'}, ':total_users' => $items->{'total_users'}, ':new_active_users' => $items->{'new_active_users'}, ':site' => 'space'));
$affected_rows = $stmt->rowCount();

echo $affected_rows;
