<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href=".\assets\style\darkly.min.css" rel="stylesheet">
    <script src=".\assets\script\jquery.3.2.1.min.js"></script>
    <script src=".\assets\script\bootstrap.min.js"></script>
    <style>
        .container{
            padding-top: 40px;
        }
        #panel-table{
            padding: 0px;
        }
        #ftp-file-list{
            width: 100%;
        }
        #table-file-name{
            white-space: normal;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

<!-- <form action="upload.php" method="post" enctype="multipart/form-data">
    <label>Select file to upload:</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form> -->


<div class="container">
    <!-- Uploader -->
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Upload Files</strong></div>
        <div class="panel-body">
            <!-- Standar Form -->
            <h4>Select files from your computer</h4>
            <form action="process.php" method="post" enctype="multipart/form-data" id="upload-form" onsubmit="return checkFile()">
                <div class="form-inline">
                    <div class="form-group">
                        <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                    </div>
                    <h4>Uploaded File Name</h4>
                    <div class="form-group">
                        <input type="text" id="custom-file-name" class="form-control" name="customName" placeholder="Choose file first :(">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary pull-right" name="upload" id="js-upload-submit">Upload files</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client -->

    <div class="panel panel-default">
            <div class="panel-heading" id="ftp-dir">Download Files</div>
            <div id="panel-table" class="panel-body table-responsive">
                <table class="table table-hover table-striped" id="ftp-file-list">

                </table>
            </div>
        </div>
    </div>

    <!-- some php -->
    <?php
        session_start();
        if(isset($_SESSION['info'])){ 
            echo "<script>alert(\"".$_SESSION['info']."\")</script>";
            unset($_SESSION['info']);
        }
    ?>

    <script>
        $(document).ready(function(){

            $("#fileToUpload").change(function(){
                const customFileName = $("#custom-file-name");
                customFileName.val($(this).prop('files')[0].name);
                customFileName.attr('size', customFileName.val().length);
            });
            
            // server file process
            $.ajax({
                type: "POST",
                url: "process.php",
                data: "action=showDir",
                success: function(data){
                    console.log(data);
                    data = JSON.parse(data);
                    if(data.ok){
                        console.log(data);
                        createTableFrom(data.list);
                    }
                }
            });

            function createTableFrom(data){
                const table = $("#ftp-file-list");
                let stringHtml = '<tbody>';
                for(let i = 0; i < data.length;i++){
                    data[i] = data[i].replace(/^.*[\\\/]/, '');
                    stringHtml += `<tr>`;
                    stringHtml += `<td id="table-file-name">${data[i]}</td>`;
                    stringHtml += `<td><button class='btn btn-success pull-right' onclick='download("${data[i]}")'>download</button></td>`;
                    stringHtml += `</tr>`;
                }
                stringHtml += `</tbody>`;
                table.append(stringHtml);
            }

            function setViewDir(dir){
                $("#ftp-dir").html(dir);
            }

            


        //     $("#upload-form").submit(function(e){
        //         // mencegah berubah halaman
        //         e.preventDefault();
        //         console.log(document.getElementById('upload-files').files[0]);
        //     });
        });


        function checkFile(){
            if(!$("#fileToUpload").prop('files').length){
                alert("Please choose file first :(");
                return false;
            }
            return true;
        };
        function download(_path){
            document.location.href = "./process.php?download="+_path;
            // //alert(_path);
            // $.ajax({
            //     type: "POST",
            //     url: "process.php",
            //     data: "action=download&path="+_path,
            //     success: function(data){
            //         data = JSON.parse(data);
            //         if(data.ok){
            //             console.log(data);
            //         }
            //     }
            // });
        }
    </script>
</body>
</html>