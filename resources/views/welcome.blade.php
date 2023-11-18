<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css">
    <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://getbootstrap.com/assets/css/docs.min.css'>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>

<body>
    <div class="container"> <br />
        <div class="row">

            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Upload files</strong> <small> </small></div>
                    <div class="panel-body">
                        <!-- Drop Zone -->
                        <form action="{{ route('upload') }}" class="dropzone" id="upload-form"></form>
                        <br />
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%;" id="upload-progress"> <span class="sr-only">0%
                                    Complete</span> </div>
                        </div>
                        <br />
                        <!-- Upload Finished -->
                        <div class="js-upload-finished">
                            <h4>Upload history</h4>
                            <div class="list-group" id="upload-history"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Download files</strong> <small> </small></div>
                    <div class="panel-body send_link">

                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- /container -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        // Initialize Dropzone
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#upload-form", {
            url: "{{ route('upload') }}",
            paramName: "file",
            maxFilesize: 500, // MB
            maxFiles: 5,

            // acceptedFiles: "image/*",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
            },

            init: function() {
                this.on("success", function(file, response) {
                    // Handle successful uploads
                    console.log(response);

                    // Assuming the response contains the link as 'fileLink'
                    var fileLink = response.fileLink;

                    // Create a link element
                    var linkElement = document.createElement("a");
                    linkElement.href = fileLink;
                    linkElement.textContent = "Download File";

                    // Create a button element for copying the link
                    var copyButton = document.createElement("button");
                    copyButton.textContent = "Copy Link";
                    copyButton.style.marginLeft = "100px"; // Adjust the margin as needed
                    copyButton.addEventListener("click", function() {
                        // Copy the link to the clipboard
                        var tempInput = document.createElement("input");
                        tempInput.value = fileLink;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand("copy");
                        document.body.removeChild(tempInput);
                        alert("Link copied to clipboard!");
                    });

                    // Create a container for the link and the copy button
                    var linkContainer = document.createElement("div");
                    linkContainer.appendChild(linkElement);
                    linkContainer.appendChild(copyButton);

                    // Append the link container to the panel-body div with the class 'send_link'
                    document.querySelector('.send_link').appendChild(linkContainer);

                    // Add a line break
                    var lineBreak = document.createElement("br");
                    document.querySelector('.send_link').appendChild(lineBreak);

                });
                this.on("error", function(file, errorMessage) {
                    // Handle errors
                    console.log();
                });
                this.on("removedfile", function(file) {
                    // Handle file removal
                    removeFile(file)
                });
                this.on("totaluploadprogress", function(progress) {
                    // Handle total upload progress
                    document.getElementById("upload-progress").style.width = progress + "%";
                });
            }
        });



        // Custom function to handle file removal
        function removeFile(file) {
            // You can make an AJAX request to delete the file on the server
            // Replace "/your-delete-url" with the actual URL for deleting files
            $.ajax({
                type: "POST",
                url: "/your-delete-url",
                data: {
                    filename: file.name
                }, // Send the filename to be deleted
                success: function(data) {
                    console.log("File removed successfully");
                },
                error: function(error) {
                    console.error("Error removing file: ", error);
                }
            });
        }
    </script>
</body>

</html>
