<?php

enum BookCopyStatus : string{
    case AVAILABLE = 'available';
    case LOANED = 'loaned';
    case RESERVED = 'reserved';
    case DAMAGED = 'damaged';
    case LOST = 'lost';
}

final class BookCopy{
    private int $id, $edition_id;
    private string $asset_code;
    private BookCopyStatus $status;

    private function __construct(
        int $edition_id, string $asset_code, string $status, int $id = 0) {
        $this->id = $id;
        $this->edition_id = $edition_id;
        $this->asset_code = $asset_code;
        $this->status = BookCopyStatus::from($status);
    }

    public function toArray(){
        return (array) $this;
    }
    
    public static function fromArray(array $data){
        return new BookCopy(
            $data['edition_id'],
            $data['asset_code'],
            $data['status'],
            $data['id']
        );
    }

    public function get_id(): int {return $this->id;}
    public function get_edition_id(): int {return $this->edition_id;}
    public function get_asset_code(): string {return $this->asset_code;}
    public function get_status(): string {return $this->status->value;}
    
    public function set_edition_id(int $edition_id){$this->edition_id = $edition_id;}
    public function set_asset_code(string $code){$this->asset_code = $code;}
    public function set_status(string $status){$this -> status = BookCopyStatus::from($status);}
}
?>