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
        $this->addSql('CREATE TABLE available_things (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO `available_things` (`id`, `name`) VALUES (NULL, \'Билет на концерт\')');
        $this->addSql('INSERT INTO `available_things` (`id`, `name`) VALUES (NULL, \'Подарочный сертификат\')');
        $this->addSql('INSERT INTO `available_things` (`id`, `name`) VALUES (NULL, \'Кофеварка\')');
        $this->addSql('INSERT INTO `available_things` (`id`, `name`) VALUES (NULL, \'Мобильный телефон\')');
        $this->addSql('INSERT INTO `available_things` (`id`, `name`) VALUES (NULL, \'Пицца\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE available_things');
    }
}
