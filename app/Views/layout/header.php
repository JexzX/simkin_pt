<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMKIN UIN Salatiga' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <style>
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        margin: 5px 0;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar .nav-link.active {
        background: #667eea;
        color: white;
    }

    .sidebar .nav-link i {
        width: 25px;
        margin-right: 10px;
    }

    .navbar-top {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 15px 20px;
    }

    .content-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
    }

    .card-stats {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s;
    }

    .card-stats:hover {
        transform: translateY(-5px);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        color: white;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .table-custom {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
    }
    </style>
</head>

<body>