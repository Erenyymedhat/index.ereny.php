<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'post_management';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    $conn->query($sql);
}

// Handle post deletion
if (isset($_GET['delete_id'])) {
    $post_id = (int)$_GET['delete_id'];

    $sql = "DELETE FROM posts WHERE post_id = $post_id";
    $conn->query($sql);
}

// Fetch all posts
$sql = "SELECT * FROM posts ORDER BY post_id DESC";
$result = $conn->query($sql);
?><!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .post button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Post Management Page</h1><form method="POST" action="">
    <input type="text" name="title" placeholder="Post Title" required><br>
    <textarea name="content" placeholder="Post Content" required></textarea><br>
    <button type="submit" name="create_post">Create Post</button>
</form>

<h2>Existing Posts</h2>
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="post">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo htmlspecialchars($row['content']); ?></p>
        <a href="?delete_id=<?php echo $row['post_id']; ?>">
            <button>Delete Post</button>
        </a>
    </div>
<?php endwhile; ?>

</body>
</html><?php
// Close connection
$conn->close();
?>