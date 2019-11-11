<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109001224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_impression (event_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_7CB5689E71F7E88B (event_id), INDEX IDX_7CB5689EA3BA46B6 (impression_id), PRIMARY KEY(event_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_impression ADD CONSTRAINT FK_7CB5689E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_impression ADD CONSTRAINT FK_7CB5689EA3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event CHANGE event_periode event_period VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F893E5F2F7B');
        $this->addSql('DROP INDEX IDX_16DB4F893E5F2F7B ON picture');
        $this->addSql('ALTER TABLE picture CHANGE event_id_id event_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8971F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8971F7E88B ON picture (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE event_impression');
        $this->addSql('ALTER TABLE event CHANGE event_period event_periode VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8971F7E88B');
        $this->addSql('DROP INDEX IDX_16DB4F8971F7E88B ON picture');
        $this->addSql('ALTER TABLE picture CHANGE event_id event_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F893E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F893E5F2F7B ON picture (event_id_id)');
    }
}
