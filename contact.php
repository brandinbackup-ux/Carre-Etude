<?php
// PHP 8+. Uses the hosting account's local mail transport.
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function reply(int $status, string $message): never {http_response_code($status);echo json_encode(['message'=>$message]);exit;}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') reply(405,'Method not allowed');
if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0)>20000) reply(413,'Request too large');
if (!empty($_POST['website'])) reply(400,'Invalid request');
$origin=parse_url($_SERVER['HTTP_ORIGIN'] ?? '',PHP_URL_HOST);
$host=explode(':',$_SERVER['HTTP_HOST'] ?? '')[0];
if (!$origin || strcasecmp($origin,$host)!==0) reply(403,'Invalid origin');
$fields=[];
foreach (['name','email','company','phone','city','type','stage','message'] as $key) {
 if (isset($_POST[$key]) && !is_string($_POST[$key])) reply(400,'Invalid field');
 $fields[$key]=trim($_POST[$key] ?? '');
 if (strlen($fields[$key])>($key==='message'?8000:300)) reply(400,'Field too long');
}
foreach (['name','email','city','type','message'] as $key) if ($fields[$key]==='') reply(400,'Required field missing');
if (!filter_var($fields['email'],FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/',$fields['email'])) reply(400,'Invalid email');
$key=hash('sha256',__FILE__.($_SERVER['REMOTE_ADDR'] ?? ''));
$handle=fopen(sys_get_temp_dir().'/carre-'.$key,'c+');
if (!$handle || !flock($handle,LOCK_EX)) reply(503,'Please retry');
$last=(int)stream_get_contents($handle);
if(time()-$last<60){flock($handle,LOCK_UN);fclose($handle);reply(429,'Please wait one minute');}
ftruncate($handle,0);rewind($handle);fwrite($handle,(string)time());flock($handle,LOCK_UN);fclose($handle);
$body="Nouvelle demande depuis le site CARRÉ ÉTUDE MAROC\n\n";
foreach($fields as $key=>$value) $body.=$key.': '.$value."\n\n";
$headers=['From'=>'CARRÉ ÉTUDE MAROC <contact@carreetude.com>','Reply-To'=>$fields['email'],'MIME-Version'=>'1.0','Content-Type'=>'text/plain; charset=UTF-8'];
if (!mail('contact@carreetude.com','Demande de projet — CARRÉ ÉTUDE MAROC',$body,$headers)) reply(503,'Mail transport unavailable');
reply(200,'Sent');
