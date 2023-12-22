<?php 
   require('../admin/inc/db_config.php');
   require('../admin/inc/essentials.php');

   date_default_timezone_set("Asia/Ho_Chi_Minh");

    if (isset($_POST['info_form']))
    {
        $frm_data = filteration($_POST);
        session_start();

        $u_exist =select("SELECT * FROM `user_cred` WHERE  `phonenum`=? AND `id`!=? LIMIT 1",
        [$frm_data['phonenum'],$_SESSION['uId']],"ss");
        
        if(mysqli_num_rows($u_exist)!=0){
            // phone number already -> login_register.php
            echo 4;
            exit;
        }

        $query = "UPDATE `user_cred` SET `name`=?, `phonenum`=?, 
            `dob`=?, `address`=? WHERE `id`=?";
        $values = [$frm_data['name'], $frm_data['phonenum'], 
            $frm_data['dob'], $frm_data['address'], $_SESSION['uId']];

        if(update($query,$values,'sssss')){
            $_SESSION['uName'] = $frm_data['name'];
            echo 1;
        }else{
            echo 0;
        }

    }

    if (isset($_POST['profile_form']))
    {
        session_start();

        $img = uploadUserImage($_FILES['profile']);

        if ($img == 'inv_img') {
            echo 'inv_img';
            exit;
        }
        else if ($img == 'upd_failed') {
            echo 'upd_failed';
            exit;
        }

        //fetching old image and deleting it

        $u_exist = select("SELECT `profile` FROM `user_cred` WHERE  `id`=? LIMIT 1", [$_SESSION['uId']],"s");
        $u_fetch = mysqli_fetch_assoc($u_exist);

        deleteImage($u_fetch['profile'],USERS_FOLDER);

        $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=?";

        $values = [$img, $_SESSION['uId']];

        if(update($query,$values,'ss')){
            $_SESSION['uPic'] = $img;
            echo 1;
        }else{
            echo 0;
        }

    }

   ?>