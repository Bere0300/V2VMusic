<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031124931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_genre (user_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_6192C8A0A76ED395 (user_id), INDEX IDX_6192C8A04296D31F (genre_id), PRIMARY KEY(user_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_genre ADD CONSTRAINT FK_6192C8A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_genre ADD CONSTRAINT FK_6192C8A04296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_user DROP FOREIGN KEY FK_367E0FB94296D31F');
        $this->addSql('ALTER TABLE genre_user DROP FOREIGN KEY FK_367E0FB9A76ED395');
        $this->addSql('DROP TABLE genre_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre_user (genre_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_367E0FB94296D31F (genre_id), INDEX IDX_367E0FB9A76ED395 (user_id), PRIMARY KEY(genre_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE genre_user ADD CONSTRAINT FK_367E0FB94296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_user ADD CONSTRAINT FK_367E0FB9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_genre DROP FOREIGN KEY FK_6192C8A0A76ED395');
        $this->addSql('ALTER TABLE user_genre DROP FOREIGN KEY FK_6192C8A04296D31F');
        $this->addSql('DROP TABLE user_genre');
    }
}
