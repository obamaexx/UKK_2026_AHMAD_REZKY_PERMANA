<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // FUNCTION: hitung denda (Rp 5.000 per hari keterlambatan)
        DB::unprepared("DROP FUNCTION IF EXISTS fn_hitung_denda");
        DB::unprepared("
        CREATE FUNCTION fn_hitung_denda(p_due DATE, p_return DATE) RETURNS INT
        DETERMINISTIC
        BEGIN
            IF p_return > p_due THEN
                RETURN DATEDIFF(p_return, p_due) * 5000;
            END IF;
            RETURN 0;
        END");

        // STORED PROCEDURE: setujui peminjaman (transaksi + commit/rollback)
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_approve_loan");
        DB::unprepared("
        CREATE PROCEDURE sp_approve_loan(IN p_loan_id BIGINT, IN p_officer_id BIGINT)
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                ROLLBACK;
                RESIGNAL;
            END;

            START TRANSACTION;

            IF EXISTS (SELECT 1 FROM loan_details ld JOIN tools t ON t.id = ld.tool_id
                       WHERE ld.loan_id = p_loan_id AND t.stock < ld.qty) THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok alat tidak mencukupi';
            END IF;

            UPDATE tools t JOIN loan_details ld ON ld.tool_id = t.id
            SET t.stock = t.stock - ld.qty WHERE ld.loan_id = p_loan_id;

            UPDATE loans SET status = 'approved', approved_by = p_officer_id, approved_at = NOW()
            WHERE id = p_loan_id AND status = 'pending';

            COMMIT;
        END");

        // TRIGGER: setelah ada pengembalian, stok bertambah dan status jadi returned
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_return");
        DB::unprepared("
        CREATE TRIGGER trg_after_return AFTER INSERT ON loan_returns
        FOR EACH ROW
        BEGIN
            UPDATE tools t JOIN loan_details ld ON ld.tool_id = t.id
            SET t.stock = t.stock + ld.qty WHERE ld.loan_id = NEW.loan_id;

            UPDATE loans SET status = 'returned' WHERE id = NEW.loan_id;
        END");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_return");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_approve_loan");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_hitung_denda");
    }
};