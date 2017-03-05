<?php

$update = json_decode(file_get_contents('php://input'));
$txt = $update->message->text;
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$channel_forward = $update->channel_post->forward_from;
$channel_text = $update->channel_post->text;
$from = $update->message->from->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$username = $update->message->chat->username;
$msgid = $update->callback_query->message->message_id;


function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

$user = file_get_contents('Member.txt');
$members = explode("\n", $user);
if (!in_array($chat_id, $members)) {
    $add_user = file_get_contents('Member.txt');
    $add_user .= $chat_id . "\n";
    file_put_contents('Member.txt', $add_user);
}

$admin = array("ADMIN ID 1","ADMIN ID 2");
$channel_username1 = "آیدی کانال بدون @";
$channel2 = "دست نزن";

define("TOKEN","توکن ربات");
function bridge($method, $datas=[])
{
    $url = "https://api.telegram.org/bot" . TOKEN . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

$btnch1 = json_encode(['inline_keyboard'=>[
    [['text'=>'کانال جوکدونی😂','url'=>"https://t.me/$channel_username1"]],
    [['text'=>'❤️','callback_data'=>"0"]]
]]);
$btnch2 = json_encode(['inline_keyboard'=>[
    [['text'=>'کانال جوکدونی😂','url'=>'https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA']],
    [['text'=>'❤️','callback_data'=>"0"]]
]]);

if(in_array($chat_id,$admin)) {
    if ($txt == "/start") {
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "پیامتان را بعد از دستورات زیر ارسال کنید
            /send پیام
            /photo [ریپلای]Caption
            /video [reply video]Caption",
            'parse_mode' => "HTML"
        ]);
    } else if (preg_match('/^\/([Ss]end)/', $txt)) {
        $str = str_replace("/send", "", $txt);
        bridge("sendMessage", [
            'chat_id' => $channel2,
            'text' => "#جوکدونی
        " . $str . "
        [🤥جوکدونی](https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA)",
            'parse_mode' => "Markdown",
            'reply_markup' => $btnch2
        ]);
        bridge("sendMessage", [
            'chat_id' => "@" . $channel_username1,
            'text' => "#جوکدونی
        " . $str . "
        [🤥جوکدونی](https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA)",
            'parse_mode' => "Markdown",
            'reply_markup' => $btnch2
        ]);
    } else if (preg_match('/^\/([Pp]hoto)/', $txt)) {
        $a = rand(1, 1000000);
        $b = rand(1, 1000000);
        $strr = str_replace("/photo", "", $txt);
//    $coun2t = count($update->message->reply_to_message->photo)-1;
//    $file_id2 = $update->message->reply_to_message->photo[$coun2t]->file_id;
        $photo = $update->message->reply_to_message->photo;
        $file_id2 = $photo[count($photo) - 1]->file_id;
        $get = bridge('getfile', ['file_id' => $file_id2]);
        $patch = $get->result->file_path;
        file_put_contents("$b.jpg", file_get_contents('https://api.telegram.org/file/bot' . TOKEN . '/' . $patch));

        $stamp = imagecreatefrompng('w.png');
        $im = imagecreatefromjpeg("$b.jpg");
        $save_watermark_photo_address = "$a.jpg";

// Set the margins for the stamp and get the height/width of the stamp image

        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

// Copy the stamp image onto our photo using the margin offsets and the photo
// width to calculate positioning of the stamp.

        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

// Output and free memory
// header('Content-type: image/png');

        imagejpeg($im, $save_watermark_photo_address, 80);
        imagedestroy($im);

        bridge("sendPhoto", [
            'chat_id' => "@" . $channel_username1,
            'photo' => "http://binaam.000webhostapp.com/jock/$a.jpg",
            'caption' => "$strr
            @ch_jockdoni",
            'reply_markup' => json_encode(["inline_keyboard"=>[
                [['text'=>'کانال جوکدونی😂','url'=>'https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA']],
                [['text'=>'متن زیرنویس','callback_data'=>"$strr"]],
                [['text'=>'❤️','callback_data'=>"0"]]
            ]])
        ]);

        unlink("$a.jpg");
        unlink("$b.jpg");
    } else if (preg_match('/^\/([Vv]ideo)/', $txt)) {
        $a = rand(1, 1000000);
        $b = rand(1, 1000000);
        $strr = str_replace("/video", "", $txt);
        $videof = $update->message->video->file_id;

        bridge("sendvideo", [
            'chat_id' => "@" . $channel_username1,
            'video' => $videof,
            'caption' => "$strr
            @ch_jockdoni",
            'reply_markup' => json_encode(["inline_keyboard"=>[
                [['text'=>'کانال جوکدونی😂','url'=>'https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA']],
                [['text'=>'متن زیرنویس','callback_data'=>"$strr"]],
                [['text'=>'❤️','callback_data'=>"0"]]
            ]])
        ]);
    } else if (preg_match('/^\/([Vv]oice)/', $txt)) {
        $strrr = str_replace("/voice", "", $txt);
        $voicef = $update->message->voice->file_id;
        bridge("sendvoice", [
            'chat_id' => "@" . $channel_username1,
            'voice' => $voicef,
            'caption' => "$strrr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);
        bridge("sendvoice", [
            'chat_id' => $channel2,
            'voice' => $voicef,
            'caption' => "$strrr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);
    } else if (preg_match('/^\/([Aa]udio)/', $txt)) {
        $strrrr = str_replace("/audio", "", $txt);
        $audio = $update->message->audio->file_id;
        bridge("sendAudio", [
            'chat_id' => "@" . $channel_username1,
            'audio' => $audio,
            'title' => "@ch_jockdoni",
            'performer' => "@ch_jockdoni",
            'caption' => "$strrrr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);
        bridge("sendAudio", [
            'chat_id' => $channel2,
            'audio' => $audio,
            'title' => "@ch_jockdoni",
            'performer' => "@ch_jockdoni",
            'caption' => "$strrrr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);
    }elseif($update->message->forward_from || $update->message->forward_from_chat){
        $id = $update->message->forward_from->id;
        $id2 = $update->message->forward_from_chat->id;
        if(isset($update->message->forward_from_chat)){
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' =>$id2,
                'parse_mode' => "HTML"
            ]);
        }else{
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' => $id,
                'parse_mode' => "HTML"
            ]);
        }
    } else if (preg_match('/^\/([gG]if)/', $txt)) {
        $strrrrr = str_replace("/gif", "", $txt);
        $thumb = $update->message->document->file_id;
        bridge("sendDocument", [
            'chat_id' => "@" . $channel_username1,
            'document' => $thumb,
            'caption' => "$strrrrr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);
    }
}else{
    if ($txt == "/start") {
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "سلام",
            'parse_mode' => "HTML",
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>'راهنما','callback_data'=>'help']]
            ]])
        ]);
    }elseif($data == "help"){
        $a = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getChatMembersCount?chat_id=@ch_jockdoni"));
        bridge("sendMessage", [
            'chat_id' => $chatid,
            'text' => "با فوروارد پیام از دوستان یا کانال تمامی اطلاعات آن را بگیرید 👍🏻
با ارسال عکس آن را با تصویر جوکدونی واترمارک کنید👍🏻

با ارسال استیکر آن را به عکس تبدیل کنید یا با ارسال عکس آن را به استیکر تبدیل کنید👍🏻👍",
            'parse_mode' => "HTML"
        ]);
    } else if (isset($update->message->photo)) {
        $a = rand(1, 1000000);
        $b = rand(1, 1000000);
//    $coun2t = count($update->message->reply_to_message->photo)-1;
//    $file_id2 = $update->message->reply_to_message->photo[$coun2t]->file_id;
        $photo = $update->message->photo;
        $file_id2 = $photo[count($photo) - 1]->file_id;
        $get = bridge('getfile', ['file_id' => $file_id2]);
        $patch = $get->result->file_path;
        file_put_contents("$b.jpg", file_get_contents('https://api.telegram.org/file/bot' . TOKEN . '/' . $patch));

        $stamp = imagecreatefrompng('w.png');
        $im = imagecreatefromjpeg("$b.jpg");
        $save_watermark_photo_address = "$a.jpg";

// Set the margins for the stamp and get the height/width of the stamp image

        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

// Copy the stamp image onto our photo using the margin offsets and the photo
// width to calculate positioning of the stamp.

        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

// Output and free memory
// header('Content-type: image/png');

        imagejpeg($im, $save_watermark_photo_address, 80);
        imagedestroy($im);

        bridge("sendPhoto", [
            'chat_id' => $chat_id,
            'photo' => "http://binaam.000webhostapp.com/jock/$a.jpg",
            'caption' => "$strr
            @ch_jockdoni",
            'reply_markup' => $btnch2
        ]);

        bridge("sendSticker",[
            'chat_id'=>$chat_id ,
            "sticker"=> "https://binaam.000webhostapp.com/jock/$a.jpg" ,
        ]);

        unlink("$a.jpg");
        unlink("$b.jpg");
    }else if(isset($update->message->sticker)){
            $file = $update->message->sticker->file_id;
            $get = bridge('getfile',[
                'file_id'=>$file
            ]);
            $patch = $get->result->file_path;
            file_put_contents('Sticker.png',file_get_contents('https://api.telegram.org/file/bot'.TOKEN.'/'.$patch));
            bridge("sendPhoto",[
                'chat_id'=>$chat_id ,
                'photo'=>"https://binaam.000webhostapp.com/jock/Sticker.png"
            ]);
    }elseif($update->message->forward_from || $update->message->forward_from_chat){
        $id = $update->message->forward_from->id;
        $id2 = $update->message->forward_from_chat->id;
        if(isset($update->message->forward_from_chat)){
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' =>"ID : ".$id2."\nName : ".$update->message->forward_from_chat->first_name,
                'parse_mode' => "HTML"
            ]);
        }else{
            bridge("sendMessage", [
                'chat_id' => $chat_id,
                'text' =>"ID : ".$id."\nName : ".$update->message->forward_from->first_name,
                'parse_mode' => "HTML"
            ]);
        }
    }else{
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "کانال جوکدونی کانال خنده و شادی
            @ch_jockdoni",
            'parse_mode' => "HTML"
        ]);
    }
}

if (isset($update->callback_query)) {

    $chi = $update->callback_query->message->chat->id;
    $msg_id = $update->callback_query->message->message_id;
    $txxxt = $update->callback_query->message->text;
    $txxxt = $update->callback_query->message->caption;
    $l = $update->callback_query->data + 1;

    bridge("editMessageReplyMarkup", [
        'chat_id' => $chi,
        'message_id' => $msg_id,
        'inline_message_id' => $update->callback_query->inline_message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => 'کانال جوکدونی😂', 'url' => 'https://t.me/joinchat/AAAAAEAnWqTICLb9w-I7lA']],
                [['text' => "❤️($l)", 'callback_data' => "$l"]]
            ]
        ])
    ]);
    $le = $update->callback_query->data;
    var_dump(bridge('answerCallbackQuery',[
        'callback_query_id'=>$update->callback_query->id,
        'text'=>$le
    ]));
}
