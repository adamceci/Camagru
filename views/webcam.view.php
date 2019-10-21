<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
    <style>
        #container {
            margin: 0px auto;
            width: 500px;
            height: 375px;
            border: 10px #333 solid;
        }
        #videoElement {
            width: 500px;
            height: 375px;
            background-color: #666;
        }
    </style>
</head>
<body class="webcam_body">
<div id="container">
    <video autoplay="true" id="videoElement">

    </video>
    <script type="text/javascript">
        var video = document.querySelector("#videoElement");

        if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true, audio: true })
                .then(function (stream) {
                    video.srcObject = stream;
                })
                .catch(function (err0r) {
                    console.log("Something went wrong!");
                });
        }
    </script>
</div>
</body>
</html>