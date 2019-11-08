<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107194842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_impression (comment_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_A6BA3B91F8697D13 (comment_id), INDEX IDX_A6BA3B91A3BA46B6 (impression_id), PRIMARY KEY(comment_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture_impression (picture_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_88BF1B6EE45BDBF (picture_id), INDEX IDX_88BF1B6A3BA46B6 (impression_id), PRIMARY KEY(picture_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_impression ADD CONSTRAINT FK_A6BA3B91F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_impression ADD CONSTRAINT FK_A6BA3B91A3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_impression ADD CONSTRAINT FK_88BF1B6EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_impression ADD CONSTRAINT FK_88BF1B6A3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment_impression');
        $this->addSql('DROP TABLE picture_impression');
    }
}
