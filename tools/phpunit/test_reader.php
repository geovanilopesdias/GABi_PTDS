<?php
declare(strict_types=1);
require '/home/geovani/vendor/autoload.php';
use PHPUnit\Framework\TestCase;

final class ReaderTest extends TestCase {
    public function testStudent(): void {
        $student = Reader::Student('Maria da Silva', 'maria_da_silva', '51992380715');
        $this -> assertSame(false, $student->get_canLoan());
        $this -> assertSame(false, $student->get_canRegister());
        $this -> assertSame('student', $student->get_readerType());
    }
}

?>