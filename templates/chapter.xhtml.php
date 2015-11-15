<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title><?php echo $this->author ?> - <?php echo $this->title ?></title>
      <link rel="stylesheet" href="stylesheet.css" type="text/css" />
    </head>

    <body class="chapter">
        <h1><?php echo $this->current_chapter['title'] ?></h1>
        <?php echo $this->current_chapter['content'] ?>
    </body>
</html>
