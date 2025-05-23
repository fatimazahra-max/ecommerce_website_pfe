<?php
include('../includes/connect.php');
include('../functions/common_function.php');
@session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce User Login Page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>

    <div class="register">
        <div class="container py-3">
            <h2 class="text-center mb-4">User Login</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="post" class="d-flex flex-column gap-4">
                        <!-- Username Field -->
                        <div class="form-outline">
                            <label for="user_username" class="form-label">Username</label>
                            <input type="text" placeholder="Enter your username" autocomplete="off" required="required" name="user_username" id="user_username" class="form-control">
                        </div>
                        <!-- Password Field -->
                        <div class="form-outline">
                            <label for="user_password" class="form-label">Password</label>
                            <input type="password" placeholder="Enter your password" autocomplete="off" required="required" name="user_password" id="user_password" class="form-control">
                        </div>
                        
                        <div>
                            <input type="submit" value="Login" class="btn btn-primary mb-2" name="user_login">
                            <p>
                                Don't have an account? <a href="user_registration.php" class="text-primary text-decoration-underline"><strong>Register</strong></a>
                            </p>
                        </div>
                        <div class="form-outline mb-4">
                        <a href="../index.php" class="btn btn-primary">Back</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>

<?php
if (isset($_POST['user_login'])) {
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];

    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username'";
    $select_result = mysqli_query($con, $select_query);
    $row_count = mysqli_num_rows($select_result);

    if ($row_count > 0) {
        $row_data = mysqli_fetch_assoc($select_result);
        $stored_password = $row_data['user_password'];
        $user_role = $row_data['role'];
        $user_ip = getIPAddress();

        if (password_verify($user_password, $stored_password)) {
            $_SESSION['username'] = $user_username;
            $_SESSION['role'] = $user_role;
            $_SESSION['admin_image'] = $row_data['user_image'];
            $_SESSION['admin_id'] = $row_data['user_id'];

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

            if ($user_role === 'admin') {
                echo "
                <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Welcome Admin 👑',
                    text: 'Redirecting to admin dashboard...',
                    confirmButtonColor: '#c02675'
                }).then(() => {
                    window.location.href = '../admin/index.php';
                });
                </script>";
                exit();
            }

            $select_cart_query = "SELECT * FROM `card_details` WHERE ip_address='$user_ip'";
            $select_cart_result = mysqli_query($con, $select_cart_query);
            $row_cart_count = mysqli_num_rows($select_cart_result);

            if ($row_cart_count > 0) {
                echo "
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful 🎉',
                    text: 'You have items waiting in your cart!',
                    confirmButtonColor: '#c02675'
                }).then(() => {
                    window.location.href = '../index.php';
                });
                </script>";
                exit();
            } else {
                echo "
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome Back 💖',
                    text: 'Redirecting to your profile...',
                    confirmButtonColor: '#c02675'
                }).then(() => {
                    window.location.href = 'profile.php';
                });
                </script>";
                exit();
            }
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Wrong Password ❌',
                text: 'Try again, soldier!',
                confirmButtonColor: '#EF4444'
            });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'warning',
            title: 'Username Not Found 🚫',
            text: 'Are you sure you registered?',
            confirmButtonColor: '#FBBF24'
        });
        </script>";
    }
}
?>

