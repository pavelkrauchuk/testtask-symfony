<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507143053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prizes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bonus (id INT NOT NULL, amount INT NOT NULL, is_admissed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE money (id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, is_converted BOOLEAN NOT NULL, is_transferred BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE prizes (id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F73CF5A6A76ED395 ON prizes (user_id)');
        $this->addSql('CREATE TABLE thing (id INT NOT NULL, name VARCHAR(255) NOT NULL, is_shipped BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7ABF396750 FOREIGN KEY (id) REFERENCES prizes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE money ADD CONSTRAINT FK_B7DF13E4BF396750 FOREIGN KEY (id) REFERENCES prizes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prizes ADD CONSTRAINT FK_F73CF5A6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE thing ADD CONSTRAINT FK_5B4C2C83BF396750 FOREIGN KEY (id) REFERENCES prizes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE prizes_id_seq CASCADE');
        $this->addSql('ALTER TABLE bonus DROP CONSTRAINT FK_9F987F7ABF396750');
        $this->addSql('ALTER TABLE money DROP CONSTRAINT FK_B7DF13E4BF396750');
        $this->addSql('ALTER TABLE prizes DROP CONSTRAINT FK_F73CF5A6A76ED395');
        $this->addSql('ALTER TABLE thing DROP CONSTRAINT FK_5B4C2C83BF396750');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE money');
        $this->addSql('DROP TABLE prizes');
        $this->addSql('DROP TABLE thing');
    }
}
