<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Stok Minimum</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #e74c3c;
        }
        .detail {
            margin-top: 20px;
        }
        .detail p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚠️ Stok Barang Menipis</h2>

        <div class="detail">
            <p><strong>Nama Barang:</strong> {{ $data['nama_barang'] }}</p>
            <p><strong>Spesifikasi:</strong> {{ $data['spek'] }}</p>
            <p><strong>Stok Saat Ini:</strong> {{ $data['stock'] }}</p>
            <p><strong>Stok Minimum:</strong> {{ $data['min_stock'] }}</p>
        </div>

        <p style="margin-top: 20px;">Segera lakukan pengisian ulang stok untuk mencegah kehabisan barang.</p>

        <div class="footer">
            <p>Pesan ini dikirim otomatis oleh sistem inventory.</p>
        </div>
    </div>
</body>
</html>
