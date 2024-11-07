<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['data'] ?? '';

    if (empty($text)) {
        echo "<p style='color: red; font-style: italic;'>Нет текста для анализа</p>";
    } else {
        echo "<p style='color: blue; font-style: italic;'>Исходный текст: <span style='color: green; font-weight: bold;'>" . htmlspecialchars($text) . "</span></p>";

        //кол-во символов учитывая многобайтовые символы
        $char_count = preg_match_all('/./us', $text, $matches);

        //счетчики
        $letter_count = 0;
        $uppercase_count = 0;
        $lowercase_count = 0;
        $punctuation_count = 0;
        $digit_count = 0;
        $word_count = 0;

        //массивы для частот символов и слов
        $char_frequency = [];
        $word_frequency = [];
        $words = preg_split('/\s+/u', trim($text)); //разделить текст на слова, игнорируя пробелы, табуляции или переходы на новую строку

        foreach ($words as $word) {
            if (!empty($word)) {
                $word_count++;//увеличить счетчик для слов
                $word_frequency[$word] = isset($word_frequency[$word]) ? $word_frequency[$word] + 1 : 1; //счетчик частоты для текущего слова

                //разделить текущее слово на отдельные символы
                $characters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY); //на каждый символ, -1 - нет ограничений на колво частей и пустые строки не будут включены
                foreach ($characters as $char) { //перебор всех символов в слове
                    // Подсчет букв (рус и англ)
                    if (preg_match('/\p{L}/u', $char)) { //является ли символ буквой (поиск всех буквенных символов)
                        $letter_count++; //счетчик просто букв
                        if (preg_match('/\p{Lu}/u', $char)) { //если буква заглавная
                            $uppercase_count++;
                        } else {
                            $lowercase_count++; //строчная
                        }
                    } elseif (ctype_digit($char)) { //если символ это цифра 
                        $digit_count++;
                    } elseif (preg_match('/[.,;:!?\'"]/u', $char)) { //если символ знак препинания
                        $punctuation_count++;
                    }

                    //счетчик вхождений текущего символа в массиве частот
                    $char_frequency[$char] = isset($char_frequency[$char]) ? $char_frequency[$char] + 1 : 1;
                }
            }
        }

        echo "<table border='1' cellpadding='5'>
                <tr><th>Параметр</th><th>Значение</th></tr>
                <tr><td>Количество символов</td><td>$char_count</td></tr>
                <tr><td>Количество букв</td><td>$letter_count</td></tr>
                <tr><td>Количество заглавных букв</td><td>$uppercase_count</td></tr>
                <tr><td>Количество строчных букв</td><td>$lowercase_count</td></tr>
                <tr><td>Количество знаков препинания</td><td>$punctuation_count</td></tr>
                <tr><td>Количество цифр</td><td>$digit_count</td></tr>
                <tr><td>Количество слов</td><td>$word_count</td></tr>
              </table>";

        // Таблица частоты каждого символа
        echo "<p>Количество вхождений каждого символа:</p><table border='1' cellpadding='5'>";
        foreach ($char_frequency as $char => $count) {
            echo "<tr><td>" . htmlspecialchars($char) . "</td><td>$count</td></tr>";
        }
        echo "</table>";

        // Таблица частоты каждого слова
        echo "<p>Список слов и их количество:</p><table border='1' cellpadding='5'>";
        ksort($word_frequency); //сортировка слов по алфавиту
        foreach ($word_frequency as $word => $count) {
            echo "<tr><td>" . htmlspecialchars($word) . "</td><td>$count</td></tr>";
        }
        echo "</table>";
    }
}
?>

<a href="index.html" class="analysis-button">Другой анализ</a>

<style>
    .analysis-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        color: white;
        background-color: #4CAF50;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .analysis-button:hover {
        background-color: #45a049;
        transform: scale(1.05);
    }

    .analysis-button:active {
        background-color: #3e8e41;
    }   
</style>