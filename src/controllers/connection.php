<?php

final class DB {
    const READER_TABLE = 'readers';
    const READER_FIELDS = ['id', 'login', 'passphrase', 'name', 'phone', 'role', 'debt', 'can_borrow', 'can_register'];
    const CLASSROOM_TABLE = 'classrooms';
    const CLASSROOM_FIELDS = ['name', 'year'];
    const ENROLLMENT_TABLE = 'enrollments';
    const ENROLLMENT_FIELDS = ['student_id', 'classroom_id'];
    const TEACHING_TABLE = 'teachings';
    const TEACHING_FIELDS = ['teacher_id', 'classroom_id'];
    const LIBRARY_SETTING_TABLE = 'Library_Settings';
    const LIBRARY_SETTING_FIELDS = ['loan_deadline', 'late_fee', 'late_fee_period', 'head_reserve_queue_deadline'];
    const OPUS_TABLE = 'opuses';
    const OPUS_FIELDS = ['id', 'title', 'original_year', 'ddc', 'cutter_sunborn', 'alternative_url'];
    const WRITER_TABLE = 'writers';
    const WRITER_FIELDS = ['id', 'name', 'birth_year'];
    const AUTHORSHIP_TABLE = 'authorship';
    const AUTHORSHIP_FIELDS = ['author_id', 'opus_id'];
    const PUBLISHER_TABLE = 'publishers';
    const PUBLISHER_FIELDS = ['id', 'name'];
    const EDITION_TABLE = 'editions';
    const EDITION_FIELDS = ['id', 'isbn', 'opus_id', 'publisher_id', 'translators', 'edition_number', 'volume', 'collection_id', 'pages', 'publishing_year', 'cover_colors'];
    const COLLECTION_TABLE = 'collections';
    const COLLECTION_FIELDS = ['id', 'name', 'publisher_id'];
    const BOOK_COPY_TABLE = 'bookshelf';
    const BOOK_COPY_FIELDS = ['id', 'edition_id', 'asset_number', 'status'];
    const LOAN_TABLE = 'loans';
    const LOAN_FIELDS = ['id', 'copy_id', 'reader_id', 'opener_id', 'opening_date', 'closer_id', 'closing_date', 'loan_date', 'return_date', 'debt', 'debt_receiver_id'];
    const RECOMENDATION_TABLE = 'recomendations';
    const RECOMENDATION_FIELDS = ['opus_id', 'student_id', 'teacher_id', 'operation_date'];
    const RESERVATION_TABLE = 'reservations';
    const RESERVATION_FIELDS = ['opus_id', 'reader_id', 'operation_date'];
    const SYNOPSIS_TABLE = 'synopses';
    const SYNOPSIS_FIELDS = ['id', 'opus_id', 'student_id', 'teacher_id', 'status', 'updating_date'];
    const LIKE_TABLE = 'likes';
    const LIKE_FIELDS = ['synopsis_id', 'liker_id'];
}

final class Connection {
    private $config;
    private static $pdo = null;

    public function __construct() {
        $this->config = require 'config.php';
    }

    public function connect(): PDO {
        if (self::$pdo === null)
            try {
                self::$pdo = new PDO(
                    "pgsql:host=localhost;
                    dbname={
                        $this->config['db_name']}",
                        $this->config['db_user'],
                        $this->config['db_pass']);
            }
            catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            return self::$pdo;
    }
}
?>