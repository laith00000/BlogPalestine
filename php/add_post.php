<?php 
// Start session
session_start();

include_once 'conn.php'; // Include conn.php at the beginning
include_once 'pub.php'; // Include pub.php at the beginning
include_once 'userClass.php';

// Add the post to the database 
if (isset($_POST['publication_content']) && !empty($_POST['publication_content'])) {
    $postContent = $_POST['publication_content'];
    $database = new Conn();
    $conn = $database->getConn();
    
    // Set user email from session
    if(isset($_SESSION['userEmail'])) {
        $userEmail = $_SESSION['userEmail'];
    if(isset($_SESSION['nom'])) {
        $userEmail = $_SESSION['nom'];    
        
        //create a new post
        $post = new Pub($conn); // Correct the class name to Pub
        
        // Set user email
        $post->setEmail($userEmail);
        
        // Set post content
        $post->setContent($postContent);
        
        // Set date creation (assuming it's current time)
        $post->setDateCreation(date("Y-m-d H:i:s"));
        
        // Attempt to create the post
        if ($post->createPublication()) {
            echo 'Post inserted successfully';
        } else {
            echo 'Error adding post.';
        }
    } else {
        echo 'Error: User not logged in.';
    }
} else {
    echo 'Error: Post content is missing.';
}
?>
