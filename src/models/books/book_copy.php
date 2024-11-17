<?php

enum BookCopyStatus : string{
    case AVAILABLE = 'available';
    case LOANED = 'loaned';
    case RESERVED = 'reserved';
    case DAMAGED = 'damaged';
    case LOST = 'lost';
}

final class BookCopy{
    private ?int $id;
    private int $edition_id;
    private string $asset_code;
    private BookCopyStatus $status;

    private function __construct(
        string $asset_code, ?int $edition_id = null, ?string $status = null, ?int $id = null) {
        $this->id = $id;
        $this->edition_id = $edition_id;
        $this->asset_code = $asset_code;
        if(!is_null($status)) $this->status = BookCopyStatus::from($status);
    }

    public function toArray(){
        return [
            'id' => $this->id ?? null,
            'edition_id' => $this->edition_id,
            'asset_code' => $this->asset_code,
            'status' => $this->status->value
        ];
    }
    
    public static function fromArray(array $data, bool $for_insertion = false){
        $book = new BookCopy(
            $data['asset_code'],
            $data['edition_id']
        );
        if(!$for_insertion) {
            $book -> set_status($data['status']);
            $book -> id = $data['id'] ?? null;
        }
        else {
            $book -> set_status('available');
            $book -> id = $data['id'] ?? null;
        }
        return $book;
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