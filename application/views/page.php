<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$data['title']?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<style>
.jumbotron{
    background-color: #008391 !important;
}
.jumbotron h1 {
    color: #fff;
}
</style>

<!--<div class="jumbotron text-center">
  <h1><?=$data['title']?></h1>
</div>-->
  
<div class="container">
    
  <div class="row">
    <div class="col-sm-12">
        <?=$data['content']?>
    </div>
  </div>
  
</div>

</body>
</html>
