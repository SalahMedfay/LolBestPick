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
final class Version20200126225455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `with` (id INT AUTO_INCREMENT NOT NULL, first_champion_id INT DEFAULT NULL, second_champion_id INT DEFAULT NULL, win INT NOT NULL DEFAULT 0, lose INT NOT NULL DEFAULT 0, INDEX IDX_9890E20E648ADA29 (first_champion_id), INDEX IDX_9890E20E939A4BF5 (second_champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vs (id INT AUTO_INCREMENT NOT NULL, first_champion_id INT DEFAULT NULL, second_champion_id INT DEFAULT NULL, win INT NOT NULL DEFAULT 0, lose INT NOT NULL DEFAULT 0, INDEX IDX_F1B0EC09648ADA29 (first_champion_id), INDEX IDX_F1B0EC09939A4BF5 (second_champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `with` ADD CONSTRAINT FK_9890E20E648ADA29 FOREIGN KEY (first_champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE `with` ADD CONSTRAINT FK_9890E20E939A4BF5 FOREIGN KEY (second_champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE vs ADD CONSTRAINT FK_F1B0EC09648ADA29 FOREIGN KEY (first_champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE vs ADD CONSTRAINT FK_F1B0EC09939A4BF5 FOREIGN KEY (second_champion_id) REFERENCES champion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `with` DROP FOREIGN KEY FK_9890E20E648ADA29');
        $this->addSql('ALTER TABLE `with` DROP FOREIGN KEY FK_9890E20E939A4BF5');
        $this->addSql('ALTER TABLE vs DROP FOREIGN KEY FK_F1B0EC09648ADA29');
        $this->addSql('ALTER TABLE vs DROP FOREIGN KEY FK_F1B0EC09939A4BF5');
        $this->addSql('DROP TABLE `with`');
        $this->addSql('DROP TABLE vs');
        $this->addSql('DROP TABLE champion');
    }
}
