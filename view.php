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

        <h2>View all entries</h2>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Description</th>
                <th>Category</th>
                <th>Date Published</th>
                <th>ISBN</th>
                <th>Image</th>
                <th colspan="2">Actions</th>
            </tr>
            <?php
            // Include the database connection file
            include 'db.php';

            // Query to select all entries that are not archived
            $sql = 'SELECT * FROM booksdb WHERE archiveStatus = 0';

            // Execute the query
            $result = $conn->query($sql);

            // Check if the query returned any results
            if ($result->num_rows > 0) {
                // Loop through the results
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['author'] . '</td>';
                    echo '<td>' . $row['publisher'] . '</td>';
                    echo '<td>' . $row['descr'] . '</td>';
                    echo '<td>' . $row['category'] . '</td>';
                    echo '<td>' . $row['date_published'] . '</td>';
                    echo '<td>' . $row['isbn'] . '</td>';
                    echo '<td><img src="uploads/' . $row['img'] . '" width="100"></td>';
                    echo '<td><a href="edit.php?id=' . $row['id'] . '">Edit</a></td>';
                    echo '<td><button onclick="archive(' . $row['id'] . ')">Archive</button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No entries found</td></tr>';
            }

            // Close the connection
            $conn->close();
            ?>

        </table>

        <script>

            function archive(id) {
                // Confirm the action
                if (confirm('Are you sure you want to archive this entry?')) {
                    // Perform an AJAX request
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'archive.php?id=' + id, true);

                    // Check the response
                    xhr.onload = function() {
                        if (xhr.status == 200) {
                            // Parse the JSON response
                            var res = JSON.parse(xhr.responseText);

                            // Check the status
                            if (res.status === 'success') {
                                // Alert the user
                                alert('Entry archived successfully');
                                // Reload the page
                                window.location.reload();
                            } else {
                                alert('Error archiving entry');
                            }
                        }
                    };

                    // Send the request
                    xhr.send();
                }
            }
        </script>
    </body>

</html>
