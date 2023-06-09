<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607205901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_state (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, state_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_200DA6068D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_state ADD CONSTRAINT FK_200DA6068D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` DROP state');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_state DROP FOREIGN KEY FK_200DA6068D9F6D38');
        $this->addSql('DROP TABLE order_state');
        $this->addSql('ALTER TABLE `order` ADD state VARCHAR(255) DEFAULT NULL');
    }
}
