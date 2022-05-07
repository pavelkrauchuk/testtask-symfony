<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507163103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parameters (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, param_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `parameters` ADD UNIQUE(`param_name`)');
        $this->addSql('INSERT INTO `parameters` (`id`, `value`, `param_name`) VALUES (NULL, \'1000\', \'available_money\')');
        $this->addSql('INSERT INTO `parameters` (`id`, `value`, `param_name`) VALUES (NULL, \'300\', \'max_bonus_for_prize\')');
        $this->addSql('INSERT INTO `parameters` (`id`, `value`, `param_name`) VALUES (NULL, \'300\', \'max_money_for_prize\')');
        $this->addSql('INSERT INTO `parameters` (`id`, `value`, `param_name`) VALUES (NULL, \'1.87\', \'bonus_to_money_conversion_rate\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE parameters');
    }
}
