<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804130050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F8A76ED395');
        $this->addSql('DROP INDEX IDX_835033F8A76ED395 ON genre');
        $this->addSql('ALTER TABLE genre DROP user_id');
        $this->addSql('ALTER TABLE user ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494296D31F ON user (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_835033F8A76ED395 ON genre (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494296D31F');
        $this->addSql('DROP INDEX IDX_8D93D6494296D31F ON user');
        $this->addSql('ALTER TABLE user DROP genre_id');
    }
}
