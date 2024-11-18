<?php

abstract class Storage
{
    abstract function create(TelegraphText $object);

    abstract function read();

    abstract function update();

    abstract function delete();

    abstract function list();
}

class FileStorage extends Storage
{
    private $directory = 'C:/xampp/htdocs/MODULE-9/storage';
    public $listObjects = [];
    public function create(TelegraphText $object)
    {
        $inputSlug = $object->slug;
        $slugTemp = explode(".", $inputSlug);

        $i = 1;
        do {
            $slug = $slugTemp[0] . '_' . date('d-m-Y') . "_{$i}.txt";
            $i++;
        }
        while (file_exists("{$this->directory}" . "/" . "{$slug}"));

        $text = serialize($object);
        file_put_contents("{$this->directory}" . "/" . "{$slug}", "{$text}");
        return $this->slug = $slug;
    }

    public function read()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function list()
    {
        $listFiles = scandir($this->directory);
        $resultList = array_filter($listFiles, fn($item) => !is_dir($item));

        foreach ($resultList as $item) {
            $this->listObjects[] = unserialize(file_get_contents($this->directory . "/" . $item));
        }

    }
}

class TelegraphText
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
            $text = unserialize(file_get_contents("{$this->slug}"));

            echo "Пост считан \n";
            return $text;
        } else {
            echo "Пост пуст \n";
        }
    }

    function storeText()
    {
        $text = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published,
        ];
        // $str = serialize($text);
        file_put_contents("{$this->slug}", serialize($text));
        echo "Пост сохранен \n";
    }

    function editText(string $title, string $text)
    {
        if ($title !== '' && $text !== '') {

            $this->title = $title;
            $this->text = $text;
            echo "Запись успешно изменена \n";
        } else {
            echo "Заголовок или текст не должны быть пустыми \n";
        }
    }
}


$telegraph1 = new TelegraphText('Petr', 'test2.txt');
$telegraph2 = new FileStorage;

$telegraph1->title = 'Privet3';
$telegraph1->text = 'Hi, World3';

// $telegraph2->create($telegraph1);

$telegraph2->list();
print_r($telegraph2->listObjects);