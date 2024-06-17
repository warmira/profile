<?php
class Answer {
    private int $ID;
    private int $rcvID;
    private string $rcvName;
    private int $sndID;
    private string $sndName;
    private string $subject;
    private string $content;
    private string $timestamp;
    private $db;

    public function __construct(int $ID, int $rcvID, int $sndID, string $subject, string $content, string $timestamp) {
        $this->ID = $ID;
        $this->rcvID = $rcvID;
        $this->sndID = $sndID;
        $this->subject = $subject;
        $this->content = $content;
        $this->timestamp = $timestamp;

        // Establish database connection
        $this->db = new mysqli("localhost", "root", "", "profile");

        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Function to reply to a message
    public function reply(int $rcvID, int $sndID, string $subject, string $content): bool {
        $timestamp = date('Y-m-d H:i:s');

        // Prepare the SQL statement
        $stmt = $this->db->prepare("INSERT INTO messages (rcvID, rcvName, sndID, sndName, subject, content, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Get receiver and sender names
        $rcvName = $this->getUserNameByID($rcvID);
        $sndName = $this->getUserNameByID($sndID);

        if ($stmt) {
            $stmt->bind_param("isiisss", $rcvID, $rcvName, $sndID, $sndName, $subject, $content, $timestamp);

            // Execute the statement
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $this->db->error;
        }

        return false;
    }

    // Helper function to get user name by ID
    private function getUserNameByID(int $userID): string {
        $stmt = $this->db->prepare("SELECT name FROM users WHERE ID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->bind_result($name);
            $stmt->fetch();
            $stmt->close();
            return $name;
        }
        return "";
    }

    // Close the database connection when the object is destroyed
    public function __destruct() {
        $this->db->close();
    }
}
?>
