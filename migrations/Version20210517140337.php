<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517140337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_category_fr VARCHAR(255) NOT NULL, name_category_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, commentary LONGTEXT NOT NULL, rate INT NOT NULL, commentary_photo VARCHAR(255) DEFAULT NULL, valid TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, statut SMALLINT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dog (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sex_fr VARCHAR(255) NOT NULL, age INT NOT NULL, description_fr LONGTEXT NOT NULL, description_en LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_812C397D12469DE2 (category_id), INDEX IDX_812C397DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title_fr VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, text_fr LONGTEXT NOT NULL, text_en LONGTEXT NOT NULL, news_img VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_1DD39950A76ED395 (user_id), FULLTEXT INDEX IDX_1DD3995079D32E2346FF21AF1AC19CD625ED935A (title_fr, title_en, text_fr, text_en), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_page (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, text_fr LONGTEXT NOT NULL, text_en LONGTEXT NOT NULL, img_file VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397D12469DE2');
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397DA76ED395');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD39950A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE dog');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE site_page');
        $this->addSql('DROP TABLE user');
    }
}
