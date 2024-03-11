<?php
    session_start();
    include_once "config.php";//include database connect file
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){//filter_var function Filters a variable with a specified filter here validating email
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exist!";//we are checking from database if email already available then shows exist already
            }else{//check file uploaded by user or not.
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];//get user uploaded file name
                    $img_type = $_FILES['image']['type'];//file type
                    $tmp_name = $_FILES['image']['tmp_name'];//this move image temporary to our folder
                    
                    $img_explode = explode('.',$img_name);//explode()split image  with dot get extension like jpg..
                    $img_ext = end($img_explode);//getting extension of user uploaded file
    
                    $extensions = ["jpeg", "png", "jpg"];
                    if(in_array($img_ext, $extensions) === true){//if uploaded extension match with specified extension in array proceed next
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if(in_array($img_type, $types) === true){
                            $time = time();//use current time to rename for uniqueness of  moved image file in our folder 
                            $new_img_name = $time.$img_name;
                            //move uploaded file to our folder
                            if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                                $ran_id = rand(time(), 100000000);//creating random id for each user, reprsent uniqueid in table user
                                $status = "Active now";
                                $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                VALUES ({$ran_id}, '{$fname}','{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')");
                                if($insert_query){
                                    $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        $_SESSION['unique_id'] = $result['unique_id'];//using this seesion we use unique_id in other php file.means when login using registered email id, user unique id will be used by using session.
                                        echo "success";
                                    }else{
                                        echo "This email address not Exist!";
                                    }
                                }else{
                                    echo "Something went wrong. Please try again!";
                                }
                            }
                        }else{
                            echo "Please upload an image file - jpeg, png, jpg";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                }
            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }
?>