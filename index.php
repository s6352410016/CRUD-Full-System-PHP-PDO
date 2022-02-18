<?php

    session_start();
    require_once("config/db.php");

    if(isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
        $deletestmt->execute();

        if($deletestmt){
            echo "<script>alert('Data has been delete successfully');</script>";
            $_SESSION['success'] = "Data has been delete successfully";
            header("refresh:1; url=index.php");
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
</head>
<body>
    
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="insert.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="firstname" class="col-form-label">Firstname:</label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="col-form-label">Lastname:</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="col-form-label">Position:</label>
                            <input type="text" class="form-control" name="position" required>
                        </div>
                        <div class="mb-3">
                            <label for="img" class="col-form-label">Image:</label>
                            <input type="file" class="form-control" id="imgInput" name="img" required>
                            <img width="100%" id="previewImg" alt="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>CRUD Bootstrap</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add User</button>
            </div>
        </div>
        <hr>
        <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success">
                    <?php
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                    ?>
                </div>
        <?php } ?>
        <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger">
                    <?php
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>
        <?php } ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Position</th>
                    <th scope="col">Img</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $stmt = $conn->query("SELECT * FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    
                    if(!$users){
                        echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
                    }else{
                        foreach($users as $user){       
                ?>
                <tr>
                    <td scope="row"><?php echo $user['id']; ?></td>
                    <td><?php echo $user['firstname']; ?></td>
                    <td><?php echo $user['lastname']; ?></td>
                    <td><?php echo $user['position']; ?></td>
                    <td width="150px"><img width="100%" src="uploads/<?php echo $user['img']; ?>" class="rounded" alt=""></td>
                    <td>
                        <a class="btn btn-warning" href="edit.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a class="btn btn-danger" href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete?');">Delete</a>            
                    </td>        
                </tr>
                <?php 
                        }
                    } 
                ?>
            </tbody>
        </table>
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