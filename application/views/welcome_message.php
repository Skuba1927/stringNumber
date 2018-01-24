<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"  crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"  crossorigin="anonymous"></script>
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 21px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
    form {
        margin: 10px;
    }
	</style>
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Число</label>
            <input type="text" class="form-control" value="{entered_number}" id="exampleInputEmail1" placeholder={enter_number} name="number">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Валюта</label>
            <select class="form-control" name="currency">
                <option value="rur" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'rur'){echo 'selected';} ?>>Рубли</option>
                <option value="uah" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'uah'){echo 'selected';} ?>>Гривна</option>
                <option value="usd" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'usd'){echo 'selected';} ?>>Долар</option>
                <option value="eur" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'eur'){echo 'selected';} ?>>Евро</option>
                <option value="pln" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'pln'){echo 'selected';} ?>>Злотый</option>
                <option value="gel" <?php if (isset($_POST['currency']) && $_POST['currency'] == 'gel'){echo 'selected';} ?>>Лари</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Язык</label>
            <select class="form-control form-control-sm" name="language">
                <option value="ru" <?php if (isset($_POST['language']) && $_POST['language'] == 'ru'){echo 'selected';} ?>>Русский</option>
                <option value="en" <?php if (isset($_POST['language']) && $_POST['language'] == 'en'){echo 'selected';} ?>>English</option>
                <option value="gr" <?php if (isset($_POST['language']) && $_POST['language'] == 'gr'){echo 'selected';} ?>>Грузинский</option>
                <option value="ua" <?php if (isset($_POST['language']) && $_POST['language'] == 'ua'){echo 'selected';} ?>>Украинский</option>
                <option value="es" <?php if (isset($_POST['language']) && $_POST['language'] == 'es'){echo 'selected';} ?>>Испанский</option>
                <option value="pl" <?php if (isset($_POST['language']) && $_POST['language'] == 'pl'){echo 'selected';} ?>>Польский</option>
            </select>
        </div>

        <br/>
        <input type="submit">
    </form>

	<p class="footer"><strong>{number_string}</strong></p>
</div>

</body>
</html>