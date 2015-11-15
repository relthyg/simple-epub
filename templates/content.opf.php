<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<package xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/" unique-identifier="Id" version="3.0">

    <metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">
        <dc:title><?php echo $this->title ?></dc:title>
        <dc:creator><?php echo $this->author ?></dc:creator>
        <dc:language><?php echo $this->language ?></dc:language>
        <dc:identifier id="Id"><?php echo $this->id ?></dc:identifier>
        <meta property="dcterms:modified"><?php echo $this->timestamp ?></meta>
    </metadata>

    <manifest>
        <item id="toc" properties="nav" href="toc.xhtml" media-type="application/xhtml+xml" />
        <item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" />
        <item id="style" href="stylesheet.css" media-type="text/css" />
        <item id="titlepage" href="title_page.xhtml" media-type="application/xhtml+xml" />
<?php foreach ($this->chapters as $k => $chapter):  ?>
        <item id="chapter<?php echo $k ?>" href="chap<?php echo $k ?>.xhtml" media-type="application/xhtml+xml" />
<?php endforeach;  ?>
    </manifest>

    <spine toc="ncx">
        <itemref idref="titlepage" />
<?php foreach ($this->chapters as $k => $chapter):  ?>
        <itemref idref="chapter<?php echo $k ?>" />
<?php endforeach;  ?>
    </spine>
</package>
