<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Init extends AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8';");
        $this->execute("ALTER DATABASE COLLATE='utf8_unicode_ci';");
        $table = $this->table("activations", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('activations')->hasColumn('id')) {
            $this->table("activations")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("activations")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('user_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('code', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'user_id'])->update();;
        $table->addColumn('completed', 'integer', ['null' => false, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'code'])->update();;
        $table->addColumn('completed_at', 'timestamp', ['null' => true, 'after' => 'completed'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'completed_at'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->save();
        if($this->table('activations')->hasIndex('fk_activations_user_id_idx')) {
            $this->table("activations")->removeIndexByName('fk_activations_user_id_idx');
        }
        $this->table("activations")->addIndex(['user_id'], ['name' => "fk_activations_user_id_idx", 'unique' => false])->save();
        $table = $this->table("adverts", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('adverts')->hasColumn('id')) {
            $this->table("adverts")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("adverts")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('type', 'string', ['null' => true, 'limit' => 50, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('widget_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'type'])->update();;
        $table->addColumn('categories', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'widget_id'])->update();;
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'categories'])->update();;
        $table->addColumn('date_start', 'datetime', ['null' => true, 'after' => 'name'])->update();;
        $table->addColumn('date_end', 'datetime', ['null' => true, 'after' => 'date_start'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'date_end'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'enabled'])->update();;
        $table->addColumn('file1', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'css_class'])->update();;
        $table->addColumn('file2', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'file1'])->update();;
        $table->save();
        if($this->table('adverts')->hasIndex('publicidadTipoId_fk_idx')) {
            $this->table("adverts")->removeIndexByName('publicidadTipoId_fk_idx');
        }
        $this->table("adverts")->addIndex(['type'], ['name' => "publicidadTipoId_fk_idx", 'unique' => false])->save();
        $table = $this->table("calendar", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('calendar')->hasColumn('id')) {
            $this->table("calendar")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("calendar")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'id'])->update();;
        $table->addColumn('date', 'date', ['null' => false, 'after' => 'enabled'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'date'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'temporary'])->update();;
        $table->save();
        $table = $this->table("calendar_activities", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('calendar_activities')->hasColumn('id')) {
            $this->table("calendar_activities")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("calendar_activities")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('time', 'time', ['null' => false, 'after' => 'id'])->update();;
        $table->addColumn('calendar_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'time'])->update();;
        $table->addColumn('place_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'calendar_id'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'place_id'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'temporary'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_MEDIUM, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'comment' => "temporary field untill I finish translations and dynamic fields", 'after' => 'enabled'])->update();;
        $table->save();
        if($this->table('calendar_activities')->hasIndex('fk_calendar_id')) {
            $this->table("calendar_activities")->removeIndexByName('fk_calendar_id');
        }
        $this->table("calendar_activities")->addIndex(['calendar_id'], ['name' => "fk_calendar_id", 'unique' => false])->save();
        if($this->table('calendar_activities')->hasIndex('fk_activities_placeId_idx')) {
            $this->table("calendar_activities")->removeIndexByName('fk_activities_placeId_idx');
        }
        $this->table("calendar_activities")->addIndex(['place_id'], ['name' => "fk_activities_placeId_idx", 'unique' => false])->save();
        $table = $this->table("categories", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('categories')->hasColumn('id')) {
            $this->table("categories")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("categories")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('parent_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('depth', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'parent_id'])->update();;
        $table->addColumn('lft', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'depth'])->update();;
        $table->addColumn('rgt', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'lft'])->update();;
        $table->addColumn('order', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'rgt'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'order'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'css_class'])->update();;
        $table->addColumn('private', 'boolean', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'enabled'])->update();;
        $table->addColumn('image', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'private'])->update();;
        $table->addColumn('url', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'image'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_MEDIUM, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'url'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'data'])->update();;
        $table->addColumn('popup', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'temporary'])->update();;
        $table->addColumn('type', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'popup'])->update();;
        $table->addColumn('is_content', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'type'])->update();;
        $table->addColumn('content_type', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'is_content'])->update();;
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'content_type'])->update();;
        $table->addColumn('updated_at', 'datetime', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'updated_at'])->update();;
        $table->addColumn('group_visibility', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'deleted_at'])->update();;
        $table->save();
        $table = $this->table("ci_sessions", ['id' => false, 'primary_key' => ["session_id"], 'engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->addColumn('session_id', 'string', ['null' => false, 'default' => '0', 'limit' => 40, 'collation' => "utf8_general_ci", 'encoding' => "utf8"]);
        $table->addColumn('ip_address', 'string', ['null' => false, 'default' => '0', 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'session_id']);
        $table->addColumn('user_agent', 'string', ['null' => false, 'limit' => 120, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'ip_address']);
        $table->addColumn('last_activity', 'integer', ['null' => false, 'default' => '0', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'user_agent']);
        $table->addColumn('user_data', 'text', ['null' => false, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'last_activity']);
        $table->addColumn('prevent_update', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'user_data']);
        $table->save();
        if($this->table('ci_sessions')->hasIndex('last_activity_idx')) {
            $this->table("ci_sessions")->removeIndexByName('last_activity_idx');
        }
        $this->table("ci_sessions")->addIndex(['last_activity'], ['name' => "last_activity_idx", 'unique' => false])->save();
        $table = $this->table("config", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('config')->hasColumn('id')) {
            $this->table("config")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("config")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('key', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('value', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'key'])->update();;
        $table->addColumn('group', 'string', ['null' => true, 'default' => 'general', 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'value'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'group'])->update();;
        $table->save();
        $table = $this->table("content", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('content')->hasColumn('id')) {
            $this->table("content")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("content")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('category_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'css_class'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'category_id'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'enabled'])->update();;
        $table->addColumn('important', 'boolean', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'temporary'])->update();;
        $table->addColumn('timezone', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'important'])->update();;
        $table->addColumn('publication_start', 'datetime', ['null' => true, 'after' => 'timezone'])->update();;
        $table->addColumn('publication_end', 'datetime', ['null' => true, 'after' => 'publication_start'])->update();;
        $table->addColumn('module', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'publication_end'])->update();;
        $table->addColumn('data', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'module'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'data'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'position'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('content')->hasIndex('paginaId')) {
            $this->table("content")->removeIndexByName('paginaId');
        }
        $this->table("content")->addIndex(['category_id'], ['name' => "paginaId", 'unique' => false])->save();
        if($this->table('content')->hasIndex('publicacionHabilitado')) {
            $this->table("content")->removeIndexByName('publicacionHabilitado');
        }
        $this->table("content")->addIndex(['enabled'], ['name' => "publicacionHabilitado", 'unique' => false])->save();
        $table = $this->table("field_data", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('field_data')->hasColumn('id')) {
            $this->table("field_data")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("field_data")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('parent_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('field_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'parent_id'])->update();;
        $table->addColumn('section', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'field_id'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'section'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'data'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('field_data')->hasIndex('id_UNIQUE')) {
            $this->table("field_data")->removeIndexByName('id_UNIQUE');
        }
        $this->table("field_data")->addIndex(['id'], ['name' => "id_UNIQUE", 'unique' => true])->save();
        if($this->table('field_data')->hasIndex('key_unique_field_data')) {
            $this->table("field_data")->removeIndexByName('key_unique_field_data');
        }
        $this->table("field_data")->addIndex(['parent_id','field_id','section'], ['name' => "key_unique_field_data", 'unique' => true])->save();
        if($this->table('field_data')->hasIndex('fk_field_data_field_id_idx')) {
            $this->table("field_data")->removeIndexByName('fk_field_data_field_id_idx');
        }
        $this->table("field_data")->addIndex(['field_id'], ['name' => "fk_field_data_field_id_idx", 'unique' => false])->save();
        $table = $this->table("fields", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('fields')->hasColumn('id')) {
            $this->table("fields")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("fields")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('input_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('parent_id', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'input_id'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'parent_id'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'position'])->update();;
        $table->addColumn('section', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'css_class'])->update();;
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'section'])->update();;
        $table->addColumn('label_enabled', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'name'])->update();;
        $table->addColumn('required', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'label_enabled'])->update();;
        $table->addColumn('validation', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'required'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_TINY, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'validation'])->update();;
        $table->addColumn('view_in', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'data'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'view_in'])->update();;
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'enabled'])->update();;
        $table->addColumn('updated_at', 'datetime', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'datetime', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('fields')->hasIndex('campoId')) {
            $this->table("fields")->removeIndexByName('campoId');
        }
        $this->table("fields")->addIndex(['input_id'], ['name' => "campoId", 'unique' => false])->save();
        if($this->table('fields')->hasIndex('inputId_bc_idx')) {
            $this->table("fields")->removeIndexByName('inputId_bc_idx');
        }
        $this->table("fields")->addIndex(['input_id'], ['name' => "inputId_bc_idx", 'unique' => false])->save();
        $table = $this->table("files", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('files')->hasColumn('id')) {
            $this->table("files")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("files")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('parent_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('section_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'parent_id'])->update();;
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'section_id'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'name'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'position'])->update();;
        $table->addColumn('link', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'data'])->update();;
        $table->addColumn('date', 'date', ['null' => true, 'after' => 'link'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'date'])->update();;
        $table->addColumn('type', 'string', ['null' => true, 'limit' => 10, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'enabled'])->update();;
        $table->addColumn('mime_type', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'type'])->update();;
        $table->addColumn('file_ext', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'mime_type'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'file_ext'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('files')->hasIndex('descargaCategoriaId_d')) {
            $this->table("files")->removeIndexByName('descargaCategoriaId_d');
        }
        $this->table("files")->addIndex(['parent_id'], ['name' => "descargaCategoriaId_d", 'unique' => false])->save();
        if($this->table('files')->hasIndex('descargaCategoriaId_idx')) {
            $this->table("files")->removeIndexByName('descargaCategoriaId_idx');
        }
        $this->table("files")->addIndex(['parent_id'], ['name' => "descargaCategoriaId_idx", 'unique' => false])->save();
        $table = $this->table("forms", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('forms')->hasColumn('id')) {
            $this->table("forms")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("forms")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('email', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'email'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'name'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('forms')->hasIndex('id_UNIQUE')) {
            $this->table("forms")->removeIndexByName('id_UNIQUE');
        }
        $this->table("forms")->addIndex(['id'], ['name' => "id_UNIQUE", 'unique' => true])->save();
        $table = $this->table("image_sections", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('image_sections')->hasColumn('id')) {
            $this->table("image_sections")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("image_sections")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('section', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('multiple_upload', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'section'])->update();;
        $table->save();
        $table = $this->table("images_config", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('images_config')->hasColumn('id')) {
            $this->table("images_config")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("images_config")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('image_section_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('sufix', 'string', ['null' => true, 'default' => '_huge', 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'image_section_id'])->update();;
        $table->addColumn('width', 'integer', ['null' => true, 'default' => '500', 'limit' => MysqlAdapter::INT_SMALL, 'precision' => 5, 'after' => 'sufix'])->update();;
        $table->addColumn('height', 'integer', ['null' => true, 'default' => '300', 'limit' => MysqlAdapter::INT_SMALL, 'precision' => 5, 'after' => 'width'])->update();;
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'height'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'name'])->update();;
        $table->addColumn('crop', 'boolean', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'position'])->update();;
        $table->addColumn('force_jpg', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'crop'])->update();;
        $table->addColumn('optimize_original', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'force_jpg'])->update();;
        $table->addColumn('background_color', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'optimize_original'])->update();;
        $table->addColumn('quality', 'decimal', ['null' => true, 'precision' => 3, 'after' => 'background_color'])->update();;
        $table->addColumn('restrict_proportions', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'quality'])->update();;
        $table->addColumn('watermark', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'restrict_proportions'])->update();;
        $table->addColumn('watermark_file_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'watermark'])->update();;
        $table->addColumn('watermark_position', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'watermark_file_id'])->update();;
        $table->addColumn('watermark_alpha', 'decimal', ['null' => true, 'precision' => 3, 'after' => 'watermark_position'])->update();;
        $table->addColumn('watermark_repeat', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'watermark_alpha'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'watermark_repeat'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('images_config')->hasIndex('fk_images_config_image_section_id_idx')) {
            $this->table("images_config")->removeIndexByName('fk_images_config_image_section_id_idx');
        }
        $this->table("images_config")->addIndex(['image_section_id'], ['name' => "fk_images_config_image_section_id_idx", 'unique' => false])->save();
        $table = $this->table("input_type", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('input_type')->hasColumn('id')) {
            $this->table("input_type")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("input_type")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 30, 'collation' => "latin1_swedish_ci", 'encoding' => "latin1", 'after' => 'id'])->update();;
        $table->save();
        if($this->table('input_type')->hasIndex('fk_input_tipo_input1')) {
            $this->table("input_type")->removeIndexByName('fk_input_tipo_input1');
        }
        $this->table("input_type")->addIndex(['id'], ['name' => "fk_input_tipo_input1", 'unique' => false])->save();
        $table = $this->table("inputs", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('inputs')->hasColumn('id')) {
            $this->table("inputs")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("inputs")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('content', 'text', ['null' => false, 'limit' => 65535, 'collation' => "latin1_swedish_ci", 'encoding' => "latin1", 'after' => 'id'])->update();;
        $table->addColumn('input_type_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'content'])->update();;
        $table->addColumn('section', 'string', ['null' => false, 'limit' => 10, 'collation' => "latin1_swedish_ci", 'encoding' => "latin1", 'comment' => "donde se mostrara el input contacto , producto o ambos", 'after' => 'input_type_id'])->update();;
        $table->save();
        if($this->table('inputs')->hasIndex('fk_input_contacto_inputs_rel1')) {
            $this->table("inputs")->removeIndexByName('fk_input_contacto_inputs_rel1');
        }
        $this->table("inputs")->addIndex(['id'], ['name' => "fk_input_contacto_inputs_rel1", 'unique' => false])->save();
        if($this->table('inputs')->hasIndex('inputTipoId')) {
            $this->table("inputs")->removeIndexByName('inputTipoId');
        }
        $this->table("inputs")->addIndex(['input_type_id'], ['name' => "inputTipoId", 'unique' => false])->save();
        if($this->table('inputs')->hasIndex('inputTipoId_i')) {
            $this->table("inputs")->removeIndexByName('inputTipoId_i');
        }
        $this->table("inputs")->addIndex(['input_type_id'], ['name' => "inputTipoId_i", 'unique' => false])->save();
        $table = $this->table("languages", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('languages')->hasColumn('id')) {
            $this->table("languages")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("languages")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 25, 'collation' => "latin1_swedish_ci", 'encoding' => "latin1", 'after' => 'id'])->update();;
        $table->addColumn('slug', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('position', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'slug'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'position'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('languages')->hasIndex('id_UNIQUE')) {
            $this->table("languages")->removeIndexByName('id_UNIQUE');
        }
        $this->table("languages")->addIndex(['id'], ['name' => "id_UNIQUE", 'unique' => true])->save();
        $table = $this->table("login_attempts", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('login_attempts')->hasColumn('id')) {
            $this->table("login_attempts")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'precision' => 7, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("login_attempts")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'precision' => 7, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('ip_address', 'varbinary', ['null' => false, 'limit' => 16, 'after' => 'id'])->update();;
        $table->addColumn('login', 'string', ['null' => false, 'limit' => 100, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'ip_address'])->update();;
        $table->addColumn('time', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'login'])->update();;
        $table->save();
        $table = $this->table("map_locations", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('map_locations')->hasColumn('id')) {
            $this->table("map_locations")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("map_locations")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('map_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'map_id'])->update();;
        $table->addColumn('coords', 'string', ['null' => false, 'limit' => 100, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('image', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'coords'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => false, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'image'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'enabled'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'temporary'])->update();;
        $table->save();
        if($this->table('map_locations')->hasIndex('mapaId_idx')) {
            $this->table("map_locations")->removeIndexByName('mapaId_idx');
        }
        $this->table("map_locations")->addIndex(['map_id'], ['name' => "mapaId_idx", 'unique' => false])->save();
        if($this->table('map_locations')->hasIndex('mapaId_mu')) {
            $this->table("map_locations")->removeIndexByName('mapaId_mu');
        }
        $this->table("map_locations")->addIndex(['map_id'], ['name' => "mapaId_mu", 'unique' => false])->save();
        $table = $this->table("maps", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('maps')->hasColumn('id')) {
            $this->table("maps")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        } else {
            $this->table("maps")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        }
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('image', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => false, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'image'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'enabled'])->update();;
        $table->save();
        $table = $this->table("persistences", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('persistences')->hasColumn('id')) {
            $this->table("persistences")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("persistences")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('user_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('code', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'user_id'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'code'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->save();
        if($this->table('persistences')->hasIndex('code_UNIQUE')) {
            $this->table("persistences")->removeIndexByName('code_UNIQUE');
        }
        $this->table("persistences")->addIndex(['code'], ['name' => "code_UNIQUE", 'unique' => true])->save();
        if($this->table('persistences')->hasIndex('fk_persistences_user_id_idx')) {
            $this->table("persistences")->removeIndexByName('fk_persistences_user_id_idx');
        }
        $this->table("persistences")->addIndex(['user_id'], ['name' => "fk_persistences_user_id_idx", 'unique' => false])->save();
        $table = $this->table("predefined_lists", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('predefined_lists')->hasColumn('id')) {
            $this->table("predefined_lists")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("predefined_lists")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('field_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'field_id'])->update();;
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'enabled'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'css_class'])->update();;
        $table->save();
        if($this->table('predefined_lists')->hasIndex('productoCampoId_pclp_idx')) {
            $this->table("predefined_lists")->removeIndexByName('productoCampoId_pclp_idx');
        }
        $this->table("predefined_lists")->addIndex(['field_id'], ['name' => "productoCampoId_pclp_idx", 'unique' => false])->save();
        $table = $this->table("products", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('products')->hasColumn('id')) {
            $this->table("products")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("products")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('important', 'boolean', ['null' => false, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'comment' => "1 si es producto del dia 0 si no ", 'after' => 'id'])->update();;
        $table->addColumn('category_id', 'integer', ['null' => false, 'default' => '1', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'important'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => false, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'comment' => "si,no para mostrar consultas", 'after' => 'category_id'])->update();;
        $table->addColumn('image', 'string', ['null' => true, 'limit' => 255, 'collation' => "latin1_swedish_ci", 'encoding' => "latin1", 'after' => 'enabled'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'image'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'position'])->update();;
        $table->addColumn('stock_quantity', 'integer', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_SMALL, 'precision' => 5, 'after' => 'temporary'])->update();;
        $table->addColumn('stock_auto_allocate_status', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'stock_quantity'])->update();;
        //$table->addColumn('weight', '[double]', ['null' => true, 'precision' => 22, 'after' => 'stock_auto_allocate_status'])->update();;
        $this->execute('ALTER TABLE products ADD COLUMN weight DOUBLE(13,4) NULL;');
        $table->addColumn('css_class', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'weight'])->update();;
        $table->addColumn('visible_to', 'string', ['null' => true, 'default' => 'public', 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'css_class'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'visible_to'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('products')->hasIndex('categoriaId_idx')) {
            $this->table("products")->removeIndexByName('categoriaId_idx');
        }
        $this->table("products")->addIndex(['category_id'], ['name' => "categoriaId_idx", 'unique' => false])->save();
        $table = $this->table("reminders", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('reminders')->hasColumn('id')) {
            $this->table("reminders")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("reminders")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('user_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('code', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'user_id'])->update();;
        $table->addColumn('completed', 'integer', ['null' => false, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'code'])->update();;
        $table->addColumn('completed_at', 'timestamp', ['null' => true, 'after' => 'completed'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'completed_at'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->save();
        if($this->table('reminders')->hasIndex('fk_reminders_user_id_idx')) {
            $this->table("reminders")->removeIndexByName('fk_reminders_user_id_idx');
        }
        $this->table("reminders")->addIndex(['user_id'], ['name' => "fk_reminders_user_id_idx", 'unique' => false])->save();
        $table = $this->table("role_sections", ['id' => false, 'primary_key' => ["role_id", "section_name"], 'engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->addColumn('role_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false]);
        $table->addColumn('section_name', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'role_id']);
        $table->save();
        $table = $this->table("role_users", ['id' => false, 'primary_key' => ["user_id", "role_id"], 'engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->addColumn('user_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false]);
        $table->addColumn('role_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'user_id']);
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'role_id']);
        $table->addColumn('updated_at', 'datetime', ['null' => true, 'after' => 'created_at']);
        $table->save();
        if($this->table('role_users')->hasIndex('fk_role_users_role_id_idx')) {
            $this->table("role_users")->removeIndexByName('fk_role_users_role_id_idx');
        }
        $this->table("role_users")->addIndex(['role_id'], ['name' => "fk_role_users_role_id_idx", 'unique' => false])->save();
        $table = $this->table("roles", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('roles')->hasColumn('id')) {
            $this->table("roles")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("roles")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('slug', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('name', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'slug'])->update();;
        $table->addColumn('permissions', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'permissions'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->save();
        if($this->table('roles')->hasIndex('slug_UNIQUE')) {
            $this->table("roles")->removeIndexByName('slug_UNIQUE');
        }
        $this->table("roles")->addIndex(['slug'], ['name' => "slug_UNIQUE", 'unique' => true])->save();
        $table = $this->table("sections", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('sections')->hasColumn('id')) {
            $this->table("sections")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        } else {
            $this->table("sections")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        }
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('controller', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('position', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'controller'])->update();;
        $table->addColumn('view_menu', 'boolean', ['null' => true, 'default' => '0', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'position'])->update();;
        $table->addColumn('desc', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'view_menu'])->update();;
        $table->save();
        $table = $this->table("sliders", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('sliders')->hasColumn('id')) {
            $this->table("sliders")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("sliders")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('name', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('class', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'name'])->update();;
        $table->addColumn('type', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'class'])->update();;
        $table->addColumn('width', 'integer', ['null' => true, 'default' => '800', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'type'])->update();;
        $table->addColumn('height', 'integer', ['null' => true, 'default' => '600', 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'width'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'height'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'enabled'])->update();;
        $table->addColumn('config', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'temporary'])->update();;
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'config'])->update();;
        $table->addColumn('updated_at', 'datetime', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'updated_at'])->update();;
        $table->save();
        $table = $this->table("stats", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('stats')->hasColumn('id')) {
            $this->table("stats")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        } else {
            $this->table("stats")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10])->update();
        }
        $table->addColumn('ip', 'string', ['null' => false, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('category_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'ip'])->update();;
        $table->addColumn('date', 'datetime', ['null' => false, 'after' => 'category_id'])->update();;
        $table->addColumn('url', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'date'])->update();;
        $table->save();
        if($this->table('stats')->hasIndex('estadisticaUserIP')) {
            $this->table("stats")->removeIndexByName('estadisticaUserIP');
        }
        $this->table("stats")->addIndex(['ip'], ['name' => "estadisticaUserIP", 'unique' => false])->save();
        if($this->table('stats')->hasIndex('estadisticaFecha')) {
            $this->table("stats")->removeIndexByName('estadisticaFecha');
        }
        $this->table("stats")->addIndex(['date'], ['name' => "estadisticaFecha", 'unique' => false])->save();
        if($this->table('stats')->hasIndex('paginaId_e_idx')) {
            $this->table("stats")->removeIndexByName('paginaId_e_idx');
        }
        $this->table("stats")->addIndex(['category_id'], ['name' => "paginaId_e_idx", 'unique' => false])->save();
        $table = $this->table("throttle", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('throttle')->hasColumn('id')) {
            $this->table("throttle")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("throttle")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('user_id', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'after' => 'id'])->update();;
        $table->addColumn('type', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'user_id'])->update();;
        $table->addColumn('ip', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'type'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'after' => 'ip'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->save();
        $table = $this->table("translations", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('translations')->hasColumn('id')) {
            $this->table("translations")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("translations")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('parent_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('language_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'parent_id'])->update();;
        $table->addColumn('type', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'comment' => "widget, content, field", 'after' => 'language_id'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_MEDIUM, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'type'])->update();;
        $table->addColumn('created_at', 'datetime', ['null' => true, 'after' => 'data'])->update();;
        $table->addColumn('updated_at', 'datetime', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('translations')->hasIndex('key_unique_translations')) {
            $this->table("translations")->removeIndexByName('key_unique_translations');
        }
        $this->table("translations")->addIndex(['parent_id','language_id','type'], ['name' => "key_unique_translations", 'unique' => true])->save();
        if($this->table('translations')->hasIndex('fk_translations_1_idx')) {
            $this->table("translations")->removeIndexByName('fk_translations_1_idx');
        }
        $this->table("translations")->addIndex(['parent_id'], ['name' => "fk_translations_1_idx", 'unique' => false])->save();
        if($this->table('translations')->hasIndex('fk_translations_language_id_idx')) {
            $this->table("translations")->removeIndexByName('fk_translations_language_id_idx');
        }
        $this->table("translations")->addIndex(['language_id'], ['name' => "fk_translations_language_id_idx", 'unique' => false])->save();
        $table = $this->table("users", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('users')->hasColumn('id')) {
            $this->table("users")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        } else {
            $this->table("users")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'signed' => false, 'identity' => 'enable'])->update();
        }
        $table->addColumn('email', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'id'])->update();;
        $table->addColumn('password', 'string', ['null' => false, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'email'])->update();;
        $table->addColumn('permissions', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'password'])->update();;
        $table->addColumn('last_login', 'timestamp', ['null' => true, 'after' => 'permissions'])->update();;
        $table->addColumn('first_name', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'last_login'])->update();;
        $table->addColumn('last_name', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'first_name'])->update();;
        $table->addColumn('image_extension', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'last_name'])->update();;
        $table->addColumn('image_coord', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'image_extension'])->update();;
        $table->addColumn('temporary', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'image_coord'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'temporary'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'after' => 'created_at'])->update();;
        $table->save();
        if($this->table('users')->hasIndex('email_email_unique')) {
            $this->table("users")->removeIndexByName('email_email_unique');
        }
        $this->table("users")->addIndex(['email'], ['name' => "email_email_unique", 'unique' => true])->save();
        $table = $this->table("widgets", ['engine' => "InnoDB", 'encoding' => "utf8", 'collation' => "utf8_general_ci", 'comment' => ""]);
        $table->save();
        if ($this->table('widgets')->hasColumn('id')) {
            $this->table("widgets")->changeColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        } else {
            $this->table("widgets")->addColumn('id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'identity' => 'enable'])->update();
        }
        $table->addColumn('category_id', 'integer', ['null' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'precision' => 10, 'after' => 'id'])->update();;
        $table->addColumn('view', 'string', ['null' => true, 'default' => 'default_view.php', 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'category_id'])->update();;
        $table->addColumn('class', 'string', ['null' => true, 'limit' => 255, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'view'])->update();;
        $table->addColumn('enabled', 'boolean', ['null' => true, 'default' => '1', 'limit' => MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'class'])->update();;
        $table->addColumn('type', 'string', ['null' => true, 'limit' => 45, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'enabled'])->update();;
        $table->addColumn('data', 'text', ['null' => true, 'limit' => 65535, 'collation' => "utf8_general_ci", 'encoding' => "utf8", 'after' => 'type'])->update();;
        $table->addColumn('created_at', 'timestamp', ['null' => true, 'after' => 'data'])->update();;
        $table->addColumn('updated_at', 'timestamp', ['null' => true, 'after' => 'created_at'])->update();;
        $table->addColumn('deleted_at', 'timestamp', ['null' => true, 'after' => 'updated_at'])->update();;
        $table->save();
        if($this->table('widgets')->hasIndex('paginaId_m')) {
            $this->table("widgets")->removeIndexByName('paginaId_m');
        }
        $this->table("widgets")->addIndex(['category_id'], ['name' => "paginaId_m", 'unique' => false])->save();
    }
}
