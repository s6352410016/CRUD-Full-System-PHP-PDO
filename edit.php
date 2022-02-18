<?php

    session_start();
    require_once("config/db.php");

    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $position = $_POST['position'];
        $img = $_FILES['img'];

        $img2 = $_POST['img2'];
        $upload = $_FILES['img']['name'];

        // เงื่อนไขสำหรับเช็คว่าถ้าใช้รูปใหม่จะให้ทำอะไร
        if($upload != ''){
            $allow = array('jpg' , 'jpeg' , 'png');
            $extension = explode("." , $img['name']);
            $fileActExt = strtolower(end($extension));
            $fileNew = rand() . "." . $fileActExt;
            $filePath = "uploads/".$fileNew;

            if(in_array($fileActExt , $allow)){ // เช็คนามสกุลไฟล์ที่รับมา ว่าตรงกันกับสกุลไฟล์ที่เราจะให้ใช้ไหม
                if($img['size'] > 0 && $img['error'] == 0){
                    move_uploaded_file($img['tmp_name'] , $filePath);
                }
            }
               
        }else{ // เงื่อนไขสำหรับใช้รูปเดิมจะไห้ทำไอะไร
            $fileNew = $img2;
        }   

        $sql = $conn->prepare("UPDATE users SET firstname = :firstname , lastname = :lastname , position = :position , img = :img WHERE id = $id");
        $sql->bindParam(":firstname" , $firstname);
        $sql->bindParam(":lastname" , $lastname);
        $sql->bindParam(":position" , $position);
        $sql->bindParam(":img" , $fileNew);
        $sql->execute();

        if($sql){
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: index.php");
        }else{
            $_SESSION['error'] = "Data has not been updated";
            header("location: index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud PDO & Bootstrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .container{
            max-width: 550px;
        }
    </style>
</head>
<body>
    
    <div class="container mt-5">
        <h1>Edit Data</h1>
        <hr>
        <form method="post" enctype="multipart/form-data">
            <?php
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmt = $conn->query("SELECT * FROM users WHERE id = $id");
                    $stmt->execute();
                    $data = $stmt->fetch();
                }
            ?>
            <div class="mb-3">
                <input type="text" readonly class="form-control" name="id" required value="<?php echo $data['id']; ?>"> 
                <label for="firstname" class="col-form-label">Firstname:</label>
                <input type="text" class="form-control" name="firstname" required value="<?php echo $data['firstname']; ?>">
                <input type="hidden" class="form-control" name="img2" required value="<?php echo $data['img']; ?>">
            </div>
            <div class="mb-3">
                <label for="lastname" class="col-form-label">Lastname:</label>
                <input type="text" class="form-control" name="lastname" required value="<?php echo $data['lastname']; ?>">
            </div>
            <div class="mb-3">
                <label for="position" class="col-form-label">Position:</label>
                <input type="text" class="form-control" name="position" required value="<?php echo $data['position']; ?>">
            </div>
            <div class="mb-3">
                <label for="img" class="col-form-label">Image:</label>
                <input type="file" class="form-control" id="imgInput" name="img">
                <img width="100%" id="previewImg" alt="" src="uploads/<?php echo $data['img']; ?>">
            </div>
            <div class="modal-footer">
                <a href="index.php" class="btn btn-secondary">Go back</a>
                <button type="submit" name="update" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        //นำไฟล์รูปภาพที่เลือกมาแสดงโชว์ 
        imgInput.onchange = evt =>{
            const [file] = imgInput.files;
            if(file){
                previewImg.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>
</html>