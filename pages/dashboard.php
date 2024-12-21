<?php
include '../init.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include '../templates/header.php'; ?>
<style>
    body {
        background: linear-gradient(to right, #1e3c72, #2a5298);
        min-height: 100vh;
        margin: 0;
        font-family: 'Roboto', sans-serif;
    }

    .dashboard-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        background: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 500px;
        width: 100%;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        padding-bottom: 2%;
        justify-content: center;
        margin: auto;
    }

    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .dashboard-card-header {
        background: linear-gradient(to right, #1e3c72, #2a5298);
        color: #ffffff;
        padding: 30px;
        border-radius: 20px 20px 0 0;
    }

    .dashboard-card-header h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
    }

    .dashboard-card-body {
        padding: 40px;
        text-align: center;
    }

    .dashboard-card-body h1 {
        font-size: 2rem;
        margin-bottom: 15px;
        font-weight: bold;
        color: #1e3c72;
    }

    .dashboard-card-body p {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 25px;
    }

    .btn-custom {
        background: linear-gradient(to right, #1e3c72, #2a5298);
        color: #ffffff;
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
    }

    .btn-custom:hover {
        background: linear-gradient(to right, #2a5298, #1e3c72);
        box-shadow: 0 5px 15px rgba(42, 82, 152, 0.4);
    }

    .dashboard-card-footer {
        background: #f8f9fa;
        padding: 15px;
        text-align: center;
        border-radius: 0 0 20px 20px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .dashboard-card-footer small {
        font-size: 0.85rem;
    }
</style>

<div class="container">
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3>Welcome to Your Dashboard</h3>
        </div>
        <div class="dashboard-card-body">
            <h1>Hello, <?= htmlspecialchars($_SESSION['user']['firstname']); ?>!</h1>
            <p class="lead">We're delighted to see you here. Explore your account and manage your preferences with ease.</p>
            <a href="logout.php" class="btn btn-custom">Logout</a>
        </div>
        <div class="dashboard-card-footer">
            <small>© <?= date('Y'); ?> Lux SOfTech - Thats What I Prefered(TWIP)</small>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
