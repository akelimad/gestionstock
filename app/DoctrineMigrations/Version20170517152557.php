<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170517152557 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            INSERT INTO User (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles) VALUES (NULL, "t11", "t11", "t11@gmail.com", "t11@gmail.com", "1", NULL, "$2y$13$AXD86k1MQkM9rWBEi78f6ORkGB42AFBMS188.DU/bHTYsCN0WyFLW", "2017-04-27 17:53:24", NULL, NULL, "a:0:{}");
            
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
