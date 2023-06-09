<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609181744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, specialist_id INT DEFAULT NULL, service_name VARCHAR(255) NOT NULL, cost VARCHAR(100) NOT NULL, INDEX IDX_E19D9AD212469DE2 (category_id), INDEX IDX_E19D9AD27B100C1A (specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_order (service_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_5C5B7E7FED5CA9E6 (service_id), INDEX IDX_5C5B7E7F8D9F6D38 (order_id), PRIMARY KEY(service_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_category (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD27B100C1A FOREIGN KEY (specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE service_order ADD CONSTRAINT FK_5C5B7E7FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_order ADD CONSTRAINT FK_5C5B7E7F8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE address ADD country_subject VARCHAR(100) DEFAULT NULL, ADD city VARCHAR(255) NOT NULL, ADD street VARCHAR(255) DEFAULT NULL, ADD number VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE specialist ADD about_myself VARCHAR(1000) DEFAULT NULL, ADD education VARCHAR(255) DEFAULT NULL, ADD work_experience VARCHAR(255) DEFAULT NULL, ADD avatar_image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD surname VARCHAR(255) NOT NULL, ADD patronymic VARCHAR(100) DEFAULT NULL, ADD phone_number VARCHAR(30) NOT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD27B100C1A');
        $this->addSql('ALTER TABLE service_order DROP FOREIGN KEY FK_5C5B7E7FED5CA9E6');
        $this->addSql('ALTER TABLE service_order DROP FOREIGN KEY FK_5C5B7E7F8D9F6D38');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_order');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('ALTER TABLE `order` DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE specialist DROP about_myself, DROP education, DROP work_experience, DROP avatar_image');
        $this->addSql('ALTER TABLE `user` DROP first_name, DROP surname, DROP patronymic, DROP phone_number, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE address DROP country_subject, DROP city, DROP street, DROP number');
    }
}
