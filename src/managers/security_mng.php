<?php
require_once (__DIR__ . '/../models/people/reader.php');
require_once (__DIR__ . '/../controllers/book_dao.php');

final class SecurityManager{
    
    /**
     * By now, is what it is; I'll change Reader set_passphrase to comport
     * password_hash after studying about peppering passwords.
     * https://www.php.net/manual/en/function.password-hash.php
    */
    public static function check_password(?Reader $user, $post_passphrase): bool{
        $post_passphrase_sha256 = hash('sha256', $post_passphrase);
        return $user -> get_passphrase() === $post_passphrase_sha256;
    }

    public static function toDateTimeOrNull($value): ?DateTime {
        if (is_string($value)) return new DateTime($value, new DateTimeZone('America/Sao_Paulo'));
        if ($value instanceof DateTime) return $value;
        return null;
    }

    public static function generate_login(string $name, string $phone): string {
        $name_words = explode(' ', $name);
        return $name_words[0] . $name_words[count($name_words)-1] . preg_replace('/\D/', '', $phone);
    }

    public static function generate_provisory_passphrase(){
        $nouns = ['carro', 'forno', 'prédio', 'trinco', 'computador', 'lápis', 'livro'];
        $adjective = ['grande', 'pequeno', 'torto', 'feio', 'ruim', 'bonito', 'novo'];
        $colors = ['azul', 'vermelho', 'preto', 'branco', 'verde', 'dourado', 'prateado'];
        $word_list = [$nouns, $colors, $adjective];
        for ($i = 0; $i < 3; $i++) {
            $words[] = $word_list[$i][array_rand($word_list[$i])];
        }
        
        return implode('-', $words) . rand(10, 99);
    }

    public static function is_login_valid($login_to_test): bool{
        return strlen($login_to_test) >= 8;
    }

    public static function is_passphrase_valid($passphrase_to_test): bool{
        return strlen($passphrase_to_test) >= 8;
    }

    public static function is_name_valid($nameToTest): bool{
        return preg_match("/^[A-zÀ-ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-zÀ-ÿ][A-zÀ-ÿ']+$/", $nameToTest);
    }

    public static function is_phone_valid($phoneToTest): bool{
        return preg_match("/^[1-9]{2}9[0-9]{8}$/", $phoneToTest);
    }

    public static function is_url_valid($url_to_test): bool{
        if (empty($url_to_test)) return true;
        return filter_var($url_to_test, FILTER_VALIDATE_URL);
    }

    public static function is_ddc_valid(string $ddcToTest): bool{
        return preg_match('/^\d{1,3}(\.\d+)?$/', $ddcToTest);
    }

    public static function is_isbn_valid(string $isbn_to_test): bool{
        // ISBN need to be 13 characters long and purely numeric:
        if (strlen($isbn_to_test) != 13) 
            throw new UnexpectedValueException("ISBN has more (or less) than 13 digits.");
        if (!ctype_digit($isbn_to_test))
            throw new UnexpectedValueException("ISBN has non-numeric digits.");
        $digits = str_split($isbn_to_test);
        $sum = 0;
        // Iterate over the ISBN, but the last digit:
        for ($d = 0; $d < strlen($isbn_to_test)-1; $d++) {
            if ($d == 0 or $d % 2 == 0) $sum += $digits[$d];
            else $sum += $digits[$d] * 3;
        };
        $remainder = $sum % 10;
        
        // If the (sum%10) = 0, last digit should be 0; (10-remainder) otherwise:
        if ($isbn_to_test[strlen($isbn_to_test)-1] == 0 and $remainder == 0) return true;
        if ($isbn_to_test[strlen($isbn_to_test)-1] == 10 - $remainder) return true;
        else return false;
    }

    public static function is_isbn_in_database(string $isbn): bool{
        $edition = BookDAO::fetch_edition_by_isbn($isbn);
        return !empty($edition);
    }
}

?>