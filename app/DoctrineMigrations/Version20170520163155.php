<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170520163155 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('
            INSERT INTO User (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, created_at, deleted_at) VALUES (NULL, "admin", "admin", "admin@gmail.com", "admin@gmail.com", "1", NULL, "$2y$13$YCBsEgmoNGnhF/fW.7iVROVqiB7JH2.g4UBi88lVd94BMJfx8HHQW", "2017-04-27 17:53:24", NULL, NULL, "a:1:{}", "2017-05-22 09:53:24", NULL)
            
        ');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
