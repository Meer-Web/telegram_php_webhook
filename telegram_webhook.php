<?php 
/*
Author: info@meer-web.nl
Web: https://meer-web.nl
Version: 2.0.0

#URL to get the webhook info
https://api.telegram.org/bot<BOT_ID>/getWebhookInfo
# URL to set the webhook
curl -F "url=https://<DOMAIN.TLD>/<YOUR_WEBHOOK_PHP>" https://api.telegram.org/bot<$TELEGRAM_BOT>/setWebhook

# COMMANDS for botfather to take in
help - Show help
ping - Check keepalive
time - Shows the current time.
picture - Post a picture
gif - Post a gif
*/

/********************** Variables to set **********************/
// Set your Bot ID.
$TELEGRAM_BOT='123456789:ABCDEFXXXXXXXXXXXXXXXXXXXXX';

/********************** Start script **********************/
// Telegram function which you can call
function telegram($METHOD) {
        global $TELEGRAM_BOT, $TELEGRAM_CHATID, $MSG, $CAPTION;
        if ($METHOD == 'TXT') { 
                $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendMessage';$data=array('chat_id'=>$TELEGRAM_CHATID,'text'=>$MSG);
        }
        if ($METHOD == 'IMG') {
                if ($CAPTION != '') {
                        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendPhoto';$data=array('chat_id'=>$TELEGRAM_CHATID,'photo'=>$IMG,'caption'=>$CAPTION);
                } else {
                        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendPhoto';$data=array('chat_id'=>$TELEGRAM_CHATID,'photo'=>$IMG);
                }
         }
        if ($METHOD == 'GIF') { 
                if ($CAPTION != '') {
                        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendAnimation';$data=array('chat_id'=>$TELEGRAM_CHATID,'animation'=>$GIF,'caption'=>$CAPTION);
                } else {
                        $url='https://api.telegram.org/bot'.$TELEGRAM_BOT.'/sendAnimation';$data=array('chat_id'=>$TELEGRAM_CHATID,'animation'=>$GIF);
                }
        }
        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
}

$TELEGRAM_API = "https://api.telegram.org/bot$TELEGRAM_BOT";
$TELEGRAM_RCV = json_decode(file_get_contents("php://input"), TRUE);

// ChatID needed to reply in the same chat
$TELEGRAM_CHATID = $TELEGRAM_RCV["message"]["chat"]["id"];

// Fetch the first word, ea for commands
$MESSAGE = explode(" ", strtolower($TELEGRAM_RCV["message"]["text"]));

$MESSAGE_FULL = $TELEGRAM_RCV["message"]["text"];
$MESSAGE_FULL = substr(strstr("$MESSAGE_FULL"," "), 1);

// Get first name of user sending message
$FIRSTNAME = $TELEGRAM_RCV["message"]["from"]["first_name"];
$LASTNAME = $TELEGRAM_RCV["message"]["from"]["last_name"];
$USERID = $TELEGRAM_RCV["message"]["from"]["id"];

// Trim off botname
if(strpos($MESSAGE[0], '@') !== false){
	$COMMAND = explode('@', $MESSAGE[0]);
	$COMMAND = $COMMAND[0];
} else {
	$COMMAND = $MESSAGE[0];
}

// Set empty caption
$CAPTION = '';

// Reply
switch ($MESSAGE[0]) {
        case '/ping':
                // Friendly reply
                $MSG = "Hi $FIRSTNAME!";
                telegram('TXT');
                break;
        case '/time':
                // Send current time
                $MSG = "The current time is " . date('d-m-Y H:i:s');
                telegram('TXT');
                break;
        case '/chatid':
                // Show chatid
                $MSG = "$TELEGRAM_CHATID";
                telegram('TXT');
                break;
	case '/picture':
                // Show an image with caption (or leave caption empty)
                $MSG = "https://www.creativefabrica.com/wp-content/uploads/2022/04/11/Turtle-Graphics-3812807.jpg";
                $CAPTION = 'Here you have a little turtle';
		telegram('IMG');
		break;
        case '/gif':
                // Show a gif with caption (or leave caption empty)
                $MSG = "https://media2.giphy.com/media/LMVkZXubrWzcaZNq10/giphy.gif";
                $CAPTION = 'Here you have a swimming turtle';
                telegram('GIF');
                break;
	}
?>