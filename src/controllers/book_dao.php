<?php

require_once (__DIR__ . '/../managers/dao_mng.php');
require_once (__DIR__ . '/../models/books/writer.php');
require_once (__DIR__ . '/../models/books/authorship.php');
require_once (__DIR__ . '/../models/books/book_copy.php');
require_once (__DIR__ . '/../models/books/collection.php');
require_once (__DIR__ . '/../models/books/edition.php');
require_once (__DIR__ . '/../models/books/opus.php');
require_once (__DIR__ . '/../models/books/publisher.php');


final class BookDAO{
    // Registration:
    public static function register_opus(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $o = Opus::fromArray($data, false);
        return $db_man -> insert_record_in(DB::OPUS_TABLE, $o -> toArray());   
    }

    public static function register_writer(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $w = Writer::fromArray($data, false);
        return $db_man -> insert_record_in(DB::WRITER_TABLE, $w -> toArray());   
    }

    public static function register_authorship(array $data, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $a = Authorship::fromArray($data);
        return $db_man -> insert_record_in(DB::AUTHORSHIP_TABLE, $a -> toArray());   
    }

    public static function register_publisher(array $data, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $p = Publisher::fromArray($data);
        return $db_man -> insert_record_in(DB::PUBLISHER_TABLE, $p -> toArray());   
    }

    public static function register_collection(array $data, int $user_id){ 
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $c = Collection::fromArray($data);
        return $db_man -> insert_record_in(DB::COLLECTION_TABLE, $c -> toArray());   
    }

    

// registerEdition
// registerCopy

    // Reading
    // Searching:
    // ID-fetchers:
    public static function fetch_opus_by_id(int $id): ?Opus { //OK
        $db_man = new DAOManager();
        $opus_array = $db_man -> fetch_record_by_id_from(DB::OPUS_TABLE, $id);
        if (empty($opus_array)) return null;
        return Opus::fromArray($opus_array, true); // Factory used truly for fetching
    }

    public static function fetch_all_opuses() { //OK
        $db_man = new DAOManager();
        $opus_instances = array();
        $fetched_opuses = $db_man -> fetch_all_records_from(DB::OPUS_TABLE);
        foreach($fetched_opuses as $o) $opus_instances[] = Opus::fromArray($o, true);
        return $opus_instances;
    }

    public static function fetch_all_writers() { //OK
        $db_man = new DAOManager();
        $writer_instances = array();
        $fetched_writers = $db_man -> fetch_all_records_from(DB::WRITER_TABLE);
        foreach($fetched_writers as $w) $writer_instances[] = Writer::fromArray($w, true);
        return $writer_instances;
    }

    public static function fetch_all_publishers() { // OK
        $db_man = new DAOManager();
        $fetched_pubs = $db_man -> fetch_all_records_from(DB::PUBLISHER_TABLE);
        foreach($fetched_pubs as $w) $pubs_instances[] = Publisher::fromArray($w, true);
        return $pubs_instances;
    }

    public static function fetch_all_collections() { 
        $db_man = new DAOManager();
        $fetched_collections = $db_man -> fetch_all_records_from(DB::COLLECTION_TABLE);
        foreach($fetched_collections as $c) $colls_instances[] = Publisher::fromArray($c, true);
        return $colls_instances;
    }

    

    /**
     * Fetching joining to opus data its respective writers data through authorship table.
     */
    public static function fetch_opus_with_writers(int $opus_id): ?array { // OK
        $db_man = new DAOManager();
        $fetched_opus = self::fetch_opus_by_id($opus_id);
        if(empty($fetched_opus)) return null;
        $where_condition = [['field' => DB::OPUS_TABLE.".id", 'operator' => '=']];
        //e1 = opuses, e2 = writers
        return $db_man -> fetch_elements_relation_joint(
            ['opuses_id' => $opus_id], DB::OPUS_TABLE, DB::WRITER_TABLE, DB::AUTHORSHIP_TABLE,
            DB::OPUS_FIELDS, ['name', 'birth_year'], ['e1_id' => 'opus_id', 'e2_id' => 'writer_id'],
            DB::OPUS_TABLE.".id", // Grouping
            $where_condition, [], 'AND', 'AND',  // Where logic
            DB::OPUS_TABLE.".title"); // Ordering
    }
}



// editOpus
// editEdition
// editCopy
// deleteEdition
// deleteCopy
// setCopyStatus
// fetchOpusByID
// fetchOpusByTitle
// fetchOpusByAuthor
// fetchOpusByAuthorAndTitle
// fetchCopyByID
// fetchCopyByAssetNumber
// fetchAllOpusCopies
// fetchCopyByPublisher
// fetchCopyByCollection
// fetchCopyByCoverColor

?>