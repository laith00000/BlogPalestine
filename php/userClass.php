<?php
    class User {
        // Connexion à la base de données et nom de la table
        private $conn;
        public $nom;
        public $prenom;
        public $email;
        public $mdp;
    
        public function __construct($db){
            $this->conn = $db;
        }
        
        function setNom($nom) {
            $this->nom = $nom;
        }
        function setPrenom($prenom) {
            $this->prenom = $prenom;
        }
    
        function setEmail($email) {
            $this->email = $email;
        }
    
        function setMotDePasse($mdp) {
            $this->mdp = $mdp;
        }
        public function getUsername() {
            return $this->nom;
        }
    
        function create() {
            $query = "INSERT INTO user (email,nom,prenom, mdp) VALUES (:email,:nom , :prenom, :mdp)";
        
            $stmt = $this->conn->prepare($query);
        
            // Nettoyer les données
            $this->nom=htmlspecialchars(strip_tags($this->nom));
            $this->prenom=htmlspecialchars(strip_tags($this->prenom));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->setMotDePasse($this->mdp);
    
        
            $stmt->bindParam(":nom", $this->nom);
            $stmt->bindParam(":prenom", $this->prenom);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":mdp", $this->mdp);
        
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
    
        // Vérifie si l'email de l'utilisateur existe déjà dans la base de données
        function emailExists() {
            // Requête pour vérifier l'existence de l'email
            $query = "SELECT email FROM user WHERE email = :email";
    
            // Préparation de la requête
            $stmt = $this->conn->prepare($query);
    
            // Nettoyer les données
            $this->email = htmlspecialchars(strip_tags($this->email));
    
            // Bind des valeurs
            $stmt->bindParam(":email", $this->email);
    
            // Exécution de la requête
            $stmt->execute();
    
            // Compter le nombre de lignes
            $num = $stmt->rowCount();
    
            // Si l'email existe déjà, renvoie true
            if ($num > 0) {
                return true;
            }
    
            return false;
        }
    
        // Vérifie si l'utilisateur existe déjà dans la base de données
        function userExists() {
            // Requête pour vérifier l'existence de l'utilisateur
            $query = "SELECT email FROM user WHERE email = :email";
    
            // Préparation de la requête
            $stmt = $this->conn->prepare($query);
    
            // Nettoyer les données
            $this->email = htmlspecialchars(strip_tags($this->email));
    
            // Bind des valeurs
            $stmt->bindParam(":email", $this->email);
    
            // Exécution de la requête
            $stmt->execute();
    
            // Compter le nombre de lignes
            $num = $stmt->rowCount();
    
            // Si l'utilisateur existe déjà, renvoie true
            if ($num > 0) {
                return true;
            }
    
            return false;
        }
    
        // Valider la connexion de l'utilisateur
        function connexion($email, $mdp) {
            // Requête de vérification des identifiants
            $query = "SELECT email FROM user WHERE email = :email AND mdp = :mdp";
    
            // Préparation de la requête
            $stmt = $this->conn->prepare($query);
    
            // Nettoyer les données
            $email = htmlspecialchars(strip_tags($email));
            $mdp = htmlspecialchars(strip_tags($mdp));
    
            // Bind des valeurs
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":mdp", $mdp);
    
            $stmt->execute();
    
            $num = $stmt->rowCount();
    
            if ($num > 0) {
                return true;
            }
    
            return false;
        }
    
        
    }
?>