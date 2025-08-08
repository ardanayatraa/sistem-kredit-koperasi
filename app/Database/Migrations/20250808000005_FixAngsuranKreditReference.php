<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAngsuranKreditReference extends Migration
{
    public function up()
    {
        // Alter tbl_angsuran table to change column name from id_kredit to id_kredit_ref
        // Drop foreign key constraint first
        $this->forge->dropForeignKey('tbl_angsuran', 'tbl_angsuran_id_kredit_foreign');
        
        // Drop the existing foreign key column
        $this->forge->dropColumn('tbl_angsuran', 'id_kredit');
        
        // Add new column with correct naming convention
        $this->forge->addColumn('tbl_angsuran', [
            'id_kredit_ref' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'after' => 'id_angsuran',
            ]
        ]);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('id_kredit_ref', 'tbl_kredit', 'id_kredit', 'CASCADE', 'CASCADE', 'tbl_angsuran');
    }

    public function down()
    {
        // Reverse the changes
        // Drop foreign key constraint
        $this->forge->dropForeignKey('tbl_angsuran', 'tbl_angsuran_id_kredit_ref_foreign');
        
        // Drop the column
        $this->forge->dropColumn('tbl_angsuran', 'id_kredit_ref');
        
        // Add back original column
        $this->forge->addColumn('tbl_angsuran', [
            'id_kredit' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'after' => 'id_angsuran',
            ]
        ]);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('id_kredit', 'tbl_kredit', 'id_kredit', 'CASCADE', 'CASCADE', 'tbl_angsuran');
    }
}