<?php

namespace Silex\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170422105458 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $follow = $schema->createTable('followers');

        $follow->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement'=>true]);
        $follow->addColumn('user_id', 'integer');
        $follow->addColumn('followed_id', 'integer');
        $follow->addColumn('created_at', 'datetime',['default' => CURRENT_TIMESTAMP]);
        $follow->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('followers');
    }
}
