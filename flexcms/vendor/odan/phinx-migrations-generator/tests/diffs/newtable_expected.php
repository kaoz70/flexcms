<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class MyNewMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("newtable", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('newtable')->hasColumn('id')) {
            $this->table("newtable")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("newtable")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->save();
    }
}
