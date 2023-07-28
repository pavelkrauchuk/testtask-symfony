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
        $this->addSql('CREATE SEQUENCE parameters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE parameters (id INT NOT NULL, value VARCHAR(255) NOT NULL, param_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO parameters (id, value, param_name) VALUES (nextval(\'parameters_id_seq\'), \'1000\', \'available_money\')');
        $this->addSql('INSERT INTO parameters (id, value, param_name) VALUES (nextval(\'parameters_id_seq\'), \'300\', \'max_bonus_for_prize\')');
        $this->addSql('INSERT INTO parameters (id, value, param_name) VALUES (nextval(\'parameters_id_seq\'), \'300\', \'max_money_for_prize\')');
        $this->addSql('INSERT INTO parameters (id, value, param_name) VALUES (nextval(\'parameters_id_seq\'), \'1.87\', \'bonus_to_money_conversion_rate\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE parameters_id_seq CASCADE');
        $this->addSql('DROP TABLE parameters');
    }
}
