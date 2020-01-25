<?php

declare(strict_types=1);

/*
 * This file is part of a Salah Medfay project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201103415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mate (id INT AUTO_INCREMENT NOT NULL, first_champion_id INT DEFAULT NULL, second_champion_id INT DEFAULT NULL, win INT DEFAULT NULL, lose INT DEFAULT NULL, INDEX IDX_D79678F0648ADA29 (first_champion_id), INDEX IDX_D79678F0939A4BF5 (second_champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mate ADD CONSTRAINT FK_D79678F0648ADA29 FOREIGN KEY (first_champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE mate ADD CONSTRAINT FK_D79678F0939A4BF5 FOREIGN KEY (second_champion_id) REFERENCES champion (id)');
        $this->addSql('DROP TABLE `with`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `with` (id INT AUTO_INCREMENT NOT NULL, first_champion_id INT DEFAULT NULL, second_champion_id INT DEFAULT NULL, win INT DEFAULT NULL, lose INT DEFAULT NULL, INDEX IDX_9890E20E648ADA29 (first_champion_id), INDEX IDX_9890E20E939A4BF5 (second_champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `with` ADD CONSTRAINT FK_9890E20E648ADA29 FOREIGN KEY (first_champion_id) REFERENCES champion (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `with` ADD CONSTRAINT FK_9890E20E939A4BF5 FOREIGN KEY (second_champion_id) REFERENCES champion (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE mate');
    }
}
