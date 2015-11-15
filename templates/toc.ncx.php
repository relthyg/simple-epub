<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">
    <head>
        <meta name="dtb:uid" content="<?php echo  $this->id  ?>"/>
        <meta name="dtb:depth" content="1"/>
    </head>

    <docTitle>
        <text><?php echo $this->title ?></text>
    </docTitle>

    <navMap>
        <navPoint id="title_page" playOrder="0">
            <navLabel>
                <text>Title Page</text>
            </navLabel>
            <content src="title_page.xhtml"/>
        </navPoint>

<?php foreach ($this->chapters as $k => $chapter):  ?>
        <navPoint id="chapter<?php echo $k ?>" playOrder="<?php echo $k+1 ?>">
            <navLabel>
                <text><?php echo $chapter['title'] ?></text>
            </navLabel>
            <content src="chap<?php echo $k ?>.xhtml"/>
        </navPoint>
<?php endforeach;  ?>

    </navMap>
</ncx>
