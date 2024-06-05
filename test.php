<?php
require_once('class/Message.class.php');

$msg = Message::GetMessage(1);

//var_dump($msg);
echo "Nadawca:";
echo $msg->GetSndName();
echo "<br>";
echo "Odbiorca:";
echo $msg->GetRcvName();
echo "<br>";
echo "Temat:";
echo $msg->GetSubject();
echo "<br>";
echo "Treść:";
echo $msg->GetContent();

if(Message::NewMessage(1,2,'dds','asdd')) {
    echo "Wiadomość została wysłana";
} else {
    echo "Błąd - wiadomośc nie została wysłana";
}

$InBOX = Message::GetInbox(1);
echo  "<pre>";
var_dump($InBOX);
?>