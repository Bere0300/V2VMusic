<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803144634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_EE1D56BC4296D31F ON musique (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC4296D31F');
        $this->addSql('DROP INDEX IDX_EE1D56BC4296D31F ON musique');
        $this->addSql('ALTER TABLE musique DROP genre_id');
    }
}
