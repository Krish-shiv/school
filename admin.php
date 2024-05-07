
<?php include('header_admin_dashboard.php') ?>
<style>
    .centered-content {
        text-align: center;
    }

    .centered-image {
        display: block;
        margin: 0 auto;
    }
</style>

<div class="centered-content">
    <img src="images/logo/logo_601_601.jpg" alt="LOGO" class="centered-image">
    <h1>Welcome to the Admin Page, <?php echo $_SESSION['username']; ?>!</h1>
</div
<?php include('footer_admin_dashboard.php') ?>