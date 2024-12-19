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

        <h2>Archived Entries</h2>
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

            // Query to select all entries that are archived
            $sql = 'SELECT * FROM booksdb WHERE archiveStatus = 1';

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
                    echo '<td><button onclick="unarchive(' . $row['id'] . ')">Unarchive</button></td>';
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
            // Function to unarchive an entry
            function unarchive(id) {
                // Confirm the action
                if (confirm('Are you sure you want to unarchive this entry?')) {
                    // Perform an AJAX request
                    var xhr = new XMLHttpRequest();

                    // Set the request URL
                    xhr.open('GET', 'unarchive.php?id=' + id, true);

                    // Set the request headers
                    xhr.setRequestHeader('Content-Type', 'application/json');

                    // Set the onload function
                    xhr.onload = function() {
                        // Check the response
                        if (xhr.status == 200) {
                            // Parse the JSON response
                            var res = JSON.parse(xhr.responseText);

                            // Check the status
                            if (res.status === 'success') {
                                // Alert the user
                                alert('Entry unarchived successfully');
                                // Reload the page
                                window.location.reload();
                            } else {
                                alert('Error unarchiving entry');
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