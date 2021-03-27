<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/develhopper/bookstrap/dist/bookstrap.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/samim-font@v4.0.5/dist/font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/app.css">
    <style>
        html,body{
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container-fluid full-h">
        <div class="row center full-h" style="align-items: center;">
            <div class="col-1 center">
                <h1 class="color-<?= $color ?>"><?= $message ?></h1>
                <a href="<?= $link ?>" class="btn btn-<?= $color ?>">بازگشت</a>
            </div>
        </div>
    </div>
</body>
</html>
