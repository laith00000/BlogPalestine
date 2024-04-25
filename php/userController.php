<?php
include_once 'conn.php';
include_once 'userClass.php';

// Start session (if not already started)
session_start();


// Check if the login form has been submitted
/////////////////////// se connecter /////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['connecter'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Create a new database connection
    $database = new Conn();
    $conn = $database->getConn();

    // Create a new User object to handle login
    $user = new User($conn);

    // Check login credentials
    if ($user->connexion($email, $mdp)) {
        // Set session variable
        $_SESSION['userEmail'] = $email; // Set userEmail instead of username
        
        // Debug: Check if session variable is set
        echo "Session userEmail set: " . $_SESSION['userEmail'] . "<br>";
        
        // Redirect to discuss.php
        header("Location: discuss.php");
        exit(); // Terminate script execution after redirection
    } else {
        // Display error message
        echo "Email ou mot de passe incorrects.";
    }
}

// Check if the registration form has been submitted
///////////////inscription////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscrit'])) {
    // Retrieve form data
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Create a new database connection
    $database = new Conn();
    $conn = $database->getConn();

    // Create a new User object to handle registration
    $user = new User($conn);

    // Set user properties with form data
    $user->setNom($nom);
    $user->setPrenom($prenom);
    $user->setEmail($email);
    $user->setMotDePasse($mdp);

    // Check if user already exists
    if ($user->userExists()) {
        // Display error message
        echo "Cet utilisateur existe déjà.";
    } else {
        // User does not exist, attempt to create
        if ($user->create()) {
            // Redirect to discuss.php after successful registration
            $_SESSION['userEmail'] = $email; // Set userEmail instead of username
            echo "Session userEmail set: " . $_SESSION['userEmail'] . "<br>";

        
            header("Location: discuss.php");
            exit(); // Terminate script execution after redirection
        } else {
            // Display error message
            echo "Erreur lors de l'inscription.";
        }
    }
}


?>
