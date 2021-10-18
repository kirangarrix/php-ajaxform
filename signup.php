<?php 

include('./config.php');

$response=array(
    'status' => 0,
    'message' => 'Form submission fail'
);

$uploadDir="uploads/";

$errorEmpty=false;
$errorEmail=false;

if(isset($_POST['firstname'])  ||  isset($_POST['lastname']) || isset($_POST['email']) || isset($_POST['password']) || isset($_POST['file']) ){
   

    // get data submitted form
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    if(!empty($firstname) && !empty($lastname) && !empty($email) || !empty($password) && !empty($_FILES['file']['name'])){
        $response['message']= "Not empty";
      
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $response['message']="invalid Email";
            $errorEmail=true;
        }else{
            if($errorEmpty == false && $errorEmail == false){
                $uploadStatus=1;

                $uploadFile='';

                if(!empty($_FILES['file']['name'])){
                    $fileName=basename($_FILES['file']['name']);
                    $targetFilePath=$uploadDir . $fileName;
                   // $fileType=pathinfo($targetFilePath,PATHINFO_EXTENSION);

                    if(file_exists($targetFilePath)){
                        $response['message']="Sorry File Already Exists";
                        $uploadStatus=0;
                    }else{
                        if($_FILES['file']['size']>500000){ //500kb
                            $response['message']="Sorry Your File too large";
                            $uploadStatus=0;
                        }else{
                            if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFilePath)){
                                $uploadFile=$fileName;
                                $uploadStatus=1;
                            }else{
                                $response['message']='Sorry an error occured';
                                $uploadStatus=0;
                            }
                        }
                    }
                }
                if($uploadStatus==1){
                    $hash=md5($password);
                    $check="SELECT * FROM user WHERE email = '$email' ";
                    $r=mysqli_query($con,$check);
                    if(mysqli_num_rows($r) == 1){
                      $response['message']="SOrry Email already Exist";
                    }else{
                       // $query="INSERT INTO user(firstname,lastname,email,password,image) VALUES('$firstname','$lastname','$email','$password','$uploadFile')";
                       $query="INSERT INTO user(firstname,lastname,email,password,image) VALUES('$firstname','$lastname','$email','$hash','$uploadFile')";
                       if (mysqli_query($con, $query)){
                       $response['message']="Insert Successfully";
                       $response['status']=1;
                       }else{
                           echo "error";
                           die();
                       }
                    }
                }
            }
        }

    }else{
        $response['message']= "Empty";
        $errorEmpty=true;
    }

}

echo json_encode($response);

?>