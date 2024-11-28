<?php

require_once (__DIR__ . '/../managers/dao_mng.php');
require_once (__DIR__ . '/book_dao.php');
require_once (__DIR__ . '/../models/interactions/loan.php');
require_once (__DIR__ . '/../models/books/book_copy.php');
require_once (__DIR__ . '/../models/people/reader.php');

final class LoanDAO{
    const DATE_FIELDS = ['opening_date', 'loan_date', 'closing_date', 'return_date'];

    private static  ?DateTime $now = null;

    public static function get_now(): DateTime {
        if (self::$now === null) 
            self::$now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        return self::$now;
    }

    // ----- Registration:
    public static function register_loan(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_loan($user_id)) return false;
        $data['opener_id'] = $user_id;
        $data['opening_date'] = self::get_now();
        $loan = Loan::fromArray($data, false); // Factory calling isn't for fetching
        $dml = $db_man -> insert_record_in(DB::LOAN_TABLE, $loan -> toArray());
        if ($dml) {
            BookDAO::setBookCopyStatusAs('loaned', $data['book_copy_id'], $user_id);
            return $dml;
        }
        else return false;
    }

    // ----- Updating:
    public static function close_loan(int $id, int $user_id, ?DateTime $return_date = null){
        $db_man = new DAOManager();
        if (!$db_man -> can_user_loan($user_id)) return false;
        $loan = self::fetch_loan_by_id($id, $user_id);
        if (is_null($loan)) return false;
        $data['id'] = $id;
        $data['closer_id'] = $user_id;
        $data['closing_date'] = self::get_now();
        $data['return_date'] = $return_date ?? self::get_now();
        $dml = $db_man -> update_entity_in(DB::LOAN_TABLE, $data);
        if ($dml) {
            BookDAO::setBookCopyStatusAs('available', $loan -> get_book_copy_id(), $user_id);
            return $dml;
        }
        else return false;
    }

    // ----- Reading
    // Basic fetchers:
    public static function fetch_loan_by_id(int $id): ?Loan { //OK
        $db_man = new DAOManager();
        $loan_array = $db_man -> fetch_record_by_id_from(DB::LOAN_TABLE, $id);
        // Only loaners and the respectively reader should be able to search for the loan:
        if (empty($loan_array)) return null;
                
        foreach(self::DATE_FIELDS as $f)
            $loan_array[$f] = isset($loan_array[$f]) ? new DateTime($loan_array[$f], new DateTimeZone('America/Sao_Paulo')) : null;

        return Loan::fromArray($loan_array, true); // Factory used truly for fetching
    }

    public static function fetch_all_loans(int $user_id){
        $db_man = new DAOManager();
        if (!$db_man -> can_user_loan($user_id)) return false;

        $fetched_loans = $db_man -> fetch_all_records_from(DB::LOAN_TABLE);
        $loan_instances = array();
        foreach($fetched_loans as $loan){
            foreach(self::DATE_FIELDS as $f)
                $loan[$f] = isset($loan[$f]) ? new DateTime($loan[$f], new DateTimeZone('America/Sao_Paulo')) : null;
            $loan_instances[] = Loan::fromArray($loan, true); // Factory calling TRULY for fetching
        }
        return $loan_instances;
    }

    public static function fetch_loan_by(string $field, string $value, bool $open_only): ?array { 
        if ($field !== 'name' and $field !== 'asset_code')
            throw new InvalidArgumentException('Search only by reader\'s name or copy\'s asset code.');
        
        $db_man = new DAOManager();
        $search = ($field === 'name') ? [$field => "%$value%"] : [$field => $value];
        $where_field = ($field === 'name') ? 'r.name ILIKE' : 'b.asset_code =';
        $dql = "
            SELECT ".implode(',', DB::LOAN_FIELDS).", 
                r.name, r.role, o.title 
            FROM ". DB::LOAN_TABLE ." l 
            JOIN ". DB::READER_TABLE ." r ON r.id = l.loaner_id
            JOIN ". DB::BOOK_COPY_TABLE ." b ON b.id = l.book_copy_id
            JOIN ". DB::EDITION_TABLE ." e ON e.id = b.edition_id 
            JOIN ". DB::OPUS_TABLE ." o ON o.id = e.opus_id
            WHERE $where_field :$field 
        ";
        $dql .= ($open_only) ? " AND l.return_date is null" : "";
        return $db_man -> fetch_flex_dql($dql, $search);
        
    }

    public static function fetch_open_loans_by_loaner_id(int $loaner_id): ?array { 
        $db_man = new DAOManager();
        $search = ['loaner_id' => $loaner_id];
        $dql = "
            SELECT l.id AS id, b.asset_code AS \"patr.\", o.title AS título, 
                l.loan_date AS retirada
            FROM ". DB::LOAN_TABLE ." l 
            JOIN ". DB::READER_TABLE ." r ON r.id = l.loaner_id
            JOIN ". DB::BOOK_COPY_TABLE ." b ON b.id = l.book_copy_id
            JOIN ". DB::EDITION_TABLE ." e ON e.id = b.edition_id 
            JOIN ". DB::OPUS_TABLE ." o ON o.id = e.opus_id
            WHERE r.id = :loaner_id AND l.return_date is null";
        return $db_man -> fetch_flex_dql($dql, $search);
        
    }

    public static function fetch_loan_with_reader_and_opus_data_by_loan_id(int $loan_id): ?array { 
        $db_man = new DAOManager();
        $search = ['loan_id' => $loan_id];
        $dql = "
            SELECT l.".implode(', l.', DB::LOAN_FIELDS)." , 
                r.name, b.asset_code, o.title 
            FROM ". DB::LOAN_TABLE ." l 
            JOIN ". DB::READER_TABLE ." r ON r.id = l.loaner_id
            JOIN ". DB::BOOK_COPY_TABLE ." b ON b.id = l.book_copy_id
            JOIN ". DB::EDITION_TABLE ." e ON e.id = b.edition_id 
            JOIN ". DB::OPUS_TABLE ." o ON o.id = e.opus_id
            WHERE l.id = :loan_id";
        return $db_man -> fetch_flex_dql($dql, $search, true);
    }

}
?>