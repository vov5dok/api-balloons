<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BALLOONS - Информация об отмене оплаты</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #ffffff;">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h4 class="mb-3 mt-0">BALLOONS</h4>
        </div>
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Информация об отмене оплаты</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <li class="list-group-item">Товар: {{ $product->description }}</li>
                        <li class="list-group-item">Цена: {{ number_format($product->price, 2, '.', '') }} руб.</li>
                        <li class="list-group-item">Количество: {{ $product->count }}</li>
                    </ul>
                    <div class="alert alert-danger" role="alert">
                        Оплата была отменена.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
