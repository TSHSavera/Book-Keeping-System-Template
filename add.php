<?php
// Submit the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the database connection file
    include 'db.php';

    // Prepare the SQL query
    $stmt = $conn->prepare('INSERT INTO booksdb (title, author, publisher, descr, category, date_published, isbn, img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

    // Prepare the data to be inserted
    // For the image, we will store the file name
    $imageData = $_FILES['image']['name'];
    $imagePath = 'uploads/' . $imageData;

    // For the date_published, we will convert the date to the format YYYY-MM-DD
    $datePublished = date('Y-m-d', strtotime($_POST['date_published']));

    // TODO: Add checks for other fields

    $stmt->bind_param('ssssssss', $_POST['title'], $_POST['author'], $_POST['publisher'], $_POST['description'], $_POST['category'], $_POST['date_published'], $_POST['isbn'], $imageData);

    // Check if the image is an image
    if (getimagesize($_FILES['image']['tmp_name']) === false) {
        // On page redirect, popup a message and redirect to the add page
        echo '<script>alert("Please upload an image"); window.location.href = "add.php";</script>';
    }

    // Check if the image is a valid image
    $imageFileType = strtolower(pathinfo($imageData, PATHINFO_EXTENSION));
    if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg' && $imageFileType !== 'gif') {
        // On page redirect, popup a message and redirect to the add page
        echo '<script>alert("Please upload a valid image"); window.location.href = "add.php";</script>';
    }

    // Check if the image already exists
    if (file_exists('uploads/' . $imageData)) {
        // On page redirect, popup a message and redirect to the add page
        echo '<script>alert("Image already exists"); window.location.href = "add.php";</script>';
    }


    // Execute the query
    if ($stmt->execute()) {
        
        // Check if the image was uploaded successfully
        if (!move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageData)) {
            echo '<script>alert("Error uploading image");</script>';
            // Delete the entry
            $stmt2 = $conn->prepare('DELETE FROM booksdb WHERE title = ?');
            $stmt2->bind_param('s', $_POST['title']);
            if ($stmt2->execute()) {
                // Reload the page
                echo '<script>window.location.href = "add.php";</script>';
            } else {
                echo '<script>alert("Error deleting entry");</script>';
            }
            
        }

        // On page redirect, popup a message and redirect to the view page
        echo '<script>alert("Entry added successfully"); window.location.href = "view.php";</script>';
    } else {
        // On page redirect, popup a message and redirect to the add page
        echo '<script>alert("Error adding entry"); window.location.href = "add.php";</script>';
    }

    // Close the connection
    $stmt->close();
}
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Template for Book Keeping</title>
        </head>

        <body>
            <h1>Book Keeping</h1>
            <p>Here you can keep track of your expenses and income.</p>
            <p>Click <a href="add.php">here</a> to add a new entry.</p>
            <p>Click <a href="view.php">here</a> to view all entries.</p>

            <h2>Add a new entry</h2>
            <form action="add.php" method="post" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title"><br><br>

                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author"><br><br>

                    <label for="publisher">Publisher:</label>
                    <input type="text" id="publisher" name="publisher"><br><br>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea><br><br>

                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category"><br><br>

                    <label for="date_published">Date Published:</label>
                    <input type="date" id="date_published" name="date_published"><br><br>

                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn"><br><br>

                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*"><br><br>

                    <!-- Image preview -->
                    <p>Image Preview:</p>
                    <img id="preview" src="" width="100"><br><br>
                    <input type="submit" value="Add Entry">
            </form>

            <script>
            // Get the image input
            var image = document.getElementById('image');
            // Get the image preview
            var preview = document.getElementById('preview');

            // Add an event listener to the image input
            image.addEventListener('change', function() {
                // Check if the image is selected
                if (image.files && image.files[0]) {
                    // Create a new FileReader
                    var reader = new FileReader();

                    // Set the image preview source
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }

                    // Read the image as a URL
                    reader.readAsDataURL(image.files[0]);
                } else {
                    // Set the image preview source to empty
                    preview.src = '';
                }
            });
        </script>
        </body>
    </html>