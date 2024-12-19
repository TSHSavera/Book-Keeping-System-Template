<?php

// Include the database connection file
include 'db.php';

// Check if the ID is set
if (isset($_GET['id'])) {
    // Prepare the SQL query - select the entry with the given ID and that is not archived
    $stmt = $conn->prepare('SELECT * FROM booksdb WHERE id = ? AND archiveStatus = 0');

    // Bind the ID to the query
    $stmt->bind_param('i', $_GET['id']);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the query returned any results
    if ($result->num_rows > 0) {
        // Get the row
        $row = $result->fetch_assoc();
    } else {
        // Redirect to the view page
        header('Location: view.php');
    }
} else {
    // Redirect to the view page
    header('Location: view.php');
}

// Submit the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the SQL query - if the image is empty, do not update the image
    if (empty($_FILES['image']['name'])) {
        $stmt = $conn->prepare('UPDATE booksdb SET title = ?, author = ?, publisher = ?, descr = ?, category = ?, date_published = ?, isbn = ? WHERE id = ?');
        // Prepare the data to be inserted
        // For the date_published, we will convert the date to the format YYYY-MM-DD
        $datePublished = date('Y-m-d', strtotime($_POST['date_published']));
        $stmt->bind_param('sssssssi', $_POST['title'], $_POST['author'], $_POST['publisher'], $_POST['description'], $_POST['category'], $_POST['date_published'], $_POST['isbn'], $_POST['id']);

        // TODO: Add checks for other fields

        // Execute the query
        if ($stmt->execute()) {
            // On page redirect, popup a message and redirect to the view page
            echo '<script>alert("Entry edited successfully"); window.location.href = "view.php";</script>';
        } else {
            echo '<script>alert("Error editing entry");</script>';
        }
    } else {
        $stmt = $conn->prepare('UPDATE booksdb SET title = ?, author = ?, publisher = ?, descr = ?, category = ?, date_published = ?, isbn = ?, img = ? WHERE id = ?');
        // Prepare the data to be inserted
        // For the image, we will store the file name
        $imageData = $_FILES['image']['name'];
        $imagePath = 'uploads/' . $imageData;

        // For the date_published, we will convert the date to the format YYYY-MM-DD
        $datePublished = date('Y-m-d', strtotime($_POST['date_published']));

        // TODO: Add checks for other fields

        $stmt->bind_param('ssssssssi', $_POST['title'], $_POST['author'], $_POST['publisher'], $_POST['description'], $_POST['category'], $_POST['date_published'], $_POST['isbn'], $imageData, $_POST['id']);

        // Check if the image is an image
        if (getimagesize($_FILES['image']['tmp_name']) === false) {
            // On page redirect, popup a message and redirect to the edit page
            echo '<script>alert("Please upload an image");window.location.href = "edit.php?id=' . $_POST['id'] . '";</script>';
        }

        // Check if the image is a valid image
        $imageFileType = strtolower(pathinfo($imageData, PATHINFO_EXTENSION));
        if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg' && $imageFileType !== 'gif') {
            // On page redirect, popup a message and redirect to the edit page
            echo '<script>alert("Please upload a valid image");window.location.href = "edit.php?id=' . $_POST['id'] . '";</script>';
        }

        // Check if the image already exists
        if (file_exists('uploads/' . $imageData)) {
            // On page redirect, popup a message and redirect to the edit page
            echo '<script>alert("Image already exists");window.location.href = "edit.php?id=' . $_POST['id'] . '";</script>';
        }

            // Execute the query
        if ($stmt->execute()) {
            // Check if the image was uploaded successfully
            if (!move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageData)) {
                // On page redirect, popup a message and redirect to the edit page
                echo '<script>alert("Error uploading image");window.location.href = "edit.php?id=' . $_POST['id'] . '";</script>';
            }

            // On page redirect, popup a message and redirect to the view page
            echo '<script>alert("Entry edited successfully"); window.location.href = "view.php";</script>';
        } else {
            echo '<script>alert("Error editing entry");</script>';
        }
    }
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
        <p>Click <a href="archives.php">here</a> to view archived entries.</p>

        <h2>Edit Entry</h2>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <!-- Fill the form with the existing data -->
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="title">Title:</label><br>
            <input type="text" name="title" id="title" value="<?php echo $row['title']; ?>"><br><br>
            <label for="author">Author:</label><br>
            <input type="text" name="author" id="author" value="<?php echo $row['author']; ?>"><br><br>
            <label for="publisher">Publisher:</label><br>
            <input type="text" name="publisher" id="publisher" value="<?php echo $row['publisher']; ?>"><br><br>
            <label for="description">Description:</label><br>
            <textarea name="description" id="description"><?php echo $row['descr']; ?></textarea><br><br>
            <label for="category">Category:</label><br>
            <input type="text" name="category" id="category" value="<?php echo $row['category']; ?>"><br><br>
            <label for="date_published">Date Published:</label><br>
            <input type="date" name="date_published" id="date_published" value="<?php echo $row['date_published']; ?>"><br><br>
            <label for="isbn">ISBN:</label><br>
            <input type="text" name="isbn" id="isbn" value="<?php echo $row['isbn']; ?>"><br><br>
            <label for="image">Image:</label><br>
            <input type="file" name="image" id="image"><br><br>
            <!-- Currently uploaded picture -->
            <p>Currently uploaded picture:</p>
            <img src="uploads/<?php echo $row['img']; ?>" width="100"><br><br>
            <p>Image Preview:</p>
            <img id="preview" width="100"><br><br>
            <input type="submit" value="Edit Entry">
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