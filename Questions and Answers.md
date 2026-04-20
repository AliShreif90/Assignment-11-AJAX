Question 1: In main.js, the addName function uses fetch() to send data to 
addName.php. Explain the request/response flow: what data format is sent from 
JavaScript, how does PHP receive it, and what format must PHP return for 
JavaScript to process it?

Answer: In main.js, fetch() sends the name from JavaScript to addName.php as 
request data. In this case, the request included a field like name: "Amy Adams".
On the PHP side, that data can be read from the request body, commonly
through $_POST['name'], or by reading the raw input if needed. After processing
the request, PHP must return JSON, because the JavaScript is using
response.json() to parse it. If PHP returns plain HTML instead of JSON, JavaScript fails with errors like Unexpected token '<'. 

Question 2: Looking at displayNames.php, it returns different JSON structures for success cases (with names field) versus error cases (with msg field). Explain why maintaining a consistent response structure is important for the JavaScript code that processes these responses.

Answer: A consistent response structure matters because the JavaScript code expects data in a predictable shape. If one successful response returns { "names": "..." } but an error response returns { "msg": "..." }, then the JavaScript has to branch and check different property names every time. If it only looks for names, then a response with only msg may appear blank or break the display logic. Keeping the same structure, such as always returning a names field and optionally adding extra fields like debug or msg, makes the frontend simpler and more reliable. 

Question 3: In displayNames.php, the code checks if $records === "error" and also checks count($records) > 0. Explain the difference between these two conditions and why both checks are necessary before processing the database results.

Answer: The condition $records === "error" checks whether the database operation itself failed. That is different from count($records) > 0, which checks whether the query succeeded and returned one or more rows. Both checks are needed because a query can fail entirely, or it can succeed but return no matching records. An error means something went wrong with the SQL or connection. An empty result means the SQL worked, but there was simply no data to display yet. 

Question 4: When a user clicks the "Add Name" button, main.js calls names.addName(), which then calls names.displayNames(). Explain why displayNames() is called after adding a name, and describe the sequence of AJAX requests that occur during this process.

Answer: displayNames() is called after adding a name so the page can immediately show the updated list without requiring a manual refresh. The sequence is: the user clicks Add Name, main.js sends an AJAX request to addName.php, PHP inserts the new name into the database, and then JavaScript calls displayNames() to send another AJAX request to displayNames.php. That second request gets the full updated list from the database and displays it in sorted order under the form. 

Question 5: After clearNames.php successfully clears the database, the JavaScript calls names.displayNames() to refresh the list. Explain why this refresh is necessary and what would happen if this call were omitted. How does this demonstrate the stateless nature of HTTP requests?

Answer: The refresh after clearNames.php is necessary because clearing the database does not automatically update what the user already sees in the browser. The page still shows the old list until JavaScript requests fresh data and redraws that section. If names.displayNames() were omitted, the database would be empty, but the old names would still appear on the screen until the page is reloaded. This shows the stateless nature of HTTP: each request is independent, and the server does not automatically “push” changes into the browser. The browser must make a new request to get the current state.
