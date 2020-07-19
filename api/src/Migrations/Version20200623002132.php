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
final class Version20200623002132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mate CHANGE win win INT NOT NULL, CHANGE lose lose INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX mate_idx ON mate (first_champion_id, second_champion_id)');
        $this->addSql('CREATE UNIQUE INDEX vs_idx ON vs (first_champion_id, second_champion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX mate_idx ON mate');
        $this->addSql('ALTER TABLE mate CHANGE win win INT DEFAULT NULL, CHANGE lose lose INT DEFAULT NULL');
        $this->addSql('DROP INDEX vs_idx ON vs');
    }
}
