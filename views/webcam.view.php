<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
    <style>
        #container {
            margin: 0px auto;
            width: 480px;
            height: 720px;
            border: 10px #333 solid;
        }
        #videoElement {
            width: 480px;
            height: 360px;
            position: absolute;
        }

        .webcam_filter_frame {
            width: 480px;
            height: 360px;
        }
        .visualisation {
            width: 480px;
            height: 360px;
        }

        #test {
            display:none;
        }

        #filter {
            position: relative;
            width:480px;
            height: 360px;
        }

        .test {
            width: 100%;
            height:100%;
        }

        #filter_
    </style>
</head>
<body class="webcam_body">
<div id="container">
    <video autoplay="true" id="videoElement"></video>
    <div class="webcam_filter_frame">
        <img id="filter" src="assets/filters/hearts.png" />
    </div>
    <canvas id="test"></canvas>
    <div class="visualisation">
        <img class="test" id="snap" />
    </div>
    <button id="save_picture">Take snapshot</button>

    <script type="text/javascript">
        let filter = document.querySelector("#filter")
        let video = document.querySelector("#videoElement");
        let save = document.querySelector("#save_picture")
        let canvas_filter = document.querySelector("#test");
        let ctx_filter = canvas_filter.getContext('2d');
        if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true, audio: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    ctx_filter.drawImage(filter,0, 0, canvas_filter.width, canvas_height.height);
                })
                .catch(function (err0r) {
                    console.log("Something went wrong!");
                });
        }
        save.addEventListener('click', function () {
            let snap = document.querySelector("#snap");
            let canvas = document.querySelector("#test");
            let ctx = canvas.getContext('2d');
            canvas.width = 1440;
            canvas.height = 1080;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            ctx.drawImage(filter,0, 0, canvas.width, canvas.height);
            let dataURI = canvas.toDataURL('image/png'); // can also use 'image/png'
            snap.src = dataURI;
            console.log(dataURI);
        });

        function main() {
            imagesLoaded += 1;

            if(imagesLoaded == 2) {
                // composite now
                ctx.drawImage(img1, 0, 0);

                ctx.globalAlpha = 0.5;
                ctx.drawImage(img2, 0, 0);
            }
        }

        function loadImage(src, onload) {
            let img = new Image();

            img.onload = onload;
            img.src = src;

            return img;
        }
    </script>
</div>
</body>
</html>