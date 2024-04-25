<?php
session_start(); // Start the session
// Include database connection file
include_once 'conn.php';
include_once 'pub.php';

// Function to fetch posts from the database
function fetchPosts($conn) {
    // Prepare SQL statement to fetch posts
    $sql = "SELECT * FROM pub ORDER BY dateCreation DESC"; // Order by date to get the latest posts first
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Check if the form is submitted
if(isset($_POST['poster'])) {
    // Check if the user is logged in
    if(isset($_SESSION['userEmail'])) {
        // Get the user email from the session
        $userEmail = $_SESSION['userEmail'];
        
        // Check if the publication content is provided
        if(isset($_POST['publication_content']) && !empty($_POST['publication_content'])) {
            // Get the publication content from the form
            $postContent = $_POST['publication_content'];

            // Create a new database connection
            $database = new Conn();
            $conn = $database->getConn();

            // Prepare SQL statement to insert the post
            $sql = "INSERT INTO pub (userEmail, content, dateCreation) VALUES (:userEmail, :content, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userEmail', $userEmail);
            $stmt->bindParam(':content', $postContent);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to the discussion page after posting
                header('Location: discuss.php');
                exit();
            } else {
                echo 'Error adding post.';
            }
        } else {
            echo 'Error: Post content is missing.';
        }
    } else {
        echo 'Error: User not logged in.';
    }
}

// Fetch posts from the database
$posts = [];
$database = new Conn();
$conn = $database->getConn();
if($conn) {
    $posts = fetchPosts($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion - PALESTINE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-image: url('back.png');
            background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat; 
            opacity:0.8;
          
           
            

        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .post {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .post-header h3 {
            margin: 0;
            color: #333;
        }

        .post-header span {
            font-size: 12px;
            color: #666;
        }

        .post-content {
            margin-bottom: 10px;
        }

        .post-actions button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .post-actions button:hover {
            background-color: #45a049;
        }

        #publication_content {
            width: 100%;
            height: 100px;
            resize: none;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }


    
</style>

    </style>
</head>
<body>   
    <div class="container">
        <!-- Display existing posts -->
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <h3><?php echo $post['userEmail']; ?></h3>
                        <span><?php echo $post['dateCreation']; ?></span>
                    </div>
                    <div class="post-content">
                        <p><?php echo $post['content']; ?></p>
                    </div>
                    <!-- Add interaction buttons here -->
                    <div class="post-actions">
                        <button onclick="toggleLike(this)">Like</button>
                        <!-- Add more buttons as needed -->
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?> 
        
        <!-- Form to add new post -->
        <h1>Ajouter votre Publication sur PALESTINE</h1>
        <form id="publicationForm" action="discuss.php" method="post">
            <textarea id="publication_content" name="publication_content" placeholder="Enter your publication text here"></textarea>
            <br>
            <input type="submit" class="submit-btn" value="Post" name='poster'>
            <br><br>
        </form>
    </div>
    <script>
    function toggleLike(button) {
        if (button.classList.contains('liked')) {
            button.classList.remove('liked');
        } else {
            button.classList.add('liked');
        }
    }
    function toggleLike(button) {
        button.classList.toggle('liked');
        if (button.classList.contains('liked')) {
            button.style.backgroundColor = 'red'; // Change background color to red when liked
        } else {
            button.style.backgroundColor = '#4CAF50'; // Remove background color when unliked
            button.innerHTML = 'Like'; // Change back to text "Like" when unliked
        }
    }
</script>


</body>
</html>
