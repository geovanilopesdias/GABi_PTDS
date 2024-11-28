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
        return $db_man -> insert_record_in(DB::AUTHORSHIP_TABLE, $a -> toArray(), false);   
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

    public static function register_edition(array $data, int $user_id){ // OK
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

    public static function fetch_bookcopy_by_id(int $id): ?BookCopy { // OK
        $db_man = new DAOManager();
        $edition_array = $db_man -> fetch_record_by_id_from(DB::BOOK_COPY_TABLE, $id);
        if (empty($edition_array)) return null;
        return BookCopy::fromArray($edition_array, true); // Factory used truly for fetching
    }

    public static function fetch_writer_by_id(int $id): ?Writer { // OK
        $db_man = new DAOManager();
        $writer_array = $db_man -> fetch_record_by_id_from(DB::WRITER_TABLE, $id);
        if (empty($writer_array)) return null;
        return Writer::fromArray($writer_array, true); // Factory used truly for fetching
    }

    public static function fetch_collection_by_id(int $id): ?Collection { // OK
        $db_man = new DAOManager();
        $collection_array = $db_man -> fetch_record_by_id_from(DB::COLLECTION_TABLE, $id);
        if (empty($collection_array)) return null;
        return Collection::fromArray($collection_array, true); // Factory used truly for fetching
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
        $fetched_writers = $db_man -> fetch_all_records_from(DB::WRITER_TABLE, 'name');
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

    public static function fetch_authorships_from_opus(int $opus_id) { // OK
        $db_man = new DAOManager();
        $fetched_authorships = $db_man -> 
            fetch_records_from(
                ['opus_id' => $opus_id],
                DB::AUTHORSHIP_TABLE, DB::AUTHORSHIP_FIELDS,
                [['field' => 'opus_id']]
            );
        $authorship_instances = array();
        foreach($fetched_authorships as $a) $authorship_instances[] = Authorship::fromArray($a);
        return $authorship_instances;
    }
    
    // Join-fetchers
    /**
     * Fetching joining to opus data its respective writers data through authorship table.
     * No authors will result in an empty return.
     */
    public static function fetch_opus_with_writers(int $opus_id): ?array { // OK
        $db_man = new DAOManager();
        $fetched_opus = self::fetch_opus_by_id($opus_id);
        if(empty($fetched_opus)) return null;
        $where_condition = [['field' => DB::OPUS_TABLE.".id", 'operator' => '=']];
        //e1 = opuses, e2 = writers
        return $db_man -> fetch_elements_relation_joint(
            ['opuses_id' => $opus_id], DB::OPUS_TABLE, DB::OPUS_TABLE, DB::WRITER_TABLE, DB::AUTHORSHIP_TABLE,
            DB::OPUS_FIELDS, ['name', 'birth_year'], ['e1_id' => 'opus_id', 'e2_id' => 'writer_id'],
            DB::OPUS_TABLE.".id", // Grouping
            $where_condition, [], 'AND', 'AND',  // Where logic
            DB::OPUS_TABLE.".title"); // Ordering
    }

    
    public static function fetch_edition_with_opus_writer_data(int $edition_id): ?array { //OK
        $db_man = new DAOManager();
        
        $fetched_opus = self::fetch_edition_by_id($edition_id);
        if (empty($fetched_opus)) return null;
    
        $edition_fields = ['id', 'volume', 'edition_number', 'publishing_year', 'pages', 'cover_colors', 'translators'];
        $opus_fields = ['title', 'original_year', 'alternative_url', 'ddc', 'cutter_sanborn'];
        
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
        
        $search = ['edition_id' => $edition_id];
        $results = $db_man->fetch_flex_dql($dql, $search);
        foreach ($results as &$row) {
            if (isset($row['writers'])) {
                $row['writers'] = json_decode($row['writers'], true); // Decode writers field into an array
            }
        }
        return $results;
    }

    public static function fetch_bookcopy_essentially_by(string $field, mixed $value): ?array {
        if($field === 'asset_code')
            throw new InvalidArgumentException('Use method BookDAO::fetch_bookcopy_holistically_by_asset_code() instead.');

        if (!in_array($field, ['title', 'author', 'collection', 'cover_colors'], true))
            throw new InvalidArgumentException("It isn't possible to search by $field");

        $db_man = new DAOManager();
        $field_search = match ($field) {
            'title' => 'o.title', 'writer' => 'w.name',
            'collection' => 'c.name', 'cover_colors' => 'e.cover_colors'
        };

        $search = [$field => "%$value%"];
        $dql = "SELECT 
            b.id AS id, b.asset_code AS \"patr.\",
            o.title AS título, json_agg(json_build_object('name', w.name, 'birth_year', w.birth_year)) AS autores, 
            o.cutter_sanborn AS cutter, b.status AS situação,
            o.alternative_url AS weblink 
            FROM ". DB::AUTHORSHIP_TABLE. " a 
            JOIN ".DB::OPUS_TABLE. " o ON a.opus_id = o.id 
            JOIN ". DB::WRITER_TABLE. " w ON a.writer_id = w.id 
            JOIN ". DB::EDITION_TABLE. " e ON e.opus_id = o.id 
            JOIN ". DB::COLLECTION_TABLE. " c ON c.id = e.collection_id 
            JOIN ". DB::BOOK_COPY_TABLE. " b ON b.edition_id = e.id 
            WHERE $field_search ILIKE :$field 
            GROUP BY b.id, b.asset_code, b.status, e.cover_colors, 
                o.title, o.alternative_url, 
                o.cutter_sanborn
            ORDER BY o.title";
        return $db_man->fetch_flex_dql($dql, $search);
    }

    public static function fetch_bookcopy_holistically_by(string $field, mixed $value): ?array {
        if($field === 'asset_code')
            throw new InvalidArgumentException('Use method BookDAO::fetch_bookcopy_holistically_by_asset_code() instead.');

        if (!in_array($field, ['title', 'writer', 'publisher', 'collection', 'cover_colors'], true))
            throw new InvalidArgumentException("It isn't possible to search by $field");

        $db_man = new DAOManager();
        $field_search = match ($field) {
            'title' => 'o.title', 'writer' => 'w.name',
            'publisher' => 'p.name', 'collection' => 'c.name',
            'cover_colors' => 'e.cover_colors'
        };

        $search = [$field => "%$value%"];
        $book_copy_fields = ['b.id AS id', 'b.asset_code AS patrimônio', 'b.status AS situação'];
        $edition_fields = ['e.volume', 'e.edition_number AS edição', 'e.publishing_year AS ano', 'e.pages AS páginas', 'e.cover_colors AS capa', 'e.translators AS tradutores'];
        $opus_fields = ['o.title AS título', 'o.original_year AS origem', 'o.alternative_url AS link', 'o.ddc', 'o.cutter_sanborn AS cutter'];
        $dql = "SELECT ".
            implode(", ", $opus_fields) . ", ".    
            implode(", ", $edition_fields) . ", ".
            implode(", ", $book_copy_fields) . ", ".
            " p.name AS editora,".
            " json_agg(json_build_object('name', w.name, 'birth_year', w.birth_year)) AS autores ".
            " FROM ". DB::AUTHORSHIP_TABLE. " a JOIN ".
            DB::OPUS_TABLE. " o ON a.opus_id = o.id".
            " JOIN ". DB::WRITER_TABLE. " w ON a.writer_id = w.id".
            " JOIN ". DB::EDITION_TABLE. " e ON e.opus_id = o.id".
            " JOIN ". DB::BOOK_COPY_TABLE. " b ON b.edition_id = e.id".
            " JOIN ". DB::PUBLISHER_TABLE. " p ON p.id = e.publisher_id".
            " WHERE $field_search ILIKE :$field 
            GROUP BY b.id, b.asset_code, b.status, e.volume, e.edition_number,
                e.publishing_year, e.pages, e.cover_colors, e.translators,
                o.title, o.original_year, o.alternative_url, o.ddc,
                o.cutter_sanborn, p.name 
            ORDER BY o.title";
        return $db_man->fetch_flex_dql($dql, $search);
    }

    public static function fetch_bookcopy_holistically_by_asset_code(string $bookcopy_asset_code): ?array {
        $db_man = new DAOManager();
        
        // Fetch the edition details first
        $fetched_opus = self::fetch_bookcopy_by_asset_code($bookcopy_asset_code, false);
        if (empty($fetched_opus)) return null;
    
        // Prepare the fields for the query
        $book_copy_fields = ['id', 'asset_code', 'status'];
        $edition_fields = ['volume', 'edition_number', 'publishing_year', 'pages', 'cover_colors', 'translators'];
        $opus_fields = ['title', 'original_year', 'alternative_url', 'ddc', 'cutter_sanborn'];
        
        // Build the DQL query string
        $dql = "SELECT ".
            DB::BOOK_COPY_TABLE . "." . implode(", ".DB::BOOK_COPY_TABLE.".", $book_copy_fields) . ", ".
            DB::EDITION_TABLE . "." . implode(", ".DB::EDITION_TABLE.".",$edition_fields) . ", ".
            DB::OPUS_TABLE . "." . implode(", ".DB::OPUS_TABLE.".",$opus_fields) . ", ".
            DB::PUBLISHER_TABLE . ".name AS publisher,".
            " json_agg(json_build_object('name', ".DB::WRITER_TABLE.".name, 'birth_year', ".DB::WRITER_TABLE.".birth_year)) AS ".DB::WRITER_TABLE.
            " FROM ". DB::AUTHORSHIP_TABLE.
            " JOIN ". DB::OPUS_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::WRITER_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".writer_id = ".DB::WRITER_TABLE.".id".
            " JOIN ". DB::EDITION_TABLE. " ON ".DB::EDITION_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::BOOK_COPY_TABLE. " ON ".DB::BOOK_COPY_TABLE.".edition_id = ".DB::EDITION_TABLE.".id".
            " JOIN ". DB::PUBLISHER_TABLE. " ON ".DB::PUBLISHER_TABLE.".id = ".DB::EDITION_TABLE.".publisher_id".
            " WHERE ".DB::BOOK_COPY_TABLE.".asset_code = :asset_code".
            " GROUP BY ".DB::BOOK_COPY_TABLE . ".id, ".DB::EDITION_TABLE . ".id, ".DB::OPUS_TABLE.".id, ".DB::PUBLISHER_TABLE . ".name".
            " ORDER BY ".DB::OPUS_TABLE.".title";
        
        // Prepare search parameter with the edition ID
        $search = ['asset_code' => $bookcopy_asset_code];
    
        // Execute the query
        $results = $db_man->fetch_flex_dql($dql, $search, true); // Fetch for uniqueness
        
        // Decode the 'writers' field from JSON to an array
        foreach ($results as &$row) {
            if (isset($row['writers'])) {
                $row['writers'] = json_decode($row['writers'], true); // Decode writers field into an array
            }
        }
    
        return $results;
    }

    // By-fetchers
    public static function fetch_bookcopy_by_asset_code(string $asset_code): ?BookCopy { //OK
        $db_man = new DAOManager();
        $bookcopy_array = $db_man -> fetch_records_from(
            ['asset_code' => $asset_code],
            DB::BOOK_COPY_TABLE, DB::BOOK_COPY_FIELDS,
            [['field' => 'asset_code', 'operator' => '=']], // WHERE conditions
            'AND', null, false, true // Not distinct, but unique
        );
        if (empty($bookcopy_array)) return null;
        return BookCopy::fromArray(
            $bookcopy_array, // fetch_records_from returns a 2D array
            false); // Factory NOT used for insertion
    }

    public static function fetch_opus_by_title(string $title): ?array { // OK
        $db_man = new DAOManager();
        $opus_arrays = $db_man -> fetch_records_from(
            ['title' => "%$title%"],
            DB::OPUS_TABLE, DB::OPUS_FIELDS,
            [['field' => 'title', 'operator' => 'ILIKE']] // WHERE conditions
        );
        
        if (empty($opus_arrays)) return null;
        foreach ($opus_arrays as $opus)
            $opus_instances[] = Opus::fromArray($opus, true); // Factory TRULY for fetching
        return $opus_instances;
    }
    
    public static function fetch_opus_by_author(string $author): ?array { // OK  
        $db_man = new DAOManager();
        $search = ['name' => "%$author%"];
        $where_condition = [['field' => DB::WRITER_TABLE.'.name', 'operator' => 'ILIKE']];
        $opus_instances = $db_man -> 
            fetch_elements_relation_joint(
                $search, DB::WRITER_TABLE,
                DB::OPUS_TABLE, DB::WRITER_TABLE, DB::AUTHORSHIP_TABLE,
                DB::OPUS_FIELDS, ['name', 'birth_year'],
                ['container_id' => 'opus_id', 'element_id' => 'writer_id'],
                DB::OPUS_TABLE.".id", // Grouping
                $where_condition, [], 'AND', 'AND',  // Where logic
                DB::OPUS_TABLE.".title"); // Ordering       
        return $opus_instances;
    }

    public static function fetch_all_opus_copies(int $opus_id): ?array { //OK
        $db_man = new DAOManager();
        
        $fetched_opus = self::fetch_opus_by_id($opus_id);
        if (empty($fetched_opus)) return null;
        
        $bookcopy_fields = ['asset_code', 'status'];
        $edition_fields = ['volume', 'edition_number', 'publishing_year', 'pages', 'cover_colors', 'translators'];
        $opus_fields = ['title', 'original_year', 'alternative_url', 'ddc', 'cutter_sanborn'];
        
        $dql = "SELECT ".
            DB::BOOK_COPY_TABLE . "." . implode(", ".DB::BOOK_COPY_TABLE.".",$bookcopy_fields) . ", ".    
            DB::EDITION_TABLE . "." . implode(", ".DB::EDITION_TABLE.".",$edition_fields) . ", ".
            DB::OPUS_TABLE . "." . implode(", ".DB::OPUS_TABLE.".",$opus_fields) . ", ".
            DB::PUBLISHER_TABLE . ".name AS publisher,".
            " json_agg(json_build_object('name', ".DB::WRITER_TABLE.".name, 'birth_year', ".DB::WRITER_TABLE.".birth_year)) AS ".DB::WRITER_TABLE.
            " FROM ". DB::AUTHORSHIP_TABLE.
            " JOIN ". DB::OPUS_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::WRITER_TABLE. " ON ".DB::AUTHORSHIP_TABLE.".writer_id = ".DB::WRITER_TABLE.".id".
            " JOIN ". DB::EDITION_TABLE. " ON ".DB::EDITION_TABLE.".opus_id = ".DB::OPUS_TABLE.".id".
            " JOIN ". DB::BOOK_COPY_TABLE. " ON ".DB::BOOK_COPY_TABLE.".edition_id = ".DB::EDITION_TABLE.".id".
            " JOIN ". DB::PUBLISHER_TABLE. " ON ".DB::PUBLISHER_TABLE.".id = ".DB::EDITION_TABLE.".publisher_id".
            " WHERE ".DB::OPUS_TABLE.".id = :opus_id".
            " GROUP BY ".DB::BOOK_COPY_TABLE.".id, ".DB::EDITION_TABLE . ".id, ".DB::OPUS_TABLE.".id, ".DB::PUBLISHER_TABLE . ".name".
            " ORDER BY ".DB::OPUS_TABLE.".title";
        
        $search = ['opus_id' => $opus_id];
        $results = $db_man->fetch_flex_dql($dql, $search);
        foreach ($results as &$row) {
            if (isset($row['writers'])) {
                $row['writers'] = json_decode($row['writers'], true); // Decode writers field into an array
            }
        }
        return $results;
    }

    public static function fetch_edition_by_publishers_name(string $publisher): ?array { //OK
        $db_man = new DAOManager();
        $search = ['name' => "%$publisher%"];
        $where_condition = [['field' => DB::PUBLISHER_TABLE.'.name', 'operator' => 'ILIKE']];
        
        $fetched_editions = $db_man -> 
            fetch_jointed_records_from(
                $search, DB::PUBLISHER_TABLE,
                DB::PUBLISHER_TABLE, DB::EDITION_TABLE,
                ['name AS publisher'], DB::EDITION_FIELDS,
                [['field1' => DB::PUBLISHER_TABLE.'.id', 'field2' => 'publisher_id']], 'AND',  // ON logic
                $where_condition, [], 'AND', 'AND');  //  WHERE logic
        if (empty($fetched_editions)) return null;
        foreach($fetched_editions as $e) $eds_instances[] = Edition::fromArray($e, true);
        return $eds_instances;
    }

    public static function fetch_edition_by_collection_name(string $collection): ?array { //OK
        $db_man = new DAOManager();
        $search = ['name' => "%$collection%"];
        $where_condition = [['field' => DB::COLLECTION_TABLE.'.name', 'operator' => 'ILIKE']];
        
        $fetched_editions = $db_man -> 
            fetch_jointed_records_from(
                $search, DB::COLLECTION_TABLE,
                DB::COLLECTION_TABLE, DB::EDITION_TABLE,
                ['name AS collection'], DB::EDITION_FIELDS,
                [['field1' => DB::COLLECTION_TABLE.'.id', 'field2' => 'collection_id']], 'AND',  // ON logic
                $where_condition, [], 'AND', 'AND');  //  WHERE logic
        if (empty($fetched_editions)) return null;
        foreach($fetched_editions as $e) $eds_instances[] = Edition::fromArray($e, true);
        return $eds_instances;
    }

    public static function fetch_edition_by_cover_colors(string $colors): ?array { //OK
        $db_man = new DAOManager();
        $colors_list = explode(',', $colors);
        
        // Arrays to avoid duplicates
        $eds_instances = array();
        $eds_ids = array();
        foreach($colors_list as $color){
            $search = ['cover_colors' => "%$color%"];
            $where_condition = [['field' => 'cover_colors', 'operator' => 'ILIKE']];
        
            $fetched_editions = $db_man -> 
                fetch_records_from(
                    $search, DB::EDITION_TABLE, DB::EDITION_FIELDS,
                    $where_condition, 'AND', NULL, true);  //  WHERE logic
            if (empty($fetched_editions)) return null;
            
            foreach($fetched_editions as $e){
                // Test to avoid duplicates:
                if (!in_array($e['id'], $eds_ids, true)){
                    $eds_ids[] = $e['id'];
                    $eds_instances[] = Edition::fromArray($e, true);
                }
            }    
        }
        return $eds_instances;
    }

    // ----- UPDATING
    // Searching:
    public static function edit_opus(int $id_to_update, array $data, int $user_id): bool { // OK
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

    public static function edit_edition(int $id_to_update, array $data, int $user_id): bool { // OK
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_edition_data = self::fetch_edition_by_id($id_to_update) -> toArray();
        if (empty($current_edition_data)) return false;
        
        // Edit current edition data in order to scan them through checkers into model setters:
        foreach($current_edition_data as $field => &$v)
            if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
        
        try{
            $edition = Edition::fromArray($current_edition_data, false);
            $data['id'] = $id_to_update;
        }
        catch(Exception $e) {echo ("Edition setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(DB::EDITION_TABLE, $data);
    }

    public static function edit_book_copy(int $id_to_update, array $data, int $user_id): bool { // OK
        if (isset($data['status']))
            throw new InvalidArgumentException("Book copy status shouldn't be lightly updated: use borrow/return methods instead.");
        
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_book_copy_data = self::fetch_bookcopy_by_id($id_to_update) -> toArray();
        if (empty($current_book_copy_data)) return false;
        
        // Edit current book copy's data in order to scan them through checkers into model setters:
        foreach($current_book_copy_data as $field => &$v)
            if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
        
        try{
            $copy = BookCopy::fromArray($current_book_copy_data, false); // It's not a insertion instanciation
            $data['id'] = $id_to_update;
        }
        catch(Exception $e) {echo ("Edition setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(DB::BOOK_COPY_TABLE, $data);
    }
    
    public static function setBookCopyStatusAs(string $status, int $id_to_update, int $user_id): bool { // OK
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_book_copy = self::fetch_bookcopy_by_id($id_to_update);
        if (!is_null($current_book_copy)) $current_book_copy_data = $current_book_copy -> toArray();
        else return false;
        if (empty($current_book_copy_data)) return false;
        
        // Edit current book copy's status in order to scan if is a valid one:
        try{
            $copy = BookCopy::fromArray($current_book_copy_data, false); // It's not a insertion instanciation
            $copy -> set_status($status);
        }
        catch(Exception $e) {echo ("Edition setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(
            DB::BOOK_COPY_TABLE,
            ['id' => $id_to_update, 'status' => $copy -> get_status()]
        );
    }
    
    public static function edit_writer(int $id_to_update, array $data, int $user_id): bool { // OK        
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_writer_data = self::fetch_writer_by_id($id_to_update) -> toArray();
        if (empty($current_writer_data)) return false;
        
        // Edit current book copy's data if name is being change to scan it:
        if(isset($data['name'])){
            foreach($current_writer_data as $field => &$v)
                if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
            try{
                $w = Writer::fromArray($current_writer_data, false); // It's not a fetching instanciation
            }
            catch(Exception $e) {echo ("Edition setting failed: " . $e->getMessage()); return false;}
        }
        $data['id'] = $id_to_update;
        return $db_man->update_entity_in(DB::WRITER_TABLE, $data);
    }

    public static function edit_collection(int $id_to_update, array $data, int $user_id): bool { // OK        
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_collection_data = self::fetch_collection_by_id($id_to_update) -> toArray();
        if (empty($current_collection_data)) return false;
               
        $data['id'] = $id_to_update;
        return $db_man->update_entity_in(DB::COLLECTION_TABLE, $data);
    }

    public static function edit_publisher(int $id_to_update, array $data, int $user_id): bool { //OK
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_publisher_data = self::fetch_collection_by_id($id_to_update) -> toArray();
        if (empty($current_publisher_data)) return false;
               
        $data['id'] = $id_to_update;
        return $db_man->update_entity_in(DB::PUBLISHER_TABLE, $data);
    }
    
    public static function edit_all_opus_authorship( // OK
        array $writers, int $opus_id, int $user_id): bool{
        // Checks if writers contain only Writer instances:
        if(count($writers) !== count(array_filter($writers, fn($item) => $item instanceof Writer))) 
            throw new InvalidArgumentException(
                'An array of writer instances should be passed to edit authorships.');
        
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        
        self::clear_opus_authorships($opus_id, $user_id);
        foreach($writers as $w){
            $sql = self::register_authorship(
                ['opus_id' => $opus_id,
                'writer_id' => $w -> get_id()],
                $user_id);
            if(!$sql) return false;
        }
        
        return true;
    }


    // ----- ERASING
    // Row erasing
    public static function delete_edition(int $edition_id, int $user_id){  // OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        return $db_man -> delete_record_in(DB::EDITION_TABLE, $edition_id);
    }

    public static function delete_book_copy(int $book_copy_id, int $user_id){  // Not tested
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        return $db_man -> delete_record_in(DB::BOOK_COPY_TABLE, $book_copy_id);
    }

    // Special erasing
    public static function clear_opus_authorships(int $opus_id, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        
        $opus_authorships = self::fetch_authorships_from_opus($opus_id);
        if(empty($opus_authorships)) return false;
        foreach($opus_authorships as $a){
            $sql = $db_man -> delete_relationship(
            DB::AUTHORSHIP_TABLE, 'opus_id', 'writer_id',
            $opus_id, $a -> get_writer_id());
            if(!$sql) return false;
        }
        return true;
    }
    


}
// fetchOpusByAuthorAndTitle --> Ainda não...

?>