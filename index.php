<?php

require_once "vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $file_name = $_FILES["file"]["name"];
    $file_type = $_FILES["file"]["type"];
    $temp_name = $_FILES["file"]["tmp_name"];
    $file_size = $_FILES["file"]["size"];
    $error = $_FILES["file"]["error"];
    if (!$temp_name)
    {
        echo "ERROR: Please browse for file before uploading";
        exit();
    }
    function compress_image($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
        imagejpeg($image, $destination_url, $quality);
        echo $destination_url;
    }
    if ($error > 0)
    {
        echo $error;
    }
    else if (($file_type == "image/gif") || ($file_type == "image/jpeg") || ($file_type == "image/png") || ($file_type == "image/pjpeg"))
    {
        $filename = compress_image($temp_name, "uploads/" . $file_name, 80);
        
    }
    else
    {
        echo "Uploaded image should be jpg or gif or png.";
    }

    

    
    
die;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            
        }
        label{
            width: 500px;
            background-color: #ecf0f1;
            height: 300px;
            display: grid;
            place-items: center;
            color: black;
        }
        input{
            opacity: 0;
            position: absolute;
        }

        .files{
            padding: 0px 20px;
        }
        .file {
            position: relative;
        }
        #download-btn{
            position: absolute;
            top: 5px;
            left: 5px;
            color: white;
            text-decoration: none;
            background-color: rgba(0,0 , 0, .5);
            padding: 5px;
            border-radius: 50%;
        }
        #loader{
            position: absolute;
            width: 100%;
            height: 100vh;
            top: 0px;
            height: 0px;
            background-color: black;
            opacity: 0;
            transition: 500ms all;
            color: white;
            display: grid;
            place-items: center;
        }
    </style>
</head>
<body>

<div id="loader">

</div>
   
<label for="file">
    <span>File upload</span>
    <input multiple name="file[]" type="file" id="file">
</label>



<div id="file-preview">
        
</div>



<script>
    const fileInput = document.querySelector('#file');
    fileInput.addEventListener('change',function(){
        const files = fileInput.files;
        [...files].map((file)=>{
            const form = new FormData();
            form.append('file',file);
            const request = new XMLHttpRequest();
            request.upload.addEventListener('progress' , function(e){
                document.querySelector('#loader').style.opacity = .4;
                document.querySelector('#loader').innerHTML = `${e.loaded / e.total * 100} %`;
                
            },false);
            request.open('POST','index.php')
            request.send(form);
            
            request.addEventListener('load',function(){
                let html = document.createElement('div');
                html.classList.add('file');
                html.innerHTML = `
                <img src="${this.response}" id="img" width="500px" alt="">
            <a download="" id="download-btn" href="${this.response}"><i class="fas fa-download"></i></a>
        </div>`
                document.querySelector('#file-preview').appendChild(html);
                
            });
        });
        

    });
</script>
</body>
</html>