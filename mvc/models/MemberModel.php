<?php
class MemberModel extends DB{

    //get all member from database
    public function GetAllMember()
    {
        $sql = "SELECT * FROM member WHERE username!='admin' ORDER BY id ";
        return mysqli_query($this->con, $sql);
        
    }
    //get all limt member from database
    public function GetLimitMember($memPerPage,$page)
    {
        $start = ($page-1)*$memPerPage;
        $sql = "SELECT * FROM member WHERE username!='admin' ORDER BY id LIMIT $start, $memPerPage";
        return mysqli_query($this->con, $sql);
    }
    // delete member
    public function DeleteMember($id)
    {
        $sql = "DELETE FROM member WHERE id='$id' ";
        return mysqli_query($this->con, $sql);
    
    }
    //search Member for managemember of ADMIN page
     public function SearchMember($username)
     {
        $sql = "SELECT * FROM member WHERE username != 'admin' and username like '%$username%' ";
        $row = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($row) == 0){
            return false;
        }
        else{
            return $row;
        }
     
     }
    public function LimitSearchMember($username,$memPerPage, $page)
    {
        $start = ($page-1)*$memPerPage;
        $sql = "SELECT * FROM member WHERE username like '%$username%' and username != 'admin' LIMIT $start,$memPerPage";
        $row = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($row) == 0){
            return false;
        }
        else{
            return $row;
        }
    
    }
    //register
    public function InsertNewUser($username, $pass, $fullname, $email){   
        $sql = "INSERT INTO member VALUES ( null, '$username', '$pass', '$fullname', '$email')";
        if (mysqli_query($this->con, $sql) ){
            $result = true;
        }
        else {
            $result = false;
        }                          
    return json_encode($result);
    }
    
    //login member
    public function CheckMember($Username, $Password){
        $qr = "SELECT * FROM member WHERE username = '$Username'";
        $row = mysqli_query($this->con, $qr);
        $data = mysqli_fetch_assoc($row);
        //ki???m tra user ???? t???n t???i hay ch??a
        if (mysqli_num_rows($row) == 1 ){
             //n???u pass ???? m?? h??a th?? x??i h??m password_verify($data['pass'], $Password) ????? gi???i m??
            if($Password == $data['pass']){
                return json_encode($data);
            }
            else {
                $result = 1;// sai m???t kh???u
            }
        }
        else{
            $result  = 2;//sai t??n ????ng nh???p
        }
        return json_encode($result);    
    }

    //register
    public function CheckNewUser($user){
        $sql = "SELECT id FROM member WHERE username = '$user' ";
        $row = mysqli_query($this->con, $sql);
        //ki???m tra user tr??ng
        if (mysqli_num_rows($row) > 0 ){
            // $result = true;
            echo "Username has been existed!";
        }
        else {
            // $result = false;
            echo "";
        } 
        // return json_encode(echo);
    }
    public function CheckUser($user){
        $sql = "SELECT id FROM member WHERE username = '$user' ";
        $row = mysqli_query($this->con, $sql);
        //ki???m tra user tr??ng
        if (mysqli_num_rows($row) > 0 ){
            // $result = true;
            echo "";
        }
        else {
            // $result = false;
            echo " Not modify your username!!!";
        } 
        // return json_encode(echo);
    }
    //forgot
    public function CheckMail($username, $email){
        $qr = "SELECT * FROM member WHERE  username = '$username' and email = '$email'";
        $row = mysqli_query($this->con, $qr);
        //ki???m tra username v?? email c?? t???n t???i kh??ng
        if (mysqli_num_rows($row) > 0 ){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
            $PasswordReset = substr(str_shuffle($chars), 0, 8);
            $update = mysqli_query($this->con, "UPDATE  member SET pass ='$PasswordReset'
            WHERE email = '$email'"); 
        }
        else {
            $PasswordReset = false;
        } 
        return json_encode($PasswordReset);
          
    }
     // update
    public function UpdateInfo($username, $fullname, $email)
     {
         $sql = "SELECT * FROM member WHERE username = '$username'";
         $row = mysqli_query($this->con, $sql);
         if (mysqli_num_rows($row) > 0) {
             $sql = "UPDATE member  
                 SET fullname='$fullname',   
                     email= '$email'     
                 WHERE username = '$username'";
             //  echo $sql;
             //  die();
             if (mysqli_query($this->con, $sql)) {
                 $result = true;
             } else {
                 $result = false;
             }
         }
         return json_encode($result);
    }
     //  //change password
     public function ChangePassword($username, $oldpass, $newpass){
        
            $sql = "UPDATE member  
                    SET pass='$newpass'       
                    WHERE username = '$username' and pass = '$oldpass' ";
            //  echo $sql;
            //  die();
            return mysqli_query($this->con, $sql);
    }

}
?>