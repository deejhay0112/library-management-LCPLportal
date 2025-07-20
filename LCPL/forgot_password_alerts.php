<?php
if (isset($_GET['error'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '" . htmlspecialchars($_GET['error']) . "'
        });
    </script>";
} elseif (isset($_GET['success'])) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '" . htmlspecialchars($_GET['success']) . "'
        }).then(() => {
            window.location.href = 'admin-login.php';
        });
    </script>";
}
?>
