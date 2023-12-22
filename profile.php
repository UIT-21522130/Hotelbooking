<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php');?>    
    <title> <?php echo $settings_r['site_title']?> - PROFILE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>


</head>
<body class="bg-light">

    <?php
        require('inc/header.php');
        
        if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
            redirect('index.php');
        }


        $u_exist = select ("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'s');
    
        if(mysqli_num_rows($u_exist)==0){
            redirect('index.php');
        }
        $u_fetch = mysqli_fetch_assoc($u_exist);
    ?>

    <div class="container">
        <div class="row">
        
            <div class="col-12 my-5  px-4">
                <h2 class="fw-bold ">PROFILE</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
                </div>
            </div> 


            <div class="col-12 mb-5  px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info-form">
                        <h5 class="mb-3 fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" >Name</label>
                                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>                                
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" >Phone Number</label>
                                <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>" class="form-control shadow-none" required>                                
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" >Date of birth</label>
                                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control shadow-none" required>                                
                            </div>                            
                            <div class="col-md-16 mb-4">
                                <label class="form-label" >Address</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address'] ?></textarea>                             
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                    </form>
                </div>
            </div> 

                    <div class="col-md-4 mb-5  px-4">
                        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                            <form id="profile-form">
                                <h5 class="mb-3 fw-bold">Picture</h5>
                                <img src="<?php echo USERS_IMG_PATH.$u_fetch['profile'] ?>" class="rounded-circle img-fluid">
                                <div class="mb-3 fw-bold"></div>
                                
                                <label class="form-label">New Picture</label>
                                <input name="profile" accept=".jpg, .jpeg, .png, .webp" type="file" class="mb-4 form-control shadow-none">                                  
                                
                                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                            </form>
                        </div>
                    </div> 

                    <div class="col-md-8 mb-5  px-4">
                        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                            <form id="pass-form">
                                <h5 class="mb-3 fw-bold">Change Password</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" >New Password</label>
                                        <input name="new_pass" type="password"  class="form-control shadow-none" required>                                
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" >Confirm Password</label>
                                        <input name="confirm_pass" type="password" class="form-control shadow-none" required>                                
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                            </form>
                        </div>
                    </div> 

                </div>
            </div>

        </div>
    </div>


    


    <?php require('inc/footer.php');?>

    <script>
       let displayedUsername; // Khai báo biến displayedUsername

        let info_form = document.getElementById('info-form');

        info_form.addEventListener('submit', function(e){
            e.preventDefault();

            let data = new FormData();
            data.append('info_form','');
            data.append('name', info_form.elements['name'].value);
            data.append('phonenum', info_form.elements['phonenum'].value);
            data.append('address', info_form.elements['address'].value);
            data.append('dob', info_form.elements['dob'].value);

            let xhr= new XMLHttpRequest();
            xhr.open("POST","ajax/profile.php",true);

            xhr.onload= function(){
                if (this.responseText == 4) {
                    alert('error',"Phone is already registered!");
                }
                else if(this.responseText == 0){
                    alert('error',"No changes Made!");
                }
                else{
                    displayedUsername = this.responseText; // Gán giá trị cho displayedUsername
                    alert('success','Changes saved!');
                    // Tiếp tục thực hiện các hành động khác với biến displayedUsername nếu cần
                    var spanElement = document.getElementById("user-name");
                    spanElement.innerHTML = info_form.elements['name'].value;
                }
            }

            xhr.send(data);
        });


        let profile_form = document.getElementById('profile-form');

        profile_form.addEventListener('submit', function(e){
            e.preventDefault();

            let data = new FormData();
            data.append('profile_form','');
            data.append('profile', profile_form.elements['profile'].files[0]);

            let xhr= new XMLHttpRequest();
            xhr.open("POST","ajax/profile.php",true);

            xhr.onload = function() {
            console.log('Response:', this.responseText);

            if (this.responseText == 'inv_img') {
                alert('error', "Only JPG, WEBP, PNG image are allowed!");
            } 
            else if (this.responseText == 'upd_failed') {
                alert('error', "Image upload failed!");
            } 
            else if (this.responseText == 0) {
                alert('error', "Updation Failed!");
            } 
            else {
                console.log('Redirecting to:', window.location.pathname);
                window.location.href = window.location.pathname;
            }
        };


            xhr.send(data);
        });

        let pass_form = document.getElementById('pass-form');

        pass_form.addEventListener('submit', function(e){
            e.preventDefault();

            let new_pass = pass_form.elements['new_pass'].value;
            let confirm_pass = pass_form.elements['confirm_pass'].value;

            if(new_pass != confirm_pass){
                alert('error', 'Password do not match!');
                return false;
            }

            let data = new FormData();
            data.append('pass_form','');
            data.append('new_pass', new_pass);
            data.append('confirm_pass', confirm_pass);


            let xhr= new XMLHttpRequest();
            xhr.open("POST","ajax/profile.php",true);

            xhr.onload = function() {
            console.log('Response:', this.responseText);

            if (this.responseText == 'mismatch') {
                alert('error', "Password do not match!");
            } 
            else if (this.responseText == 0) {
                alert('error', "Updation Failed!");
            } 
            else {
                alert('success', 'Changes saved!');
                pass_form.reset();
            }
        };


            xhr.send(data);
        });


    </script>


</body> 
</html>