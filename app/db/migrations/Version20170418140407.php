<?php

namespace Silex\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170418140407 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $category = $schema->createTable('commentaire_video');

        $category->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement'=>true]);
        $category->addColumn('author', 'string', ['length' => 50]);
        $category->addColumn('content', 'text');
        $category->addColumn('video_id', 'integer');
        $category->addColumn('user_id', 'integer');
        $category->addColumn('created_at', 'datetime',['default' => CURRENT_TIMESTAMP]);
        $category->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('commentaire_video');
    }
}
