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
    public function create(TelegraphText $object)
    {
        $str = $object->slug;
        $slug1 = substr($str, 0, strlen($str) - 4);
        $i = 1;

        $slug = $slug1 . '_' . date('d.m.Y H') . "_{$i}.txt";
        while (file_exists($slug)) {
            $i++;
            $slug = $slug1 . '_' . date('d.m.Y H') . "_{$i}.txt";

        }

        $testText1 = serialize($object);
        file_put_contents("{$slug}", "{$testText1}");
        $this->slug = $slug;
        return $this->slug;
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



$telegraph2->create($telegraph1);