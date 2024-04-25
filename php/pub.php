<?php
class Pub {
    private $conn;
    public $userEmail;
    public $content;
    public $dateCreation;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function setEmail($email) {
        $this->userEmail = $email;
    }

    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function createPublication() {
        $sql = "INSERT INTO pub (userEmail, content, dateCreation) VALUES (:userEmail, :content, :dateCreation)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userEmail', $this->userEmail);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':dateCreation', $this->dateCreation);

        if ($stmt->execute()) {
            return true; // Return true on successful insertion
        } else {
            return false; // Return false on failure
        }
    }

    public function readAllPublications() {
        $query = "SELECT * FROM pub";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all publications
        } else {
            return []; // Return an empty array if no publications found
        }
    }

    public function updatePublication($id) {
        $sql = "UPDATE pub SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true; // Return true on successful update
        } else {
            return false; // Return false on failure
        }
    }

    public function deletePublication($id) {
        $sql = "DELETE FROM pub WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true; // Return true on successful deletion
        } else {
            return false; // Return false on failure
        }
    }
}

?>