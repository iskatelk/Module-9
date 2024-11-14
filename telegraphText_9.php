<?php

abstract class Storage {
    public $storage;
    abstract function create(TelegraphText $object);

    abstract function read($slug);

    abstract function update($slug,$text,$str);

    abstract function delete($slug) ;

    abstract function list(&$resultSearch);


        }

abstract class View
{
    public $storage;
    public function __construct()
    {
        $this->storage = Storage->storage;
    }

    abstract function displayTextById($id);
    abstract function displayTextByUrl($url);
}


 abstract class User {
    public $id;
    public $name;
    public $role;

    abstract function getTextsToEdit();
}


class FileStorage extends Storage
{
    public $directory = 'C:\xampp\htdocs\Work9';
    public $slug;
    public $text;


    public function create(TelegraphText $object)
    {
         $str = $object->slug;
         $slug1 = substr($str,0,strlen($str)-4);
        $i = 1;

        $slug = $slug1. '_' . date('d.m.Y H')."_{$i}.txt" ;
      while (file_exists($slug))
        {
            $i++;
           $slug = $slug1. '_' . date('d.m.Y H')."_{$i}.txt";

        }

        $testText1 = serialize($object);
        file_put_contents("{$slug}", "{$testText1}");
        $this->slug = $slug;
        return $this->slug;
    }

    public function read($slug)
    {
        return unserialize(file_get_contents("{$slug}"));

    }

    public function update($slug,$text,$str)
    {
        $textResult = unserialize(file_get_contents("{$slug}"));

        $textResult->$text = $str;
        $textResult2 = serialize($textResult);
        file_put_contents("{$slug}", "{$textResult2}");
    }

    public function delete($slug)
    {
        unlink("{$slug}");
            }

    public function list(&$resultSearch)
    {
        $search = scandir($this->directory);
            for($i=3;$i<count($search)-1;$i++) {
                if (sprintf($search[$i])!='telegraphText_9.php') {

                    $resultSearch[$i] = unserialize(file_get_contents(sprintf($search[$i])));
                }
            }
        return $resultSearch;
    }
}


class  TelegraphText
{
    public $title; // заголовок текста;
    public $text; // текст;
    public $author; // имя автора;
    public $published; // дата и время последнего изменения текста;
    public $slug;

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('d.m.Y H:i');
    }


    function loadText()
    {
        if (filesize($this->slug) > 0) {
            $str = file_get_contents("{$this->slug}");

            return unserialize($str);
        }
    }

    function storeText(array $testText)
    {
        $str = serialize($testText);
        //die($slug);
        file_put_contents("{$this->slug}", $str);
    }

    function editText(array &$textStorage): bool
    {
        if (array_key_exists('title', $textStorage)) {
            $replacements1 = [
                'title' => $this->title,
                'text' => $this->text
            ];

            $textStorage = array_replace($textStorage, $replacements1);

            echo "Запись изменена" . PHP_EOL;
            return true;
        }
        echo "Записи нет" . PHP_EOL;
        return false;
    }
}
$resultSearch = [];
$telegraph1 = new TelegraphText('Petr', 'test2.txt');
$telegraph2 = new FileStorage;

$telegraph1->title = 'Privet3';
$telegraph1->text = 'Hi, World3';



$telegraph2->create($telegraph1);
//$telegraph2->update('test2_20.12.2022 19_2.txt', 'text','Hi, World5!');

//$telegraph2->list($resultSearch);
//var_dump($resultSearch);

//$testText2 = $telegraph2->read('test_20.12.2022 10_1.txt');
//echo $testText2.PHP_EOL;
//$telegraph2->delete('test_20.12.2022 10_2.txt');
