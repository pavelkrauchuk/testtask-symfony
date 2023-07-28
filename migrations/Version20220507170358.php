<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507170358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE available_things_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE available_things (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO available_things (id, name) VALUES (nextval(\'available_things_id_seq\'), \'Билет на концерт\')');
        $this->addSql('INSERT INTO available_things (id, name) VALUES (nextval(\'available_things_id_seq\'), \'Подарочный сертификат\')');
        $this->addSql('INSERT INTO available_things (id, name) VALUES (nextval(\'available_things_id_seq\'), \'Кофеварка\')');
        $this->addSql('INSERT INTO available_things (id, name) VALUES (nextval(\'available_things_id_seq\'), \'Мобильный телефон\')');
        $this->addSql('INSERT INTO available_things (id, name) VALUES (nextval(\'available_things_id_seq\'), \'Пицца\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE available_things_id_seq CASCADE');
        $this->addSql('DROP TABLE available_things');
    }
}
