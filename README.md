# SimpleEPUB

This is a stupidly simple PHP class for generating valid [EPUB 3.0](http://idpf.org/epub/30) .epub-files.

## Quickstart

* Download the zipball or clone the repository.
* Point your browser to `index.php` to receive a small sample .epub file.

## Usage
By having a look into `index.php` you will find out that it's more or less self-explaining.

* Create an instance and use setters to set all properties but `filename` and `timestamp` which are generated automatically.
* Make sure that `tmp_dir` exists and is writable and that all templates can be found in `templates_dir`.
* Add chapter(s) using method `add_chapter($arrChapter)` like this:
```
add_chapter(array(
      ['title'] => "YourTitle",
      ['content'] => "YourContent"
));
```
* Create the epub file using method `bind()`.
* Optional: Provide the file for download using method `deliver()`.
