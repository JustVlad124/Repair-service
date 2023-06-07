<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605170544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_offer (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, order_id INT DEFAULT NULL, specialist_id INT DEFAULT NULL, INDEX IDX_8ABB1B8919EB6921 (client_id), INDEX IDX_8ABB1B898D9F6D38 (order_id), INDEX IDX_8ABB1B897B100C1A (specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialist_respond (id INT AUTO_INCREMENT NOT NULL, order_id_id INT DEFAULT NULL, specialist_id INT DEFAULT NULL, INDEX IDX_FFAB8AACFCDAEAAA (order_id_id), INDEX IDX_FFAB8AAC7B100C1A (specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_offer ADD CONSTRAINT FK_8ABB1B8919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_offer ADD CONSTRAINT FK_8ABB1B898D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE client_offer ADD CONSTRAINT FK_8ABB1B897B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE specialist_respond ADD CONSTRAINT FK_FFAB8AACFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE specialist_respond ADD CONSTRAINT FK_FFAB8AAC7B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE respond DROP FOREIGN KEY FK_99C5D5637B100C1A');
        $this->addSql('ALTER TABLE respond DROP FOREIGN KEY FK_99C5D563FCDAEAAA');
        $this->addSql('DROP TABLE respond');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE respond (id INT AUTO_INCREMENT NOT NULL, order_id_id INT DEFAULT NULL, specialist_id INT DEFAULT NULL, INDEX IDX_99C5D563FCDAEAAA (order_id_id), INDEX IDX_99C5D5637B100C1A (specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE respond ADD CONSTRAINT FK_99C5D5637B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE respond ADD CONSTRAINT FK_99C5D563FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE client_offer DROP FOREIGN KEY FK_8ABB1B8919EB6921');
        $this->addSql('ALTER TABLE client_offer DROP FOREIGN KEY FK_8ABB1B898D9F6D38');
        $this->addSql('ALTER TABLE client_offer DROP FOREIGN KEY FK_8ABB1B897B100C1A');
        $this->addSql('ALTER TABLE specialist_respond DROP FOREIGN KEY FK_FFAB8AACFCDAEAAA');
        $this->addSql('ALTER TABLE specialist_respond DROP FOREIGN KEY FK_FFAB8AAC7B100C1A');
        $this->addSql('DROP TABLE client_offer');
        $this->addSql('DROP TABLE specialist_respond');
    }
}
