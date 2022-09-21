<?php
class Migration_Add_user extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
           array(
              'id' => array(
                 'type' => 'INT',
                 'constraint' => 11,
                 'auto_increment' => true,
                 'null' => false
              ),
              'crm_id' => array(
                 'type' => 'INT',
                 'constraint' => '100',
                 'default' => 0
                 'null' => false,
              ),
              'fname' => array(
                 'type' => 'varchar',
                 'constraint' => '100',
                 'null' => false,

              ),
              'lname' => array(
                 'type' => 'varchar',
                 'constraint' => '100',
                 'null' => false,

              ),
              'city' => array(
                 'type' => 'varchar',
                 'constraint' => '50',
                 'default' => null,

              ),
              'state' => array(
                 'type' => 'varchar',
                 'constraint' => '255',
                 'null' => false,

              ),
              'country' => array(
                 'type' => 'varchar',
                 'constraint' => '255',
                 'null' => false,

              ),
              'address' => array(
                 'type' => 'TEXT',
              ),
              'phone' => array(
                 'type' => 'varchar',
                 'constraint' => '20',
                 'null' => false,

              ),
              'username' => array(
                 'type' => 'varchar',
                 'constraint' => '40',
                 'null' => false,
              ),
               'email' => array(
                 'type' => 'varchar',
                 'constraint' => '50',
                 'null' => false,
              ),
                'password' => array(
                   'type' => 'varchar',
                   'constraint' => '200',
                   'null' => false,
              ),
                 'role' => array(
                   'type' => 'varchar',
                   'constraint' => '30',
                   'null' => false,
              ),
                'status' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'null' => false,
              ),
                'dept_id' => array(
                   'type' => 'INT',
                   'constraint' => '30',
                   'null' => false,
              ),
                'picture' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'null' => false,
              ),
                'updated_on' => array(
                   'type' => 'datetime',
                   'default' => NULL
              ),
                'unique_id' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'null' => false,
              ),
                'type' => array(
                   'type' => 'ENUM("vendor","buyer","both")',
                   'constraint' => '50',
                   'default' =>NULL,
              ),
                'code' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'null' => false,
              ),
                'unique_id' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'null' => false,
              ),
                'code' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'null' => false,
              ),
                'code_status' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'null' => false,
              ),
                'unique_id' => array(
                   'type' => 'tinyint',
                   'constraint' => '4',
                   'null' => false,
              ),
                'reset_password_code' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'default' => NULL
              ),
                 'email_verification_code' => array(
                   'type' => 'INT',
                   'constraint' => '10',
                   'default' => NULL
              ),
                'created_on' => array(
                   'type' => 'datetime',
                   'null' => false
              ),
                'updated_by' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'default' => NULL
              ),
                'created_by' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'default' => NULL
              ),
                 'documents' => array(
                   'type' => 'varchar',
                   'constraint' => '500',
                   'default' => NULL
              ),
                'id_number' => array(
                   'type' => 'varchar',
                   'constraint' => '255',
                   'default' => NULL
              ),
                'po_box' => array(
                   'type' => 'INT',
                   'constraint' => '50',
                   'default' => NULL
              ),
                'job_title' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'default' => NULL
              ),
                 'vat' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'default' => NULL
              ),
                'vat_number' => array(
                   'type' => 'INT',
                   'constraint' => '50',
                   'default' => NULL
              ),
                'company_name' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'default' => NULL
              ),
                'remarks' => array(
                   'type' => 'varchar',
                   'constraint' => '100',
                   'default' => NULL
              ),
                'sales_id' => array(
                   'type' => 'INT',
                   'constraint' => '50',
                   'default' => NULL
              ),
                'payment' => array(
                   'type' => 'ENUM("pending","complete")',
                   'default' => NULL
              ),
                'buyer_commission' => array(
                   'type' => 'double',
                   'constraint' => '500',
                   'default' => NULL
              ),
                'description' => array(
                   'type' => 'varchar',
                   'constraint' => '500',
                   'null' => false
              ),
                'prefered_language' => array(
                   'type' => 'varchar',
                   'constraint' => '20',
                   'default' => NULL
              ),
                'social' => array(
                   'type' => 'varchar',
                   'constraint' => '25',
                   'null' => false
              ),
                'mobile' => array(
                   'type' => 'char',
                   'constraint' => '20',
                   'default' => NULL
              ),
                'dial_code' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'null' => false
              ),
                 'reg_type' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'collation' => 'CHARACTER SET utf8',
                   'null' => false
              ),
                'crm_status' => array(
                   'type' => ENUM('mature','immature'),
                   'null' => false
              ),
                'task_title' => array(
                   'type' => 'varchar',
                   'constraint' => '50',
                   'default' => null
              ),
                'task_detail' => array(
                   'type' => 'varchar',
                   'constraint' => '600',
                   'default' => null
              ),
                'assigned_to' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'default' => null
              ),
                'total_deposite' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'default' => null
              ),
                'operational_manager_id' => array(
                   'type' => 'INT',
                   'constraint' => '11',
                   'default' => null
              ),

           )
        );

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
//////////

// CREATE TABLE `users` (
// `flag` enum('0','1') DEFAULT NULL
// ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

// ALTER TABLE `users`
// ADD PRIMARY KEY (`id`);
// ALTER TABLE `users`
// MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
