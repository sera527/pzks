<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Syntax Validator</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <div class="container">
        <form action="validate.php" method="post">
            <div class="form-group">
                <label for="arithmeticExpression">Введіть арифметичний вираз</label>
                <input required type="text" class="form-control" id="arithmeticExpression" placeholder="(1+2)(12-8)/3(6+7)" name="expression" value="<?php if(isset($_GET["expression"])){echo $_GET["expression"];} ?>">
            </div>
            <button type="submit" class="btn btn-default">Перевірка виразу</button>
        </form>
    </div>
</body>
</html>