<?php

/**
 * Simple Class for creating valid epub 3.0.1 files
 * @author Martin Güthler
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/relthyg/simple-epub
 */
class SimpleEpub
{
    /*
     * Book's Properties
     */
    private $title = null; // required
    private $author = null; // required
    private $chapters = array(); // not required
    private $language = null; // required
    private $id = null; // required
    private $css = null; // not required
    private $toc_title = null; // required
    private $timestamp = null; // required, but set automatically in constructor.

    /*
     * Folder Structure
     */
    private $templates_dir = null;
    private $tmp_dir = null;
    private $filename = null; // generated automatically later on.

    /**
     * Constructor. Sets required property "timestamp" to "now".
     */
    public function __construct()
    {
        $this->timestamp = explode("+", date("c"))[0] . "Z";
    }

    /**
     * Setter
     * @param $title
     */
    public function set_title($title)
    {
        $this->title = $title;
    }

    /**
     * Setter
     * @param $author
     */
    public function set_author($author)
    {
        $this->author = $author;
    }

    /**
     * Setter
     * @param $language
     */
    public function set_language($language)
    {
        $this->language = $language;
    }

    /**
     * Setter
     * @param $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Setter
     * @param $css
     */
    public function set_css($css)
    {
        $this->css = $css;
    }

    /**
     * Setter
     * @param $toc_title
     */
    public function set_toc_title($toc_title)
    {
        $this->toc_title = $toc_title;
    }

    /**
     * Setter
     * @param $dir
     */
    public function set_templates_dir($dir)
    {
        $this->templates_dir = $dir;
    }

    /**
     * Setter
     * @param $dir
     */
    public function set_tmp_dir($dir)
    {
        $this->tmp_dir = $dir;
    }

    /**
     * Adds a chapter to the ebook.
     * @param $arr An array including ['title'] and ['content'] of the chapter
     */
    public function add_chapter($arr)
    {
        $chapter = array();
        $chapter['title'] = $arr['title'];
        $chapter['content'] = $arr['content'];
        $this->chapters[] = $chapter;
    }

    /**
     * Creates a file for the epub by given template
     * @param $filename The filename of the file to be created
     * @param $template The template being used.
     */
    public function create_file($filename, $template)
    {
        ob_start();
        require($this->templates_dir . $template);
        $output = ob_get_contents();
        ob_end_clean();

        file_put_contents($this->tmp_dir . $filename, $output);
    }

    /**
     * Creates the epub structure and the file
     * @throws Exception
     */
    public function bind()
    {
        $this->handle_directories();

        $this->filename = $this->author . ' - ' . $this->title . '.epub';

        $this->create_file('OEBPS/content.opf', 'content.opf.php');
        $this->create_file('OEBPS/toc.ncx', 'toc.ncx.php');
        $this->create_file('OEBPS/toc.xhtml', 'toc.xhtml.php');
        $this->create_file('OEBPS/title_page.xhtml', 'title_page.xhtml.php');
        $this->create_file('META-INF/container.xml', 'container.xml.php');

        foreach ($this->chapters as $k => $this->current_chapter)
        {
            $chapterFilename = 'OEBPS/chap' . $k . '.xhtml';
            $this->create_file($chapterFilename, 'chapter.xhtml.php');
        }

        file_put_contents($this->tmp_dir . 'mimetype', 'application/epub+zip');
        file_put_contents($this->tmp_dir . 'OEBPS/stylesheet.css', $this->css);

        $files_to_zip = array(
            'mimetype',
            'META-INF/container.xml',
            'OEBPS/stylesheet.css',
            'OEBPS/toc.ncx',
            'OEBPS/toc.xhtml',
            'OEBPS/title_page.xhtml',
            'OEBPS/content.opf'
        );

        foreach ($this->chapters as $k => $this->chapter)
        {
            $files_to_zip[] = 'OEBPS/chap' . $k . '.xhtml';
        }

        $this->create_zip($this->tmp_dir, $files_to_zip, $this->tmp_dir . $this->filename, true);
    }

    /**
     * provides the epub file as download.
     */
    public function deliver()
    {
        $filesize = filesize($this->tmp_dir . $this->filename);
        header("Content-Type: archive/zip");
        header("Content-Length: $filesize");
        header("Content-Disposition: attachment; filename=\"$this->filename\"");
        readfile($this->tmp_dir . $this->filename);
    }

    /**
     * Checks if directory properties are set and if directories are writeable
     * @throws Exception
     */
    private function handle_directories()
    {
        if (!isset($this->templates_dir))
        {
            throw new Exception('Class SimpleEpub: property "templates_dir" not set.');
        }

        if (!isset($this->tmp_dir))
        {
            throw new Exception('Class SimpleEpub: property "tmp_dir" not set.');
        }

        if (!is_writable($this->tmp_dir))
        {
            throw new Exception('Class SimpleEpub: tmp_dir not writable.');
        }

        if (!file_exists($this->tmp_dir . '/OEBPS'))
        {
            if (!mkdir($this->tmp_dir . '/OEBPS'))
            {
                throw new Exception('Class SimpleEpub: Cannot create directory ' . $this->tmp_dir . '/OEBPS');
            }
        }

        if (!file_exists($this->tmp_dir . '/META-INF'))
        {
            if (!mkdir($this->tmp_dir . '/META-INF'))
            {
                throw new Exception('Class SimpleEpub: Cannot create directory ' . $this->tmp_dir . '/OEBPS');
            }
        }
    }

    /**
     * Creates a zip file
     * @param string $source_dir The directory where the files to zip are stored in
     * @param array $files The files to zip
     * @param string $destination The filename of the zip file that should be created
     * @param bool|false $overwrite Whether an existing file should be overwritten or not
     * @return bool Is true if zip was created. Otherwise false.
     * @author David Walsh
     * @link http://davidwalsh.name/create-zip-php
     */
    private function create_zip($source_dir = '', $files = array(), $destination = '', $overwrite = false)
    {

        if (!file_exists($destination))
        {
            $overwrite = false;
        }

        if (file_exists($destination) && !$overwrite)
        {
            return false;
        }

        $valid_files = array();

        if (is_array($files))
        {
            foreach ($files as $file)
            {
                if (file_exists($source_dir . $file))
                {
                    $valid_files[] = $file;
                }
            }
        }

        if (count($valid_files))
        {
            $zip = new ZipArchive();
            if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
            {
                return false;
            }

            foreach ($valid_files as $file)
            {
                $zip->addFile($source_dir . $file, $file);
            }

            $zip->close();

            return file_exists($destination);
        } else
        {
            return false;
        }
    }
}

?>
