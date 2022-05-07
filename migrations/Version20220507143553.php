<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507143553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7ABF396750 FOREIGN KEY (id) REFERENCES prizes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thing CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE thing ADD CONSTRAINT FK_5B4C2C83BF396750 FOREIGN KEY (id) REFERENCES prizes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7ABF396750');
        $this->addSql('ALTER TABLE bonus CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE thing DROP FOREIGN KEY FK_5B4C2C83BF396750');
        $this->addSql('ALTER TABLE thing CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
