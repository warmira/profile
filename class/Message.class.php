<?php
class Message {
    private int $ID;
    private int $rcvID;
    private string $rcvName;
    private int $sndID;
    private string $sndName;
    private string $subject;
    private string $content;
    private string $timestamp;

    public function __construct(int $ID, int $rcvID, int $sndID, string $subject, string $content, string $timestamp) {
        $this->ID = $ID;
        $this->rcvID = $rcvID;
        $this->sndID = $sndID;
        $this->subject = $subject;
        $this->content = $content;
        $this->timestamp = $timestamp;
        //pobierz nazwę nadawcy i odbiorcy z bazy
        $db = new mysqli("localhost", "root", "", "profile");
        $sql = "SELECT profile.firstName, profile.lastName FROM message 
            LEFT JOIN owner on message.senderID = owner.userID 
            LEFT JOIN profile on profile.ID = owner.profileID 
            WHERE message.ID = ?";
        $q = $db->prepare($sql);
        $q->bind_param("i", $this->rcvID);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        $this->rcvName = $row['firstName'] . " " . $row['lastName'];

        $sql = "SELECT profile.firstName, profile.lastName FROM message 
            LEFT JOIN owner on message.rcvID = owner.userID 
            LEFT JOIN profile on profile.ID = owner.profileID 
            WHERE message.ID = ?";
        $q = $db->prepare($sql);
        $q->bind_param("i", $this->sndID);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        $this->sndName = $row['firstName'] . " " . $row['lastName'];

    }

    public static function NewMessage(int $rcvID, int $sndID, string $subject, string $content) : bool {
        //tworzymy nowy timestamp na podstawie obecjego czasu
        $timestamp = date("Y-m-d h:i:s");
        $db = new mysqli("localhost", "root", "", "profile");
        $sql = "INSERT INTO message (senderID, rcvID, subject, timestamp, content) VALUES (?, ?, ?, ?, ?)";
        $q = $db->prepare($sql);
        $q->bind_param("iisss", $sndID, $rcvID, $subject, $timestamp, $content);
        $result = $q->execute();
        return $result;
    }

    public static function GetMessage(int $id) : Message {
        $db = new mysqli("localhost", "root", "", "profile");
        $sql = "SELECT * FROM message WHERE ID = ?";
        $q = $db->prepare($sql);
        $q->bind_param("i", $id);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        $msg =  new Message($row['ID'], $row['rcvID'], $row['senderID'], $row['subject'], $row ['content'], $row['timestamp']);
        return $msg;
    }

    public static function GetInbox(int $rcvID) : array {
        $db = new mysqli("localhost", "root", "", "profile");
        $sql = "SELECT * FROM message WHERE rcvID = ? ORDER BY timestamp DESC";
        $q = $db->prepare($sql);
        $q->bind_param("i",$rcvID);
        if($q->execute()) {
            $result = $q->get_result();
            $messages = array();

            while($row = $result->fetch_assoc()) {
                $messages[] = new Message($row['ID'], $row['rcvID'], $row['senderID'], $row['subject'], $row ['content'], $row['timestamp']);
            }
            return $messages;
        } else {
            die("ERROR: Błąd przy pobieraniu zawartości skrzynki odbiorczej");
        }
    }

    public function GetSubject() : string {
        return $this->subject;
    }
    
    public function GetContent() : string {
        return $this->content;
    }

    public function GetRcvName() : string {
        return $this->rcvName;
    }

    public function GetSndName() : string {
        return $this->sndName;
    }
}
?>