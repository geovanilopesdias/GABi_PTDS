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

    public static function register_collection(array $data, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $c = Collection::fromArray($data);
        return $db_man -> insert_record_in(DB::COLLECTION_TABLE, $c -> toArray());   
    }

    public static function register_edition(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $e = Edition::fromArray($data, false);
        return $db_man -> insert_record_in(DB::EDITION_TABLE, $e -> toArray());   
    }

    public static function register_book_copy(array $data, int $user_id){
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $c = BookCopy::fromArray($data, true); // Truly used for insertion
        return $db_man -> insert_record_in(DB::BOOK_COPY_TABLE, $c -> toArray());
    }
    

    // ----- READING
    // ID-fetchers:
    public static function fetch_opus_by_id(int $id): ?Opus { //OK
        $db_man = new DAOManager();
        $opus_array = $db_man -> fetch_record_by_id_from(DB::OPUS_TABLE, $id);
        if (empty($opus_array)) return null;
        return Opus::fromArray($opus_array, true); // Factory used truly for fetching
    }

    public static function fetch_edition_by_id(int $id): ?Edition { //OK
        $db_man = new DAOManager();
        $edition_array = $db_man -> fetch_record_by_id_from(DB::EDITION_TABLE, $id);
        if (empty($edition_array)) return null;
        return Edition::fromArray($edition_array, true); // Factory used truly for fetching
    }

    // All fetchers:
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

    public static function fetch_all_collections() {  // OK
        $db_man = new DAOManager();
        $fetched_collections = $db_man -> fetch_all_records_from(DB::COLLECTION_TABLE);
        foreach($fetched_collections as $c) $colls_instances[] = Collection::fromArray($c, true);
        return $colls_instances;
    }

    public static function fetch_all_editions() {  // OK
        $db_man = new DAOManager();
        $fetched_editions = $db_man -> fetch_all_records_from(DB::EDITION_TABLE);
        foreach($fetched_editions as $e) $eds_instances[] = Edition::fromArray($e, true);
        return $eds_instances;
    }

    public static function fetch_whole_bookshelf() { //OK
        $db_man = new DAOManager();
        $fetched_copies = $db_man -> fetch_all_records_from(DB::BOOK_COPY_TABLE);
        foreach($fetched_copies as $c) $copies_instances[] = BookCopy::fromArray($c, false);
        return $copies_instances;
    }
    
    // Join-fetchers
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

    
    public static function fetch_edition_with_opus_writer_data(int $edition_id): ?array { //OK
        $db_man = new DAOManager();
        
        // Fetch the edition details first
        $fetched_opus = self::fetch_edition_by_id($edition_id);
        if (empty($fetched_opus)) return null;
    
        // Prepare the fields for the query
        $edition_fields = ['id', 'volume', 'edition_number', 'publishing_year', 'pages', 'cover_colors', 'translators'];
        $opus_fields = ['title', 'original_year', 'alternative_url', 'ddc', 'cutter_sanborn'];
        
        // Build the DQL query string
        $dql = "SELECT ".
            DB::EDITION_TABLE . "." . implode(", ".DB::EDITION_TABLE.".",$edition_fields) . ", ".
            DB::OPUS_TABLE . "." . implode(", ".DB::OPUS_TABLE.".",$opus_fields) . ", ".
            DB::PUBLISHER_TABLE . ".name AS publisher,".
            " json_agg(json_build_object('name', ".DB::WRITER_TABLE.".name, 'birth_year', ".DB::WRITER_TABLE.".birth_year)) AS ".DB::WRITER_TABLE.
            " FROM ". DB::AUTHORSHIP_TABLE.
            " JOIN ". DB::OPUS_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::WRITER_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".writer_id = ".DB::WRITER_TABLE.".id".
            " JOIN ". DB::EDITION_TABLE. " ON ".DB::EDITION_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::PUBLISHER_TABLE. " ON ".DB::PUBLISHER_TABLE.".id = ".DB::EDITION_TABLE.".publisher_id".
            " WHERE ".DB::EDITION_TABLE.".id = :edition_id".
            " GROUP BY ".DB::EDITION_TABLE . ".id, ".DB::OPUS_TABLE.".id, ".DB::PUBLISHER_TABLE . ".name".
            " ORDER BY ".DB::OPUS_TABLE.".title";
        
        // Prepare search parameter with the edition ID
        $search = ['edition_id' => $edition_id];
    
        // Execute the query
        $results = $db_man->fetch_complex_join_dql($dql, $search);
        
        // Decode the 'writers' field from JSON to an array
        foreach ($results as &$row) {
            if (isset($row['writers'])) {
                $row['writers'] = json_decode($row['writers'], true); // Decode writers field into an array
            }
        }
    
        return $results;
    }

    // ----- UPDATING
    // Searching:
    public static function edit_opus(int $id_to_update, array $data, int $user_id): bool {
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_opus_data = self::fetch_opus_by_id($id_to_update) -> toArray();
        if (empty($current_opus_data)) return false;
        
        // Edit current opus data in order to scan them through checkers into model setters:
        foreach($current_opus_data as $field => &$v)
            if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
        
        try{
            $opus = Opus::fromArray($current_opus_data, false);
            $data['id'] = $opus -> get_id();
        }
        catch(Exception $e) {echo ("Opus setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(DB::OPUS_TABLE, $data);
    }

    public static function edit_edition(int $id_to_update, array $data, int $user_id): bool {
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_edition_data = self::fetch_edition_by_id($id_to_update) -> toArray();
        if (empty($current_edition_data)) return false;
        
        // Edit current edition data in order to scan them through checkers into model setters:
        foreach($current_edition_data as $field => &$v)
            if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
        
        try{
            $edition = Edition::fromArray($current_edition_data, false);
            $data['id'] = $edition -> get_id();
        }
        catch(Exception $e) {echo ("Edition setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(DB::EDITION_TABLE, $data);
    }
    // editOpus
    // editEdition
    // editCopy

}




// deleteEdition
// deleteCopy
// setBookCopyStatusAs

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