<?php 
/*
Author: info@meer-web.nl
Web: https://meer-web.nl

#URL to get the webhook info
https://api.telegram.org/bot<BOT_ID>/getWebhookInfo
# URL to set the webhook
curl -F "url=https://<DOMAIN.TLD>/<YOUR_WEBHOOK_PHP>" https://api.telegram.org/bot<$TELEGRAM_BOT>/setWebhook

# COMMANDS for botfather to take in
help - Show help
ping - Check keepalive
time - Shows the current time.
picture - Reply with a picture
*/

// Telegram function for posting text.
function telegram($msg) {
        global $TELEGRAM_BOT,$TELEGRAM_CHATID;
        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendMessage';$data=array('chat_id'=>$TELEGRAM_CHATID,'text'=>$msg);
        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
}

// Telegram function for posting images.
function telegram_pic($msg, $caption) {
        global $TELEGRAM_BOT,$TELEGRAM_CHATID;
        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendPhoto';$data=array('chat_id'=>$TELEGRAM_CHATID,'photo'=>$msg,'caption'=>$caption);
        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
}

// Set your Bot ID and Chat ID.
$TELEGRAM_BOT='123456789:ABCDEFXXXXXXXXXXXXXXXXXXXXX';

$TELEGRAM_API = "https://api.telegram.org/bot$TELEGRAM_BOT";
$TELEGRAM_RCV = json_decode(file_get_contents("php://input"), TRUE);

// ChatID needed to reply in the same chat
$TELEGRAM_CHATID = $TELEGRAM_RCV["message"]["chat"]["id"];

// Fetch the first word, ea for commands
$MESSAGE = explode(" ", strtolower($TELEGRAM_RCV["message"]["text"]));

$MESSAGE_FULL = $TELEGRAM_RCV["message"]["text"];
$MESSAGE_FULL = substr(strstr("$MESSAGE_FULL"," "), 1);

// Trim off botname
if(strpos($MESSAGE[0], '@') !== false){
	$COMMAND = explode('@', $MESSAGE[0]);
	$COMMAND = $COMMAND[0];
} else {
	$COMMAND = $MESSAGE[0];
}

// Reply
switch ($MESSAGE[0]) {
        case '/ping':
                // Friendly reply
                telegram("Hi!");
                break;
        case '/time':
                // Send current time
                $now = date('d-m-Y H:i:s');
                telegram("The current time is $now");
                break;
        case '/chatid':
                // Show chatid
                telegram("$TELEGRAM_CHATID");
                break;
	case '/picture':
                // Reply image (use of different function)
		telegram_pic("URL OF IMAGE","YOUR CAPTION");
		break;
	}
?>
