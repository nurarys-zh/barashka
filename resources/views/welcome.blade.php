<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Страница загона
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/custom.css">

    <script src="/js/jquery-3.1.1.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <fieldset>
                <legend>ФЕРМА БАРАШЕК</legend>
                <span>День:</span>
                <p id="day">0</p>
                <?$index = 1?>
                @foreach ($paddock as $key => $list)
                    <div class="col-md-6">
                        <h3>Загон №{{ $key  }}</h3>

                        <div id="paddock{{ $key  }}" class="zagon">
                            @foreach ($list as $sheepId)
                                <div id="sheep{{ $sheepId }}" class="name"></div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </fieldset>
            <div class="col-md-6">
                <form>
                    <div class="form-group">
                        <button id="reset" class="btn btn-danger">Очистить</button>
                    </div>
                    <div class="form-group">
                        <select name="command" id="">
                            <option value="add">Добавить</option>
                            <option value="sleep">Убить</option>
                        </select>
                        <input type="submit" name="send" value="Выполнить">
                    </div>
                </form>
            </div>

            <div class="col-md-7 info-block">
                <p><a href="/stat/">Общая статистика</a></p>
                <p class="bg-danger">Очистить = Очищает таблицу барашек и историю</p>
                <p class="bg-success">Добавить = Добавляет одну барашку</p>
                <p class="bg-info">Убить = Убирает одну барашку. Если в каком то загоне осталось одна барашка, переводит
                    из самой насленной</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {

        function add() {
            $.ajax({
                url: '/reproduce/',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#paddock' + data.paddock).append(
                        '<div id="sheep' + data.sheep_id + '" class="name"></div>'
                    );
                }
            });
        }

        function sleep() {
            $.ajax({
                url: '/sleep/',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#sheep' + data.sleep.id).hide();
                    $('#sheep' + data.moved.id).appendTo('#paddock' + data.moved.to);
                }
            });
        }

        $('input[type=submit]').on('click', function () {
            var cmd = $('select').val();
            if (cmd == 'add') {
                add();
            } else if (cmd == 'sleep') {
                sleep();
            }
            return false;
        });

        $('#reset').on('click', function () {
            $.ajax({
                url: '/reset',
                success: function () {
                    window.location.reload();
                }
            });
            setDay(0);
            clearInterval(timer);
        });

        var timer = setInterval(function () {
            var day = localStorage.getItem('day') ? localStorage.getItem('day') : 0;
            day = parseInt(day) + 1;
            setDay(day);
            if (day % 10 == 0 && day > 0) {
                add();
            }
            if (day % 20 == 0 && day > 0) {
                sleep();
            }
        }, 1000);

        function setDay(day) {
            localStorage.setItem('day', day);
            $('#day').html(day);
        }
    });
</script>
</body>
<footer>
    <center>© Zhumay Nurarys 2021</center>
</footer>
</html>