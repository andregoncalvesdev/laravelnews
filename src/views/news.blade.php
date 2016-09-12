<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <ul>
      @foreach($news as $item)
        <li>$item->title</li>
      @endforeach
    </ul>
  </body>
</html>
