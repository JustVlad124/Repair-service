<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605135828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404559D86650F');
        $this->addSql('DROP INDEX UNIQ_C74404559D86650F ON client');
        $this->addSql('ALTER TABLE client ADD email VARCHAR(180) NOT NULL, ADD password VARCHAR(255) NOT NULL, CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('ALTER TABLE specialist DROP FOREIGN KEY FK_C2274AF49D86650F');
        $this->addSql('DROP INDEX UNIQ_C2274AF49D86650F ON specialist');
        $this->addSql('ALTER TABLE specialist ADD email VARCHAR(180) NOT NULL, ADD password VARCHAR(255) NOT NULL, CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialist ADD CONSTRAINT FK_C2274AF4A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2274AF4E7927C74 ON specialist (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2274AF4A76ED395 ON specialist (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialist DROP FOREIGN KEY FK_C2274AF4A76ED395');
        $this->addSql('DROP INDEX UNIQ_C2274AF4E7927C74 ON specialist');
        $this->addSql('DROP INDEX UNIQ_C2274AF4A76ED395 ON specialist');
        $this->addSql('ALTER TABLE specialist DROP email, DROP password, CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialist ADD CONSTRAINT FK_C2274AF49D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2274AF49D86650F ON specialist (user_id_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74 ON client');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395 ON client');
        $this->addSql('ALTER TABLE client DROP email, DROP password, CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404559D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404559D86650F ON client (user_id_id)');
    }
}
