# Book Keeping System Template
 
## How to use?
- Clone this repository or download a copy
- Import the sql file to your own database and change the credentials accordingly
- Once done, you can now start modifying the design of the webpage.
- Most parts of the code have explanations on what does that part do. Feel free to conduct some research if you can't figure out how does it work.
- Variable names used are all straight to the point - It'll be easier to understand if you have knowledge how to use php, mysql, and js.
- Image uploads are deleted by default. Feel free to replace it.

- To reset auto incrementing just execute this SQL statement. TAKE NOTE THAT THIS WILL RESET YOUR TABLE!!!
```
TRUNCATE TABLE booksdb
MODIFY TABLE booksdb AUTO_INCREMENT = 1
```
- I highly recommend to extract all js scripts from PHP files itself if you're using it. Those script codes are placed inside php files for convience but is not recommended.
