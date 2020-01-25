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
final class Version20200127224338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `with` CHANGE win win INT NOT NULL, CHANGE lose lose INT NOT NULL');
        $this->addSql('ALTER TABLE vs CHANGE win win INT NOT NULL, CHANGE lose lose INT NOT NULL');
        $this->addSql('ALTER TABLE champion ADD name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45437EB4AEA34913 ON champion (reference)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45437EB45E237E06 ON champion (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_45437EB4AEA34913 ON champion');
        $this->addSql('DROP INDEX UNIQ_45437EB45E237E06 ON champion');
        $this->addSql('ALTER TABLE champion DROP name');
        $this->addSql('ALTER TABLE vs CHANGE win win INT DEFAULT 0 NOT NULL, CHANGE lose lose INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE `with` CHANGE win win INT DEFAULT 0 NOT NULL, CHANGE lose lose INT DEFAULT 0 NOT NULL');
    }
}
