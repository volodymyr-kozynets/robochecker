<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require_once 'checker.php';
?>

    


<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <title>Robochecker</title>
    </head>
    <body>
        <section class="container">
            <form action="" method="post" role="form" class="form-inline">
                <div class="form-group">
                <input type="text" name="url" required class="form-control">
                <button type="submit" class="btn btn-default">Check</button>
                </div>
            </form>
            
<?php
if(isset($_REQUEST['url'])){ 
    
    $checker = new Checker($_REQUEST['url']);

?>
            
    
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Название проверки</td>
                        <td>Статус</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Проверка наличия файла robots.txt</td>
                        <td><?= $checker->robot_ok ?></td>
                    </tr>
                    <tr <?php if($checker->get_directive('Host') == 'ОШИБКА'): ?>  class="bg-warning" <?php endif; ?> >
                        <td>Проверка указания директивы Host:</td>
                        <td><?=$checker->get_directive('Host')?></td>
                    </tr>
                    <tr <?php if($checker->count_directive('Host') == 'ОШИБКА'): ?>  class="bg-warning" <?php endif; ?> >
                        <td>Проверка количества директив Host, прописанных в файле:</td>
                        <td><?=$checker->count_directive('Host')?></td>
                    </tr>
                    <tr <?php if($checker->get_size($_REQUEST['url']) == 'ОШИБКА'): ?>  class="bg-warning" <?php endif; ?> >
                        <td>Проверка размера файла robots.txt:</td>
                        <td><?=$checker->get_size($_REQUEST['url'])?></td>
                    </tr>
                    <tr <?php if($checker->get_directive('Sitemap') == 'ОШИБКА'): ?>  class="bg-warning" <?php endif; ?> >
                        <td>Проверка указания директивы Sitemap:</td>
                        <td><?=$checker->get_directive('Sitemap')?></td>
                    </tr>
                    <tr <?php if($checker->get_response($_REQUEST['url']) == 'ОШИБКА'): ?>  class="bg-warning" <?php endif; ?> >
                        <td>Проверка кода ответа сервера для файла robots.txt:</td>
                        <td><?=$checker->get_response($_REQUEST['url'])?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </body>
</html>

<?php } ?>