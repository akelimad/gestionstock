<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170607131647 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE category SET code = "01" where id = 1 ');
        $this->addSql('UPDATE category SET code = "02" where id = 20 ');
        $this->addSql('UPDATE category SET code = "03" where id = 3 ');
        $this->addSql('UPDATE category SET code = "04" where id = 12 ');
        $this->addSql('UPDATE category SET code = "05" where id = 16 ');
        $this->addSql('UPDATE category SET code = "06" where id = 18 ');

        $this->addSql('UPDATE category SET code = "001" where id = 2 ');
        $this->addSql('UPDATE category SET code = "002" where id = 7 ');
        $this->addSql('UPDATE category SET code = "003" where id = 6 ');
        $this->addSql('UPDATE category SET code = "004" where id = 19 ');
        $this->addSql('UPDATE category SET code = "005" where id = 13 ');
        $this->addSql('UPDATE category SET code = "006" where id = 21 ');
        $this->addSql('UPDATE category SET code = "007" where id = 4 ');
        $this->addSql('UPDATE category SET code = "008" where id = 14 ');
        $this->addSql('UPDATE category SET code = "009" where id = 15 ');
        $this->addSql('UPDATE category SET code = "010" where id = 17 ');
        $this->addSql('UPDATE category SET code = "011" where id = 11 ');
        $this->addSql('UPDATE category SET code = "012" where id = 10 ');
        $this->addSql('UPDATE category SET code = "013" where id = 9 ');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
