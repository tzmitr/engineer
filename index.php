<?php
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Something went wrong.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter a first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter a last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
        $email = trim($_POST["email"]);
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["gender"]))) {
        $gender_err = "Please select a gender.";
    } else {
        $gender = trim($_POST["gender"]);
    }
    $user_type = $_POST["user_type"];
    $address = trim($_POST["address"]);
    $hobby1 = trim($_POST["hobby1"]);
    $hobby3 = trim($_POST["hobby3"]);
    $hobby2 = trim($_POST["hobby2"]);

    $birth_month = strlen($_POST["birth_month"]) == 1 ? '0' . $_POST["birth_month"] : $_POST["birth_month"];
    $birth_year = $_POST["birth_year"];
    $birth_day = strlen($_POST["birth_day"]) == 1 ? '0' . $_POST["birth_day"] : $_POST["birth_day"];
    $date = "$birth_year-$birth_month-$birth_day";
    $birthday = DateTime::createFromFormat('Y-m-d', $date);
    if ($birthday->format('Y-m-d') !== $date) {
        $birth_day_err = "Please enter a valid date.";
    };

    if (!empty($_FILES["picture"]["name"])) {
        if (!getimagesize($_FILES["picture"]["tmp_name"])) {
            $picture_err = "File is not an image.";
            $uploadOk = 0;
        }
        $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif") {
            $picture_err = "Sorry, only JPG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        $picture = (file_get_contents($_FILES['picture']['tmp_name']));
    }


    if (empty($username_err) && empty($password_err) && empty($first_name_err) && empty($last_name_err)
        && empty($gender_err) && empty($email_err) && empty($birth_day_err) && empty($picture_err)) {
        $sql = "INSERT INTO users (user_type, picture, first_name, last_name, email, username, password, address, gender, birhday, hobby1, hobby2, hobby3)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssssssiii", $param_user_type, $param_picture, $param_first_name
                , $param_last_name, $param_email, $param_username, $param_password, $param_address, $param_gender, $param_birhday, $hobby1, $hobby2, $hobby3);
            $param_user_type = $user_type;
            $param_picture = $picture;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_address = $address;
            $param_gender = $gender;
            $param_birhday = $birthday->format('Y-m-d');

            if (mysqli_stmt_execute($stmt)) {
                echo "<script type='text/javascript'>alert('User has been registered successfully!')</script>";
            } else {
                echo "Something went wrong.";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Something went wrong.";
        }

    }
    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>User Type</label> <select name='user_type'>
        <option <?php echo($user_type == "Admin" ? "selected" : "") ?> value='Admin'>Admin</option>
        ";
        <option <?php echo($user_type == "Superadmin" ? "selected" : "") ?> value='Superadmin'>Superadmin</option>
        ";
    </select></br>

    <label>First Name</label>
    <input type="text" name="first_name" value="<?php echo $first_name; ?>"> <?php echo $first_name_err; ?></br>
    <label>Last Name</label>
    <input type="text" name="last_name" value="<?php echo $last_name; ?>"> <?php echo $last_name_err; ?></br>
    <label>Profile Picture (only jpg, gif, png allowed)</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
    <input type="file" name="picture"/><?php echo $picture_err; ?></br>
    <label>Email</label>
    <input type="text" name="email" value="<?php echo $email; ?>">  <?php echo $email_err; ?></br>
    <label>Username</label>
    <input type="text" name="username" value="<?php echo $username; ?>"> <?php echo $username_err; ?></br>
    <label>Password</label>
    <input type="Password" name="password"> <?php echo $password_err; ?></br>
    <label>Gender:</label>
    <label>male</label>
    <input type="radio" name="gender" value="m" <?php echo $gender == "m" ? "checked" : ""; ?> >
    <label>female</label>
    <input type="radio" name="gender"
           value="f" <?php echo ($gender == 'f') ? 'checked' : ''; ?>> <?php echo $gender_err; ?></br>
    <label>Address</label> <textarea name="address" cols="50" rows="4"> <?php echo $address; ?> </textarea></br>
    <label>Date of Birth</label>
    <select name="birth_month">
        <?php for ($m = 1; $m <= 12; ++$m) {
            $month_label = date('F', mktime(0, 0, 0, $m, 1));
            echo '<option  ' . ($birth_month == $m ? "selected" : "") . ' value=' . $m . '>' . $month_label . '</option>';
        }
        ?>
    </select>
    <select name="birth_day">
        <?php
        $start_date = 1;
        $end_date = 31;
        for ($j = $start_date; $j <= $end_date; $j++) {
            echo '<option ' . ($birth_day == $j ? "selected" : "") . ' value=' . $j . '>' . $j . '</option>';
        }
        ?>
    </select>
    <select name="birth_year">
        <?php
        $year = date('Y');
        $min = $year - 60;
        $max = $year;
        for ($i = $max; $i >= $min; $i--) {
            echo '<option ' . ($birth_year == $i ? "selected" : "") . ' value=' . $i . '>' . $i . '</option>';
        }
        ?>
    </select>
    <?php echo $birth_day_err; ?></br>

    <label>hobby1</label> <input type="checkbox" name="hobby1" value=1 <?php echo empty($hobby1) ? "" : "checked"; ?> >
    <label>hobby2</label> <input type="checkbox" name="hobby2" value=1 <?php echo empty($hobby2) ? "" : "checked"; ?> >
    <label>hobby3</label> <input type="checkbox" name="hobby3" value=1 <?php echo empty($hobby3) ? "" : "checked"; ?> >

    <input type="submit" class="btn btn-primary" value="Submit">
</form>
<p><a href="admin/login.php">Login here</a>.</p>
</body>
</html>