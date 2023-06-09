<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607211544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD order_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E420DE70 FOREIGN KEY (order_state_id) REFERENCES order_state (id)');
        $this->addSql('CREATE INDEX IDX_F5299398E420DE70 ON `order` (order_state_id)');
        $this->addSql('ALTER TABLE order_state DROP FOREIGN KEY FK_200DA6068D9F6D38');
        $this->addSql('DROP INDEX UNIQ_200DA6068D9F6D38 ON order_state');
        $this->addSql('ALTER TABLE order_state DROP order_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_state ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_state ADD CONSTRAINT FK_200DA6068D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_200DA6068D9F6D38 ON order_state (order_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E420DE70');
        $this->addSql('DROP INDEX IDX_F5299398E420DE70 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_state_id');
    }
}
