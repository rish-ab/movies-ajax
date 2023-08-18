<!-- Breadcrumb-->
<div class="breadcrumb-holder">
  <div class="container-fluid">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= URL; ?>dashboard">Home</a></li>
      <li class="breadcrumb-item active">Update Profile</li>
    </ul>
  </div>
</div>

<section>
  <div class="container-fluid">
    <!-- Page Header-->
    <header> 
      <h1 class="h3 display">Update Profile</h1>
    </header>

    <style>
        #profile_image {
            width: 200px;
            border-radius: 50%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 20px;
        }
    </style>
    
        <form method="POST" action="<?= URL; ?>dashboard/update_profile" style="width: 100%;" enctype="multipart/form-data">
            <div class="row" style="margin-bottom: 50px;">
                <div class="offset-md-4 col-md-5">
                    <img id="profile_image" src="<?= URL . $user->profile_image; ?>" class="img-responsive" alt="Profile image">
                    <input type="file" id="input_profile_image" name="profile_image" accept="image/*" onchange="onFileSelected();">
                </div>
            </div>

            <script type="text/javascript">
                function onFileSelected() {
                    var image = document.getElementById("input_profile_image").files;
                    if (image.length == 0) {
                        console.log("Please select an image");
                        return;
                    }
                    image = image[0];
                
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('profile_image').setAttribute('src', e.target.result);
                    }
                    reader.readAsDataURL(image);
                }
            </script>
            
            <div class="row">
                <div class="col-md-5" style="display: inline-block;">
                    <fieldset>
                        <legend>Personal Details:</legend>
                        
                        <div class="form-group">
                            <label>First name</label>
                            <input name="first_name" class="form-control" value="<?= $user->firstname; ?>" autocomplete="off">
                        </div>
                    </fieldset>
                </div>
            </div>
            
            <input type="submit" class="btn btn-warning" value="Update Profile">
        </form>
  </div>
</section>