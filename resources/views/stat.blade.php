<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Страница статистики
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap.css" >
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-info">
                Всего барашек: {{ $all  }}
            </p>
            <p class="bg-success">Живых барашек: {{ $live  }}</p>
            <p class="bg-danger">Усыпленных барашек: {{ $sleep }}</p>
            <p class="bg-primary">Самый населеный загон №: {{ $max->paddock }}. Количество барашек в загоне: {{ $max->total }}</p>
            <p class="bg-primary">Самый маленький загон №: {{ $min->paddock }}. Количество барашек в загоне: {{ $min->total }}</p>
        </div>
    </div>
</div>

<style>
    p{
        padding: 2em;
    }
</style>
</body>
<footer>
    <center>© Zhumay Nurarys 2021</center>
</footer>
</html>