<?php

include 'base.php';

$url = 'https://api.stackexchange.com/2.1/info';
$data = array("site" => 'aviation', "key" => "WFiOehV2MzNIFYyUbvtNRg((");
 
$response = (new Curl)->exec($url . '?' . http_build_query($data), [CURLOPT_ENCODING => 'gzip']);
 
// echo $response;

$obj = json_decode($response);
// print "total comments:";
// print $obj->{'items'}[0]->{"total_comments"};
$timestamp = date("Y:m:d H:i:s");
// var_dump(json_decode($response, true));

$db = PDODatabaseObject();

$stmt = $db->prepare("INSERT INTO `stats` (`total_questions`, `total_unanswered`, `total_accepted`, `total_answers`, `questions_per_minute`, `total_comments`, `total_votes`, `total_badges`, `badges_per_minute`, `total_users`, `new_active_users`, `site`) VALUES(:total_questions,:total_unanswered,:total_accepted,:total_answers,:questions_per_minute,:total_comments,:total_votes,:total_badges,:badges_per_minute,:total_users,:new_active_users,:site)");
$stmt->execute(array(':total_questions' => $obj->{'items'}[0]->{'total_questions'}, ':total_unanswered' => $obj->{'items'}[0]->{'total_unanswered'}, ':total_accepted' => $obj->{'items'}[0]->{'total_accepted'}, ':total_answers' => $obj->{'items'}[0]->{'total_answers'}, ':questions_per_minute' => $obj->{'items'}[0]->{'questions_per_minute'}, ':total_comments' => $obj->{'items'}[0]->{'total_comments'}, ':total_votes' => $obj->{'items'}[0]->{'total_votes'}, ':total_badges' => $obj->{'items'}[0]->{'total_badges'}, ':badges_per_minute' => $obj->{'items'}[0]->{'badges_per_minute'}, ':total_users' => $obj->{'items'}[0]->{'total_users'}, ':new_active_users' => $obj->{'items'}[0]->{'new_active_users'}, ':site' => 'aviation'));
$affected_rows = $stmt->rowCount();

echo $affected_rows;

class Curl
{
  protected $info = [];
  
  public function exec($url, $setopt = array(), $post = array())
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    if ( ! empty($post))
    {
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    if ( ! empty($setopt))
    {
      foreach ($setopt as $key => $value)
      {
        curl_setopt($curl, $key, $value);
      }
    }
    $data = curl_exec($curl);
    $this->info = curl_getinfo($curl);
    curl_close($curl);
    return $data;
  }
 
  public function getInfo()
  {
    return $this->info;
  }
}
