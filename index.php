<?php

// Класс, реализующий парсинг фида
class FeedParser {
    private $feed; // переменная для хранения фида

    // метод для загрузки фида из файла
    public function loadFromFile($file) {
        $this->feed = simplexml_load_file($file);
    }

    // метод для загрузки фида из URL
    public function loadFromUrl($url) {
        $this->feed = simplexml_load_file($url);
    }

    // метод для парсинга фида
    public function parseFeed() {
        // получение списка элементов <flowers> и их свойств
        $flowers = $this->feed->flowers;
        foreach ($flowers as $flower) {
            $title = (string)$flower->title;
            $description = (string)$flower->description;
            $species = (string)$flower->species;
            $genus = (string)$flower->genus;
            $photos = array();
            foreach ($flower as $key => $value) {
                if (strpos($key, 'photo') === 0) {
                    $photos[] = (string)$value;
                }
            }
            // сохранение данных в выбранное хранилище
            Storage::saveData($title, $description, $species, $genus, $photos);
        }
    }
}

// Абстрактный класс для хранения данных
abstract class Storage {
    // метод для сохранения данных
    public static function saveData($title, $description, $species, $genus, $photos) {
        // реализация метода зависит от конкретной реализации класса хранилища
    }
}

// Класс для сохранения данных в файл
class FileStorage extends Storage {
    public static function saveData($title, $description, $species, $genus, $photos) {
        // сохранение данных в файл
        $data = array(
            'title' => $title,
            'description' => $description,
            'species' => $species,
            'genus' => $genus,
            'photos' => $photos
        );
        file_put_contents('data.json', json_encode($data));
    }
}

// Класс для сохранения данных в базу данных
class DatabaseStorage extends Storage {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function save($data) {
        // Реализация сохранения данных в базу данных
    }
}
