<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605133515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C74404559D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE respond (id INT AUTO_INCREMENT NOT NULL, order_id_id INT DEFAULT NULL, specialist_id INT DEFAULT NULL, INDEX IDX_99C5D563FCDAEAAA (order_id_id), INDEX IDX_99C5D5637B100C1A (specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialist (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C2274AF49D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404559D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE respond ADD CONSTRAINT FK_99C5D563FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE respond ADD CONSTRAINT FK_99C5D5637B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE specialist ADD CONSTRAINT FK_C2274AF49D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD client_id INT DEFAULT NULL, ADD specialist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id)');
        $this->addSql('CREATE INDEX IDX_F529939819EB6921 ON `order` (client_id)');
        $this->addSql('CREATE INDEX IDX_F52993987B100C1A ON `order` (specialist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987B100C1A');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404559D86650F');
        $this->addSql('ALTER TABLE respond DROP FOREIGN KEY FK_99C5D563FCDAEAAA');
        $this->addSql('ALTER TABLE respond DROP FOREIGN KEY FK_99C5D5637B100C1A');
        $this->addSql('ALTER TABLE specialist DROP FOREIGN KEY FK_C2274AF49D86650F');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE respond');
        $this->addSql('DROP TABLE specialist');
        $this->addSql('DROP INDEX IDX_F529939819EB6921 ON `order`');
        $this->addSql('DROP INDEX IDX_F52993987B100C1A ON `order`');
        $this->addSql('ALTER TABLE `order` DROP client_id, DROP specialist_id');
    }
}
