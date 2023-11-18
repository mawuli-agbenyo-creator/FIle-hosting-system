<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .download-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        #countdown {
            font-size: 2em;
            color: #e44d26; /* Download button color */
            font-weight: bold;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #e44d26; /* Download button color */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:disabled {
            background-color: #ccc; /* Disabled button color */
            cursor: not-allowed;
        }

        button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

    <div class="download-container">
        <h1>Special Offer!</h1>

        <!-- Download Countdown Timer -->
        <p>Download will start in <span id="countdown">5</span> seconds...</p>

        <!-- Download Button -->
        <button id="downloadButton" onclick="downloadFile()" disabled>Download Now</button>
    </div>

    <!-- Add your scripts or other body elements here -->

    <script>
        // Function to get the last part of the URL
        function getLastPartOfUrl() {
            var pathArray = window.location.pathname.split('/');
            return pathArray[pathArray.length - 1];
        }

        // Extract the parameter from the URL
        var lastPartOfUrl = getLastPartOfUrl();

        // Set the countdown duration in seconds (you can use the lastPartOfUrl here)
        let countdownDuration = 5;

        // Function to update the countdown display and enable the download button
        function updateCountdown() {
            // Update the countdown display
            document.getElementById('countdown').textContent = countdownDuration;

            // If countdown reaches zero, enable the download button
            if (countdownDuration === 0) {
                document.getElementById('downloadButton').disabled = false;
            } else {
                // Decrement the countdown duration
                countdownDuration--;

                // Schedule the next update after 1 second
                setTimeout(updateCountdown, 1000);
            }
        }

        // Function to simulate the file download
        // function downloadFile() {
        //     // Simulated download logic
        //     sendAjaxRequest(lastPartOfUrl);
        //     alert('Simulated file download for ' + lastPartOfUrl);
        //     // You can replace this with your actual download logic
        // }

        // Start the countdown when the page loads
        window.onload = function () {
            updateCountdown();
        };







         // Function to send Ajax request
    function sendAjaxRequest(parameterValue) {
        // Replace the URL with the actual endpoint you want to send the request to
        var apiUrl = '/download';

        // Make sure to replace 'your_csrf_token' with the actual CSRF token name used in your Laravel application
        var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        // Your Ajax request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', apiUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        // You can handle the response here
        xhr.onload = function () {
            if (xhr.status === 200) {
                var currentUrl = window.location.href;
                window.location.href = xhr.responseText;
                console.log('Success:', xhr.responseText);
            } else {
                console.error('Error:', xhr.statusText);
            }
        };

        // You can handle errors here
        xhr.onerror = function () {
            console.error('Request failed');
        };

        // Send the request with the parameterValue
        xhr.send('data=' + parameterValue);
    }

    // Function to start the Ajax request on button click
    function downloadFile() {
        var lastPartOfUrl = getLastPartOfUrl();
        sendAjaxRequest(lastPartOfUrl);
        // You can also add your simulated download logic here
        // alert('Simulated file download for ' + lastPartOfUrl);
    }
    </script>

</body>
</html>
